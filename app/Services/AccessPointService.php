<?php

namespace App\Services;

use App\AccessPoint;
use DB;
use App\Services\OrganisationService;
use App\Services\CollectionService;
use App\Services\VenueService;

class AccessPointService
{
    public function createAccessPoint ($venue_id, $ap_name, $ap_desc, $ap_identifier, $ap_serial, $ap_tags) 
    {
        $return_flag = 'success';
        $ap_id = 1;
        $ap_id_last = DB::table('access_point')->orderBy('ap_id', 'desc')->first();
        if (!is_null($ap_id_last)) {
            $ap_id = $ap_id_last->ap_id + 1;
        }

        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
        $apData['ap_id'] = $ap_id;
        $apData['venue_id'] = $venue_id;
        $apData['org_id'] = $org_id;
        $apData['ap_name'] = $ap_name;
        $apData['ap_identifier'] = $ap_identifier;
        if ($ap_identifier == "Serial Number") {
            $apData['ap_serial'] = $ap_serial;
        } else if ($ap_identifier == "MAC Address") {
            $apData['ap_mac_address'] = $ap_serial;
        }
        $apData['ap_status'] = 'not_yet_connected';
        if ($ap_desc) {
            $apData['ap_desc'] = $ap_desc;
        }
        if ($ap_tags) {
            $apData['ap_tags'] = $ap_tags;
        }
        AccessPoint::create($apData);

        return $return_flag;
    }

    public function getAllAccessPoints () {
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();

        $all_aps = DB::table('access_point')->where(['org_id' => $org_id])->get();
        $ap_raw = [];
        foreach ($all_aps as $ap) {
            $venueService = new VenueService();
            $venue_name = $venueService->getVenueNameByID($ap->venue_id);
        
            $ap->venue_name = $venue_name;
            
            if ($ap->ap_identifier == "Serial Number") {
                $ap_search = $ap->ap_serial;
            } else if ($ap->ap_identifier == "MAC Address") {
                $ap_search = $ap->ap_mac_address;
            }

            if ($ap->ap_status == 'not_yet_connected') {
                $collectionService = new CollectionService();

                if ($ap->ap_identifier)
                $ap_mongo = $collectionService->getAPStatus($org_id, $ap->ap_identifier, $ap_search, 'all_time');
                $ap_mongo = json_decode($ap_mongo);
                $ap->ap_status = $ap_mongo->status;
                if ($ap_mongo->status == "connected") {
                    $ap->ap_ip_address = $ap_mongo->ip_address;   

                    $apDataUpdate['ap_ip_address'] = $ap->ap_ip_address;
                    $apDataUpdate['ap_status'] = $ap->ap_status;
                    DB::table('access_point')->where(['ap_id' => $ap->ap_id])->update($apDataUpdate);  
                }
            } else if ($ap->ap_status == 'connected' || $ap->ap_status == 'disconnected') {
                $collectionService = new CollectionService();
                $ap_mongo = $collectionService->getAPStatus($org_id, $ap->ap_identifier, $ap_search, 'last_24_hours');
                $ap_mongo = json_decode($ap_mongo);
                $ap->ap_status = $ap_mongo->status;

                if (isset($ap_mongo->ap_serial)) {
                    $apDataUpdate['ap_serial'] = $ap_mongo->ap_serial;
                } 

                if (isset($ap_mongo->ap_mac_address)) {
                    $apDataUpdate['ap_mac_address'] = $ap_mongo->ap_mac_address;
                } 

                $apDataUpdate['ap_status'] = $ap->ap_status;
                DB::table('access_point')->where(['ap_id' => $ap->ap_id])->update($apDataUpdate);
            } 
            $ap_raw[$ap->ap_id] = $ap;
        }
        return $ap_raw;       
    }
}