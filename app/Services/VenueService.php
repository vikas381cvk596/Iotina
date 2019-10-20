<?php

namespace App\Services;

use App\Venue;
use DB;
use App\Services\OrganisationService;

class VenueService
{
    public function createVenue ($venue_name, $venue_desc, $venue_add, $venue_add_notes) 
    {
        $return_flag = 'success';
        if ($this->venueExists($venue_name)) {

            $venue_id = 1;
            $venue_id_last = DB::table('venue')->orderBy('venue_id', 'desc')->first();
            if (!is_null($venue_id_last)) {
                $venue_id = $venue_id_last->venue_id + 1;
            }

            $organisationService = new OrganisationService();
            $org_id = $organisationService->getOrganisationID();
            $venueData['venue_id'] = $venue_id;
            $venueData['org_id'] = $org_id;
            $venueData['venue_name'] = $venue_name;
            $venueData['venue_address'] = $venue_add;
            if ($venue_desc) {
                $venueData['venue_description'] = $venue_desc;
            }
            if ($venue_add_notes) {
                $venueData['venue_address_notes'] = $venue_add_notes;
            }
            Venue::create($venueData);
        } else {
            $return_flag = 'venue_name_error';
        }

        return $return_flag;
    }

    public function venueExists ($venue_name) 
    {
        $venue_record = DB::table('venue')->where(['venue_name' => $venue_name])->first();
        if (!is_null($venue_record)) {
            return false;
        }
        return true;
    }

    public function getAllVenues () {
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();

        //$venue_raw['2'] = 'aaa';

        $all_venues = DB::table('venue')->where(['org_id' => $org_id])->get();
        $venue_raw = [];
        foreach ($all_venues as $venue) {
            $network_raw = DB::table('network_venue_mapping')->where(['venue_id' => $venue->venue_id, 'org_id' => $org_id])->get();
            $networkCount = $network_raw->count();

            $ap_raw = DB::table('access_point')->where(['venue_id' => $venue->venue_id, 'org_id' => $org_id])->get();
            $apCount = $ap_raw->count();


            $venue->network_count = $networkCount;
            $venue->ap_count = $apCount;
            $venue_raw[$venue->venue_id] = $venue;
        }
        return $venue_raw;       
    }

    public function getVenueNameByID ($venue_id) {
        $venue = DB::table('venue')->where(['venue_id' => $venue_id])->first();
        $venue_name = '';
        if (!is_null($venue)) {
            $venue_name = $venue->venue_name;
        }
        
        return $venue_name;       
    }
}