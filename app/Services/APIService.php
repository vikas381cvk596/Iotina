<?php

namespace App\Services;
use App\Services\OrganisationService;
use App\Services\NetworkService;

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
    	->whereRaw( 'UPPER(`ap_serial`) like ?', array( strtoupper($ap_serial) ) )->first();;
    	//->where(['UPPER(`ap_serial`)' => strtoupper($ap_serial)])->first();

    	if (!is_null($ap_db)) {

    		$organisationService = new OrganisationService();
        	$org_name = $organisationService->getOrganisationDetails($ap_db->org_id);

        	$networkService = new NetworkService();
        	$network_names = $networkService->getAllNetworkDataByVenueID($ap_db->org_id, $ap_db->venue_id);

    		$ap_data->organization_name = $org_name;
    		$ap_data->organization_id = $ap_db->org_id;
    		$ap_data->venue_id = $ap_db->venue_id;
    		$ap_data->group_id = 0;
    		$ap_data->network_name = $network_names;
    		$ap_data->stats_collection_interval = 300;
    		$ap_data->stats_publish_interval = 300;
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
}
