<?php

namespace App\Services;
use App\Services\OrganisationService;
use App\Services\NetworkService;
use App\Services\VenueService;
use App\Services\AccessPointService;
use App\Venue;
use App\AccessPoint;
use App\Network;
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
        $venue_name = $input_fields->input('cluster_name');
        $venue_desc = $input_fields->input('cluster_description');
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

    public function getAPDetails($ap_id)
    {
        $ap_data = new \stdClass();
        if ($ap_id != '') {
            $apService = new AccessPointService();
            $ap_info = new \stdClass();

            $ap_info = $apService->getAccessPointDetails($ap_id);
            if ($ap_info != '') {
                $ap_info->return_msg = "success";
                $ap_data = $ap_info;
            } else {
                $ap_data->return_msg = "Access Point not found";
            }

        } else {
            $ap_data->return_msg = "Access Point ID not provided";
        }

        $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $ap_data;
    }

    public function createAP($input_fields)
    {
        $ap_name = $input_fields->input('ap_name');
        $ap_description = $input_fields->input('ap_description');
        $ap_serial = $input_fields->input('ap_serial');
        $ap_ip_address = $input_fields->input('ap_ip_address');
        $ap_tags = $input_fields->input('ap_tags');
        $ap_mac_address = $input_fields->input('ap_mac_address');
        $ap_cluster_id = $input_fields->input('cluster_id');
        $ap_identifier = $input_fields->input('ap_identifier');

        if ($ap_identifier == '') {
            if ($ap_serial != '') {
                $ap_identifier = "Serial Number";
            } else if ($ap_mac_address != '') {
                $ap_identifier = "MAC Address";
            } 
        }

        $ap_data = new \stdClass();

        if ($ap_name == '') {
            $ap_data->return_msg = "Access Point name not provided";
            $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $ap_data;
        } else if ($ap_cluster_id == '') {
            $ap_data->return_msg = "Cluster ID not provided";
            $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $ap_data;
        } else if (!is_numeric($ap_cluster_id)) {
            $ap_data->return_msg = "Cluster ID must be numeric";
            $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $ap_data;
        } else if ($ap_serial == '' && $ap_mac_address == '') {
            $ap_data->return_msg = "Access Point Serial ID or MAC Address is required";
            $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $ap_data;
        }

        $venueService = new VenueService();
        $venue_exists = $venueService->getVenueDetailsByID($ap_cluster_id);
        if (!$venue_exists) {
            $ap_data->return_msg = "Cluster not found";
            $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $ap_data;
        }

        $apService = new AccessPointService();
        $output = $apService->createAccessPoint($ap_cluster_id, $ap_name, $ap_description, $ap_identifier, $ap_serial, $ap_tags);
        $ap_data = json_decode($output)->ap_info;
        $ap_data->return_msg = "success";

        $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $ap_data;
    }

    public function updateAP($ap_id, $input_fields)
    {
        $ap_name = $input_fields->input('ap_name');
        $ap_description = $input_fields->input('ap_description');
        $ap_serial = $input_fields->input('ap_serial');
        $ap_ip_address = $input_fields->input('ap_ip_address');
        $ap_tags = $input_fields->input('ap_tags');
        $ap_mac_address = $input_fields->input('ap_mac_address');
        $ap_cluster_id = $input_fields->input('cluster_id');
        $ap_identifier = $input_fields->input('ap_identifier');

        if ($ap_identifier == '') {
            if ($ap_serial != '') {
                $ap_identifier = "Serial Number";
            } else if ($ap_mac_address != '') {
                $ap_identifier = "MAC Address";
            } 
        }

        $ap_data = new \stdClass();

        if ($ap_name == '') {
            $ap_data->return_msg = "Access Point name not provided";
            $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $ap_data;
        } else if ($ap_cluster_id == '') {
            $ap_data->return_msg = "Cluster ID not provided";
            $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $ap_data;
        } else if (!is_numeric($ap_cluster_id)) {
            $ap_data->return_msg = "Cluster ID must be numeric";
            $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $ap_data;
        } else if ($ap_serial == '' && $ap_mac_address == '') {
            $ap_data->return_msg = "Access Point Serial ID or MAC Address is required";
            $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $ap_data;
        }

        $venueService = new VenueService();
        $venue_exists = $venueService->getVenueDetailsByID($ap_cluster_id);
        if (!$venue_exists) {
            $ap_data->return_msg = "Cluster not found";
            $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $ap_data;
        }

        $apService = new AccessPointService();
        $output = $apService->updateAccessPoint($ap_id, $ap_cluster_id, $ap_name, $ap_description, $ap_identifier, $ap_serial, $ap_tags);

        if (json_decode($output)->ap_info) {
            $ap_data = json_decode($output)->ap_info;
            $ap_data->return_msg = json_decode($output)->status;
        } else {
            $ap_data->return_msg = json_decode($output)->status;
        }

        if (json_decode($output)->status == "ap_not_found") {
            $ap_data->return_msg = "Access point not found";
        } else {
            $ap_data = json_decode($output)->ap_info;
            $ap_data->return_msg = "success";
        }

        $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $ap_data;
    }

    public function getAllAPs($input_filters)
    {
        $page_num = $input_filters->input('page_num');

        $build_query = AccessPoint::query();
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
        
        $build_query = $build_query->where("org_id", "=", $org_id);
        $build_query->orderBy('created_at','asc');
        if (is_numeric($page_num)) {
            $ap_records = $build_query->paginate(5,['*'],'page',$page_num);
        } else {
            $ap_records = $build_query->paginate(5);
        }

        $ap_raw = [];
        foreach ($ap_records as $row){
            $row = json_decode($row);
            $ap_record = new \stdClass();
            
            $ap_record->ap_id = $row->ap_id;
            $ap_record->cluster_id = $row->venue_id;
            $ap_record->ap_name = $row->ap_name;
            $ap_record->ap_description = $row->ap_description;
            $ap_record->ap_status = $row->ap_status;
            $ap_record->ap_serial = $row->ap_serial;
            $ap_record->ap_mac_address = $row->ap_mac_address;
            $ap_record->ap_identifier = $row->ap_identifier;
            $ap_record->ap_tags = $row->ap_tags;
            $ap_record->ap_ip_address = $row->ap_ip_address;
            $ap_record->ap_mesh_role = $row->ap_mesh_role;
            
            $ap_record->created_at = $row->created_at;
            $ap_record->updated_at = $row->updated_at;

            $ap_raw[] = $ap_record;
        }
        
        $ap_data = new \stdClass();
        $ap_data->return_msg = "success";
        $ap_data->current_page = $ap_records->currentPage();
        $ap_data->total_records = $ap_records->total();
        $ap_data->page_size = $ap_records->perPage();
        $ap_data->all_data = $ap_raw;

        $ap_data = json_encode($ap_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $ap_data;
    }

    public function createWifiNetwork($input_fields)
    {
        $network_info = new \stdClass();
        $network_info->network_name = $input_fields->input('network_name');
        $network_info->network_desc = $input_fields->input('network_description');
        $network_info->network_type = strtoupper($input_fields->input('network_type'));
        $network_info->network_status = $input_fields->input('network_status');
        $network_info->network_vlan = $input_fields->input('network_vlan');
        $network_info->backup_passphrase = $input_fields->input('backup_phrase');
        $network_info->security_protocol = strtoupper($input_fields->input('security_protocol'));
        $network_info->passphrase_expiry = $input_fields->input('passphrase_expiry');
        $network_info->network_venues = $input_fields->input('cluster_list');

        $wifi_data = new \stdClass();

        if ($network_info->network_name == '') {
            $wifi_data->return_msg = "Error: Network name not provided";
            $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $wifi_data;
        } else if ($network_info->network_type == '') {
            $wifi_data->return_msg = "Error: Network type not provided";
            $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $wifi_data;
        } else if ($network_info->backup_passphrase == '') {
            $wifi_data->return_msg = "Error: Network passphrase not provided";
            $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $wifi_data;
        } else if ($network_info->security_protocol == '') {
            $wifi_data->return_msg = "Error: Network Security Protocol not provided";
            $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $wifi_data;
        } else if ($network_info->passphrase_expiry == '') {
            $wifi_data->return_msg = "Error: Network Password Expiry not provided";
            $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $wifi_data;
        } 
        
        $networkService = new NetworkService();
        $duplicate_name_check = $networkService->duplicateNetworkName($network_info->network_name);

        if ($duplicate_name_check == "duplicate") {
            $wifi_data->return_msg = "Error: Network name already exists";
            $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $wifi_data;
        }
        
        $network_info->network_venues = json_encode($network_info->network_venues);
        $output = $networkService->createNetwork(json_encode($network_info));

        if ($output) {
            if (isset(json_decode($output)->network_info)) {
                if (isset(json_decode($output)->network_info->network)) {
                    $network_record = json_decode($output)->network_info->network;
                    if (isset($network_record->network_id)) {
                        $wifi_data->network_id = $network_record->network_id;
                    }
                    if (isset($network_record->network_name)) {
                        $wifi_data->network_name = $network_record->network_name;
                    } else {
                        $wifi_data->network_name = '';
                    }
                    if (isset($network_record->network_description)) {
                        $wifi_data->network_description = $network_record->network_description;
                    } else {
                        $wifi_data->network_description = '';
                    }
                    if (isset($network_record->network_type)) {
                        $wifi_data->network_type = $network_record->network_type;
                    } else {
                        $wifi_data->network_type = '';
                    }
                    if (isset($network_record->network_status)) {
                        $wifi_data->network_status = $network_record->network_status;
                    } else {
                        $wifi_data->network_status = '';
                    }
                    if (isset($network_record->network_vlan)) {
                        $wifi_data->network_vlan = $network_record->network_vlan;
                    } else {
                        $wifi_data->network_vlan = '';
                    }
                    if (isset($network_record->created_at)) {
                        $wifi_data->created_at = $network_record->created_at;
                    } else {
                        $wifi_data->created_at = '';
                    }
                }  
                if (isset(json_decode($output)->network_info->network_meta)) {
                    $network_meta_record = json_decode($output)->network_info->network_meta;
                    if (isset($network_meta_record->backup_phrase)) {
                        $wifi_data->backup_phrase = $network_meta_record->backup_phrase;
                    } else {
                        $wifi_data->backup_phrase = '';
                    }
                    if (isset($network_meta_record->security_protocol)) {
                        $wifi_data->security_protocol = $network_meta_record->security_protocol;
                    } else {
                        $wifi_data->security_protocol = '';
                    }
                    if (isset($network_meta_record->passphrase_expiry)) {
                        $wifi_data->passphrase_expiry = $network_meta_record->passphrase_expiry;
                    } else {
                        $wifi_data->passphrase_expiry = '';
                    }
                }  
                if (isset(json_decode($output)->network_info->cluster_list)) {
                    $wifi_data->cluster_list = json_decode($output)->network_info->cluster_list;
                } 
                if (isset(json_decode($output)->network_info->count_venue)) {
                    $wifi_data->count_venue = json_decode($output)->network_info->count_venue;
                } 
                if (isset(json_decode($output)->network_info->count_ap)) {
                    $wifi_data->count_ap = json_decode($output)->network_info->count_ap;
                }  
            }
        }
        $network_data = json_decode($output)->network_info->network;
        $wifi_data->return_msg = "success";

        $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $wifi_data;
    }

    public function getAllWifiNetworksAPI($input_filters)
    {
        $page_num = $input_filters->input('page_num');

        $build_query = Network::query();
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
        
        $build_query = $build_query->where("org_id", "=", $org_id);
        $build_query->orderBy('created_at','asc');
        if (is_numeric($page_num)) {
            $network_records = $build_query->paginate(5,['*'],'page',$page_num);
        } else {
            $network_records = $build_query->paginate(5);
        }

        $network_raw = [];
        foreach ($network_records as $row){
            $row = json_decode($row);
            $network_record = new \stdClass();
            
            $network_record->network_id = $row->network_id;
            $network_record->network_name = $row->network_name;
            $network_record->network_description = $row->network_description;
            $network_record->network_type = $row->network_type;
            $network_record->network_vlan = $row->network_vlan;
            
            $network_meta_record = DB::table('network_meta')->where(['network_id' => $row->network_id])->first();
            if ($network_meta_record) {
                $network_record->backup_phrase = $network_meta_record->backup_phrase;
                $network_record->security_protocol = $network_meta_record->security_protocol;
                $network_record->passphrase_expiry = $network_meta_record->passphrase_expiry;
            }

            $nv_mapping = DB::table('network_venue_mapping')->where(['network_id' => $row->network_id, 'org_id' => $org_id])->get();
                
            $count_venue = 0;
            $count_ap = 0;

            if ($nv_mapping) {
                $count_venue = count($nv_mapping);
            }
            $cluster_list = [];
            foreach ($nv_mapping as $venue) {
                $cluster_list[] = $venue->venue_id;

                $access_points = DB::table('access_point')->where(['venue_id' => $venue->venue_id, 'org_id' => $org_id])->get();

                if ($access_points) {
                    $count_ap = $count_ap + count($access_points);
                }
            }
            $network_record->cluster_list = $cluster_list;
            $network_record->count_venue = strval($count_venue);
            $network_record->count_ap = strval($count_ap);

            $network_record->created_at = $row->created_at;
            $network_record->updated_at = $row->updated_at;
            $network_raw[] = $network_record;
        }
        
        $wifi_data = new \stdClass();
        $wifi_data->return_msg = "success";
        $wifi_data->current_page = $network_records->currentPage();
        $wifi_data->total_records = $network_records->total();
        $wifi_data->page_size = $network_records->perPage();
        $wifi_data->all_data = $network_raw;

        $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $wifi_data;
    }

    public function getWifiNetworkDetails($network_id)
    {
        $wifi_data = new \stdClass();
        if ($network_id != '') {
            $networkService = new NetworkService();
            $network_info = new \stdClass();
            $network_info = $networkService->getNetworkDetails($network_id);
            if ($network_info) {
                if (isset($network_info['network'])) {
                    $network_record = $network_info['network'];
                    if (isset($network_record->network_id)) {
                        $wifi_data->network_id = $network_record->network_id;
                    }
                    if (isset($network_record->network_name)) {
                        $wifi_data->network_name = $network_record->network_name;
                    } else {
                        $wifi_data->network_name = '';
                    }
                    if (isset($network_record->network_description)) {
                        $wifi_data->network_description = $network_record->network_description;
                    } else {
                        $wifi_data->network_description = '';
                    }
                    if (isset($network_record->network_type)) {
                        $wifi_data->network_type = $network_record->network_type;
                    } else {
                        $wifi_data->network_type = '';
                    }
                    if (isset($network_record->network_status)) {
                        $wifi_data->network_status = $network_record->network_status;
                    } else {
                        $wifi_data->network_status = '';
                    }
                    if (isset($network_record->network_vlan)) {
                        $wifi_data->network_vlan = $network_record->network_vlan;
                    } else {
                        $wifi_data->network_vlan = '';
                    }
                    if (isset($network_record->created_at)) {
                        $wifi_data->created_at = $network_record->created_at;
                    } else {
                        $wifi_data->created_at = '';
                    }
                    $wifi_data->return_msg = "success";
                }  else {
                    $wifi_data->return_msg = "Network not found";
                }
                if (isset($network_info['network_meta'])) {
                    $network_meta_record = $network_info['network_meta'];
                    if (isset($network_meta_record->backup_phrase)) {
                        $wifi_data->backup_phrase = $network_meta_record->backup_phrase;
                    } else {
                        $wifi_data->backup_phrase = '';
                    }
                    if (isset($network_meta_record->security_protocol)) {
                        $wifi_data->security_protocol = $network_meta_record->security_protocol;
                    } else {
                        $wifi_data->security_protocol = '';
                    }
                    if (isset($network_meta_record->passphrase_expiry)) {
                        $wifi_data->passphrase_expiry = $network_meta_record->passphrase_expiry;
                    } else {
                        $wifi_data->passphrase_expiry = '';
                    }
                }  
                if (isset($network_info['cluster_list'])) {
                    $wifi_data->cluster_list = $network_info['cluster_list'];
                } 
                if (isset($network_info['count_venue'])) {
                    $wifi_data->count_venue = $network_info['count_venue'];
                } 
                if (isset($network_info['count_ap'])) {
                    $wifi_data->count_ap = $network_info['count_ap'];
                } 
            }
            //$wifi_data = $network_info;
            /*if ($network_info != '') {
                $wifi_data = $network_info;
                $wifi_data->return_msg = "success";
            } else {
                $wifi_data->return_msg = "Network not found";
            }*/

        } else {
            $wifi_data->return_msg = "Network ID not provided";
        }

        $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $wifi_data;
    }

    public function updateWifiNetwork($network_id, $input_fields)
    {
        $network_info = new \stdClass();
        $network_info->network_name = $input_fields->input('network_name');
        $network_info->network_desc = $input_fields->input('network_description');
        $network_info->network_type = strtoupper($input_fields->input('network_type'));
        $network_info->network_status = $input_fields->input('network_status');
        $network_info->network_vlan = $input_fields->input('network_vlan');
        $network_info->backup_passphrase = $input_fields->input('backup_phrase');
        $network_info->security_protocol = strtoupper($input_fields->input('security_protocol'));
        $network_info->passphrase_expiry = $input_fields->input('passphrase_expiry');
        $network_info->network_venues = $input_fields->input('cluster_list');

        $wifi_data = new \stdClass();
        $networkService = new NetworkService();
        
        $network_record_exists = $networkService->getNetworkDetails($network_id);

        if (empty($network_record_exists)) {
            $wifi_data->return_msg = "Error: Network not found";
            $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $wifi_data;
        }
        if ($network_info->network_name == '') {
            $wifi_data->return_msg = "Error: Network name not provided";
            $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $wifi_data;
        } else if ($network_info->network_type == '') {
            $wifi_data->return_msg = "Error: Network type not provided";
            $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $wifi_data;
        } else if ($network_info->backup_passphrase == '') {
            $wifi_data->return_msg = "Error: Network passphrase not provided";
            $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $wifi_data;
        } else if ($network_info->security_protocol == '') {
            $wifi_data->return_msg = "Error: Network Security Protocol not provided";
            $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $wifi_data;
        } else if ($network_info->passphrase_expiry == '') {
            $wifi_data->return_msg = "Error: Network Password Expiry not provided";
            $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $wifi_data;
        } 
        
        //$duplicate_name_check = $networkService->duplicateNetworkName($network_info->network_name);

       /* if ($duplicate_name_check == "duplicate") {
            $wifi_data->return_msg = "Error: Network name already exists";
            $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            return $wifi_data;
        }*/
        
        $network_info->network_venues = json_encode($network_info->network_venues);
        $output = $networkService->updateNetwork($network_id, json_encode($network_info));

        if ($output) {
            if (isset(json_decode($output)->status)) {
                if (json_decode($output)->status == 'network_name_duplicate') {
                    $wifi_data->return_msg = "Error: Network name already exists";
                    $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
                    return $wifi_data;
                } 
            }
            if (isset(json_decode($output)->network_info)) {
                if (isset(json_decode($output)->network_info->network)) {
                    $network_record = json_decode($output)->network_info->network;
                    if (isset($network_record->network_id)) {
                        $wifi_data->network_id = $network_record->network_id;
                    }
                    if (isset($network_record->network_name)) {
                        $wifi_data->network_name = $network_record->network_name;
                    } else {
                        $wifi_data->network_name = '';
                    }
                    if (isset($network_record->network_description)) {
                        $wifi_data->network_description = $network_record->network_description;
                    } else {
                        $wifi_data->network_description = '';
                    }
                    if (isset($network_record->network_type)) {
                        $wifi_data->network_type = $network_record->network_type;
                    } else {
                        $wifi_data->network_type = '';
                    }
                    if (isset($network_record->network_status)) {
                        $wifi_data->network_status = $network_record->network_status;
                    } else {
                        $wifi_data->network_status = '';
                    }
                    if (isset($network_record->network_vlan)) {
                        $wifi_data->network_vlan = $network_record->network_vlan;
                    } else {
                        $wifi_data->network_vlan = '';
                    }
                    if (isset($network_record->created_at)) {
                        $wifi_data->created_at = $network_record->created_at;
                    } else {
                        $wifi_data->created_at = '';
                    }
                }  
                if (isset(json_decode($output)->network_info->network_meta)) {
                    $network_meta_record = json_decode($output)->network_info->network_meta;
                    if (isset($network_meta_record->backup_phrase)) {
                        $wifi_data->backup_phrase = $network_meta_record->backup_phrase;
                    } else {
                        $wifi_data->backup_phrase = '';
                    }
                    if (isset($network_meta_record->security_protocol)) {
                        $wifi_data->security_protocol = $network_meta_record->security_protocol;
                    } else {
                        $wifi_data->security_protocol = '';
                    }
                    if (isset($network_meta_record->passphrase_expiry)) {
                        $wifi_data->passphrase_expiry = $network_meta_record->passphrase_expiry;
                    } else {
                        $wifi_data->passphrase_expiry = '';
                    }
                }  
                if (isset(json_decode($output)->network_info->cluster_list)) {
                    $wifi_data->cluster_list = json_decode($output)->network_info->cluster_list;
                } 
                if (isset(json_decode($output)->network_info->count_venue)) {
                    $wifi_data->count_venue = json_decode($output)->network_info->count_venue;
                } 
                if (isset(json_decode($output)->network_info->count_ap)) {
                    $wifi_data->count_ap = json_decode($output)->network_info->count_ap;
                }  
            }
        }
        //$wifi_data = json_decode($output)->network_info;
        $wifi_data->return_msg = "success";

        $wifi_data = json_encode($wifi_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $wifi_data;

    }
    
}
