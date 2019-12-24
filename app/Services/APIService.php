<?php

namespace App\Services;
use App\Services\OrganisationService;
use App\Services\NetworkService;
use App\Services\VenueService;
use App\Venue;
use DB;

class APIService
{
    public function getAPData($ap_serial)
    {
        $ap_data = new \stdClass();
        
        if (!$ap_serial) {
            $ap_data->status = 'error';
            $ap_data->msg = 'Access point identifier not found (serial number or mac address)';
            $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $ap_data;
        }
        
        $ap_db = DB::table('access_point')
        ->whereRaw( 'UPPER(`ap_serial`) like ?', array( strtoupper($ap_serial) ) )->first();
        //->where(['UPPER(`ap_serial`)' => strtoupper($ap_serial)])->first();
        $ap_db_mac = DB::table('access_point')
        ->whereRaw( 'UPPER(`ap_mac_address`) like ?', array( strtoupper($ap_serial) ) )->first();
        
        if (!is_null($ap_db)) {

            $organisationService = new OrganisationService();
            $org_name = $organisationService->getOrganisationDetails($ap_db->org_id);
            $setting_time_interval = 300;
            $org_interval = $organisationService->getTimeInterval();

            if ($org_interval != '') {
                $setting_time_interval = (int)$org_interval;
            }  

            $networkService = new NetworkService();
            $network_names = $networkService->getAllNetworkDataByVenueID($ap_db->org_id, $ap_db->venue_id);

            $ap_data->organization_name = $org_name;
            $ap_data->organization_id = $ap_db->org_id;
            $ap_data->venue_id = $ap_db->venue_id;
            $ap_data->group_id = 0;
            $ap_data->network_name = $network_names;
            $ap_data->stats_collection_interval = $setting_time_interval;
            $ap_data->stats_publish_interval = $setting_time_interval;
            $ap_data->stats_publish_url = 'ec2-15-206-63-2.ap-south-1.compute.amazonaws.com:9092';
            $ap_data->schema_server_url = 'http://ec2-15-206-63-2.ap-south-1.compute.amazonaws.com:8081';


            
        } else if (!is_null($ap_db_mac)) {

            $organisationService = new OrganisationService();
            $org_name = $organisationService->getOrganisationDetails($ap_db_mac->org_id);
            $setting_time_interval = 300;
            $org_interval = $organisationService->getTimeInterval();

            if ($org_interval != '') {
                $setting_time_interval = (int)$org_interval;
            }  

            $networkService = new NetworkService();
            $network_names = $networkService->getAllNetworkDataByVenueID($ap_db_mac->org_id, $ap_db_mac->venue_id);

            $ap_data->organization_name = $org_name;
            $ap_data->organization_id = $ap_db_mac->org_id;
            $ap_data->venue_id = $ap_db_mac->venue_id;
            $ap_data->group_id = 0;
            $ap_data->network_name = $network_names;
            $ap_data->stats_collection_interval = $setting_time_interval;
            $ap_data->stats_publish_interval = $setting_time_interval;
            $ap_data->stats_publish_url = 'ec2-15-206-63-2.ap-south-1.compute.amazonaws.com:9092';
            $ap_data->schema_server_url = 'http://ec2-15-206-63-2.ap-south-1.compute.amazonaws.com:8081';


            
        } else {
            $ap_data->status = 'not_found';
            $ap_data->msg = 'No records found for given access point: '.$ap_serial;
            $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $ap_data;
        }

        $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $ap_data;
    }

