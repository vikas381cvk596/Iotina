<?php

namespace App\Services;

use App\AccessPoint;
use DB;
use App\Services\OrganisationService;
use App\Services\VenueService;

class AccessPointService
{
    public function createAccessPoint ($venue_id, $ap_name, $ap_desc, $ap_serial, $ap_tags) 
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
        $apData['ap_serial'] = $ap_serial;
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
            $ap_raw[$ap->ap_id] = $ap;
        }
        return $ap_raw;       
    }
}