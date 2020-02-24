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
        $ap_record = $this->getAccessPointDetails($ap_id);
        
        $ap_data = new \stdClass();
        $ap_data->ap_info = $ap_record;
        $ap_data->status = "success";
        $ap_data->ap_id_last = $ap_id;
        $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $ap_data;
    }

    public function updateAccessPoint ($ap_id, $venue_id, $ap_name, $ap_desc, $ap_identifier, $ap_serial, $ap_tags) 
    {
        $return_flag = 'success';

        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();

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

        $current_date = new \DateTime();
        $current_date = $current_date->format('Y-m-d h:i:s'); 
        $apData['updated_at'] = $current_date;


        $result = DB::table('access_point')->where(['ap_id' => $ap_id, 'org_id' => $org_id, ])->update($apData);
        if (!$result) {
            $return_flag = 'ap_not_found';
        } else {
            $return_flag = 'success';
        }

        $ap_record = $this->getAccessPointDetails($ap_id);
        
        $ap_data = new \stdClass();
        $ap_data->ap_info = $ap_record;
        $ap_data->status = $return_flag;
        $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $ap_data;
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
            
            $ap_search = '';
            if ($ap->ap_identifier == "Serial Number") {
                $ap_search = $ap->ap_serial;
            } else if ($ap->ap_identifier == "MAC Address") {
                $ap_search = $ap->ap_mac_address;
            }
            $apDataUpdate = [];
            if ($ap->ap_status == 'not_yet_connected') {
                $collectionService = new CollectionService();

                $ap_mongo = $collectionService->getAPStatus($org_id, $ap->ap_identifier, $ap_search, 'all_time');
                $ap_mongo = json_decode($ap_mongo);
                $ap->ap_status = $ap_mongo->status;
                if ($ap_mongo->status == "connected") {
                    $ap->ap_ip_address = $ap_mongo->ip_address;   

                    $apDataUpdate['ap_ip_address'] = $ap->ap_ip_address;
                    $apDataUpdate['ap_status'] = $ap->ap_status;

                    if (isset($ap_mongo->ap_serial)) {
                        $ap->ap_serial = $ap_mongo->ap_serial;
                        $apDataUpdate['ap_serial'] = $ap_mongo->ap_serial;
                    } 

                    if (isset($ap_mongo->ap_mac_address)) {
                        $ap->ap_mac_address = $ap_mongo->ap_mac_address;
                        $apDataUpdate['ap_mac_address'] = $ap_mongo->ap_mac_address;
                    }

                    DB::table('access_point')->where(['ap_id' => $ap->ap_id])->update($apDataUpdate);  
                }
            } else if ($ap->ap_status == 'connected' || $ap->ap_status == 'disconnected') {
                $collectionService = new CollectionService();
                $ap_mongo = $collectionService->getAPStatus($org_id, $ap->ap_identifier, $ap_search, 'last_24_hours');
                $ap_mongo = json_decode($ap_mongo);
                $ap->ap_status = $ap_mongo->status;

                if (isset($ap_mongo->ap_serial)) {
                    $ap->ap_serial = $ap_mongo->ap_serial;
                    $apDataUpdate['ap_serial'] = $ap_mongo->ap_serial;
                } 

                if (isset($ap_mongo->ap_mac_address)) {
                    $ap->ap_mac_address = $ap_mongo->ap_mac_address;
                    $apDataUpdate['ap_mac_address'] = $ap_mongo->ap_mac_address;
                } 

                $apDataUpdate['ap_status'] = $ap->ap_status;
                DB::table('access_point')->where(['ap_id' => $ap->ap_id])->update($apDataUpdate);
            } 

            if ($ap->ap_status != 'not_yet_connected') {
                $collectionService = new CollectionService();
                
                $input_filters = new \stdClass();
                $input_filters->org_id = $org_id;
                $input_filters->ap_mac_address = $ap->ap_mac_address;
                
                $clientCount = $collectionService->getAllClientsConnected(json_encode($input_filters), 'ap_page');
                $ap->client_count = $clientCount;
            }

            $ap_raw[$ap->ap_id] = $ap;
        }
        return $ap_raw;       
    }

    public function getAccessPointDetails ($ap_id) {
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
        $ap_record = DB::table('access_point')
            ->where("org_id", "=", $org_id)
            ->where(function ($query) use ($ap_id) {
                $query->where('ap_serial', '=', $ap_id)
                      ->orWhere('ap_mac_address', '=', $ap_id)
                      ->orWhere('ap_id', '=', $ap_id);
            })
            ->first();

        return $ap_record;
    }

    public function deleteAccessPoint ($ap_id) 
    {
        $return_flag = 'success';
        
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();

        $ap_record = DB::table('access_point')
                ->where("org_id", "=", $org_id)
                ->where("ap_id", "=", $ap_id)
                ->first();

        if ($ap_record) {
            $deleteAP = DB::table('access_point')
                ->where("org_id", "=", $org_id)
                ->where("ap_id", "=", $ap_id)
                ->delete();
        } else {
            $return_flag = 'Access Point not found'; 
        }
        
        $ap_data = new \stdClass();
        $ap_data->status = $return_flag;
        $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $ap_data;
    }
}