    public function getOrganisationDetails()
    {
        $org_data = new \stdClass();

        if (isset($_GET['api_token'])) {
            $api_token = $_GET['api_token'];
            $user = DB::table('users')->where(['api_token' => $api_token])->first();

            if ($user) {
                $organisationService = new OrganisationService();
                $org_data->org_data = $organisationService->getAllOrganisationDetails(); 
                $org_data->return_msg = "success";
            
            } else {
                $org_data->return_msg = "User not found";
            }

        } else {
            $org_data->return_msg = "API token not provided in the request";
        }

        $org_data = json_encode($org_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $org_data;
    }

    public function getClusterDetails($cluster_id)
    {
        $venue_data = new \stdClass();
        if ($cluster_id != '') {
            $venueService = new VenueService();
            $cluster_info = new \stdClass();

            $cluster_info = $venueService->getVenueDetailsByID($cluster_id);
            if ($cluster_info != '') {
                $venue_data->cluster_id = $cluster_info->venue_id;
                $venue_data->cluster_name = $cluster_info->venue_name;
                $venue_data->cluster_description = $cluster_info->venue_description;
                $venue_data->cluster_status = $cluster_info->venue_status;
                $venue_data->created_at = $cluster_info->created_at;
                $venue_data->return_msg = "success";
            } else {
                $venue_data->return_msg = "Cluster not found";
            }
        } else {
            $venue_data->return_msg = "Cluster ID not provided";
        }

        $venue_data = json_encode($venue_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $venue_data;
    }

    public function getAllClusters($input_filters)
    {
        $page_num = $input_filters->input('page_num');

        $build_query = Venue::query();
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
        
        $build_query = $build_query->where("org_id", "=", $org_id);
        $build_query->orderBy('created_at','asc');
        if (is_numeric($page_num)) {
            $cluster_records = $build_query->paginate(5,['*'],'page',$page_num);
        } else {
            $cluster_records = $build_query->paginate(5);
        }

        $cluster_raw = [];
        foreach ($cluster_records as $row){
            $row = json_decode($row);
            $cluster_record = new \stdClass();
            
            $cluster_record->cluster_id = $row->venue_id;
            $cluster_record->cluster_name = $row->venue_name;
            $cluster_record->cluster_description = $row->venue_description;
            $cluster_record->cluster_status = $row->venue_status;
            $cluster_record->created_at = $row->created_at;

            $cluster_raw[] = $cluster_record;
        }
        
        $venue_data = new \stdClass();
        $venue_data->return_msg = "success";
        $venue_data->current_page = $cluster_records->currentPage();
        $venue_data->total_records = $cluster_records->total();
        $venue_data->page_size = $cluster_records->perPage();
        $venue_data->all_data = $cluster_raw;

        $venue_data = json_encode($venue_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $venue_data;
    }

    public function createCluster($input_fields)
    {
        $venue_name = $input_fields->input('cluster_name');;
        $venue_desc = $input_fields->input('cluster_description');;
        $venue_add = '';
        $venue_add_notes = '';

        $venue_data = new \stdClass();


        if ($venue_name != '') {
            $venueService = new VenueService();
            $venue_status = $venueService->createVenue($venue_name, $venue_desc, $venue_add, $venue_add_notes);
            if ($venue_status == "venue_name_error") {
                $venue_data->return_msg = "Cluster name already exists";
            } else {
                $cluster_info = new \stdClass();
                $cluster_info = $venueService->getVenueDetailsByName($venue_name);
                if ($cluster_info != '') {
                    $venue_data->cluster_id = $cluster_info->venue_id;
                    $venue_data->cluster_name = $cluster_info->venue_name;
                    $venue_data->cluster_description = $cluster_info->venue_description;
                    $venue_data->cluster_status = $cluster_info->venue_status;
                    $venue_data->created_at = $cluster_info->created_at;
                } 
                $venue_data->return_msg = "success";
            }
        } else {
            $venue_data->return_msg = "cluster name not provided";
        }

        $venue_data = json_encode($venue_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $venue_data;
    }

    public function updateCluster($cluster_id, $input_fields)
    {
        $cluster_name = $input_fields->input('cluster_name');;
        $cluster_desc = $input_fields->input('cluster_description');;

        $cluster_data = new \stdClass();


        if ($cluster_id != '') {
            $venueService = new VenueService();
            $output = $venueService->updateVenue($cluster_id, $cluster_name, $cluster_desc);
            if ($output == "venue_name_duplicate") {
                $cluster_data->return_msg = "Cluster name already exists";
            } else if ($output == "venue_name_missing") {
                $cluster_data->return_msg = "Cluster name missing";
            } else if ($output == "venue_not_found") {
                $cluster_data->return_msg = "Cluster not found";
            } else {
                $cluster_info = new \stdClass();
                $cluster_info = $venueService->getVenueDetailsByName($cluster_name);
                if ($cluster_info != '') {
                    $cluster_data->cluster_id = $cluster_info->venue_id;
                    $cluster_data->cluster_name = $cluster_info->venue_name;
                    $cluster_data->cluster_description = $cluster_info->venue_description;
                    $cluster_data->cluster_status = $cluster_info->venue_status;
                    $cluster_data->created_at = $cluster_info->created_at;
                } 
                $cluster_data->return_msg = "success";
            }
        } else {
            $cluster_data->return_msg = "cluster id not provided";
        }

        $cluster_data = json_encode($cluster_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $cluster_data;
    }
}
