<?php

namespace App\Services;

use App\Venue;
use DB;
use App\Services\OrganisationService;
use App\Services\CollectionService;

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

    public function updateVenue ($venue_id, $venue_name, $venue_desc) 
    {
        $return_flag = 'success';
        if ($venue_name == '') {
            $return_flag = 'venue_name_missing';
        } else {
            $organisationService = new OrganisationService();
            $org_id = $organisationService->getOrganisationID();

            $organisationService = new OrganisationService();
            $org_id = $organisationService->getOrganisationID();
            $venue_records = DB::table('venue')
                ->where("org_id", "=", $org_id)
                ->where("venue_name", "=", $venue_name)
                ->where("venue_id", "!=", $venue_id)
                ->get();
            $venue_records_count = count($venue_records);

            if ($venue_records_count > 0) {
                $return_flag = 'venue_name_duplicate';
            } else {
                $venueData['venue_name'] = $venue_name;
                $venueData['venue_description'] = $venue_desc;

                $current_date = new \DateTime();
                $current_date = $current_date->format('Y-m-d h:i:s'); 
                $venueData['updated_at'] = $current_date;


                $result = DB::table('venue')->where(['venue_id' => $venue_id, 'org_id' => $org_id, ])->update($venueData);
                if (!$result) {
                    $return_flag = 'venue_not_found';
                } else {
                    $return_flag = 'success';
                }
            }
        } 

        return $return_flag;
    }
    

    public function venueExists ($venue_name) 
    {
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
        $venue_record = DB::table('venue')->where(['venue_name' => $venue_name, 'org_id' => $org_id])->first();
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

            $collectionService = new CollectionService();
            
            $input_filters = new \stdClass();
            $input_filters->org_id = $org_id;
            $input_filters->venue_id = $venue->venue_id;
            
            $clientCount = $collectionService->getAllClientsConnected(json_encode($input_filters), 'venue_page');


            

            $venue->network_count = $networkCount;
            $venue->ap_count = $apCount;
            $venue->client_count = $clientCount;
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

    public function getVenueDetailsByName ($venue_name) 
    {
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
        $venue_record = DB::table('venue')->where(['venue_name' => $venue_name, 'org_id' => $org_id])->first();

        return $venue_record;
    }

    public function getVenueDetailsByID ($venue_id) 
    {
        $venue_record = DB::table('venue')->where(['venue_id' => $venue_id])->first();
        
        return $venue_record;
    }
}