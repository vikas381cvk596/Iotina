<?php

namespace App\Services;

use App\Organisation;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Services\CollectionService;

class OrganisationService
{
    public function createOrganisation($org_name)
    {
    	$org_id = 0; //Org Already Exists Error
    	$org_record = DB::table('organisation')->where(['org_name' => $org_name])->first();
    	if (!$org_record) {

    		$org_id = 1;
    		$org_id_last = DB::table('organisation')->orderBy('org_id', 'desc')->first();
            if (!is_null($org_id_last)) {
                $org_id = $org_id_last->org_id + 1;
            }

    		$orgData['org_id'] = $org_id;
            $orgData['org_name'] = $org_name;
    		Organisation::create($orgData);
    	} 
    	return $org_id;
    }

    public function getOrganisationID() {
        $org_id = 0;
        $user_id = Auth::id();
        $user_record = DB::table('users')->where(['id' => $user_id])->first();
        if (!is_null($user_record)) {
            $org_id = $user_record->org_id;
        }
        return $org_id;
    } 

    public function getOrganisationDetails($org_id) {
        $org_name = '';
        $org_record = DB::table('organisation')->where(['org_id' => $org_id])->first();
        if (!is_null($org_record)) {
            $org_name = $org_record->org_name;
        }
        return $org_name;
    }       

    public function getDashboardData() {
        $org_id = $this->getOrganisationID();

        //$network_raw = DB::table('network_venue_mapping')->where(['venue_id' => $venue->venue_id, 'org_id' => $org_id])->get();
        //$networkCount = $network_raw->count();

        $venue_raw = DB::table('venue')->where(['org_id' => $org_id])->get();
        $venue_count = $venue_raw->count();

        $ap_raw = DB::table('access_point')->where(['org_id' => $org_id])->get();
        $ap_count = $ap_raw->count();

        $network_raw = DB::table('network')->where(['org_id' => $org_id])->get();
        $network_count = $network_raw->count();

        $network_raw = DB::table('network')->where(['org_id' => $org_id])->get();
        $network_count = $network_raw->count();

        $collectionService = new CollectionService();
        $input_filters = new \stdClass();
        $input_filters->org_id = $org_id;
        $clients_count = $collectionService->getAllClientsConnected(json_encode($input_filters), 'dashboard_page');
        
        $dashboard_data = new \stdClass();
        $dashboard_data->org_id = $org_id;
        $dashboard_data->venue_count = $venue_count;
        $dashboard_data->ap_count = $ap_count;
        $dashboard_data->network_count = $network_count;
        $dashboard_data->clients_count = $clients_count;

        $dashboard_data = json_encode($dashboard_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $dashboard_data;
    }

    public function setTimeInterval ($setting_time_interval) {
        $org_id = $this->getOrganisationID();

        $orgUpdate['setting_time_interval'] = $setting_time_interval;
        DB::table('organisation')->where(['org_id' => $org_id])->update($orgUpdate); 

        return $setting_time_interval;
    }

    public function getTimeInterval() {
        $org_id = $this->getOrganisationID();
        $org_record = DB::table('organisation')->where(['org_id' => $org_id])->first();
        $setting_time_interval = '300';

        if ($org_record) {
            $setting_time_interval = $org_record->setting_time_interval;
        } 

        return $setting_time_interval;
    }
}