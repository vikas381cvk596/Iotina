<?php

namespace App\Services;

use App\Network;
use App\NetworkMeta;
use App\NetworkVenueMapping;
use DB;
use App\Services\OrganisationService;
use App\Services\VenueService;

class NetworkService
{
    public function createNetwork ($network_data_list) 
    {
        $network_data_list = json_decode($network_data_list, true);
        $return_flag = 'success';
        $network_id = 1;
        $network_id_last = DB::table('network')->orderBy('network_id', 'desc')->first();
        if (!is_null($network_id_last)) {
            $network_id = $network_id_last->network_id + 1;
        }

        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
        $networkData['network_id'] = $network_id;
        $networkData['org_id'] = $org_id;
        $networkData['network_type'] = $network_data_list['network_type'];
        
        if ($network_data_list['network_name']) {
            $networkData['network_name'] = $network_data_list['network_name'];
        }
        if ($network_data_list['network_desc']) {
            $networkData['network_description'] = $network_data_list['network_desc'];
        }
        if ($network_data_list['network_vlan']) {
            $networkData['network_vlan'] = $network_data_list['network_vlan'];
        }

        $network_meta_id = 1;
        $network_meta_id_last = DB::table('network_meta')->orderBy('network_meta_id', 'desc')->first();
        if (!is_null($network_meta_id_last)) {
            $network_meta_id = $network_meta_id_last->network_meta_id + 1;
        }

        $networkMetaData = [];
        $networkMetaData['network_meta_id'] = $network_meta_id;
        $networkMetaData['network_id'] = $network_id;
        if ($network_data_list['security_protocol']) {
            $networkMetaData['security_protocol'] = $network_data_list['security_protocol'];
        }
        if ($network_data_list['passphrase_format']) {
            $networkMetaData['passphrase_format'] = $network_data_list['passphrase_format'];
        }
        if ($network_data_list['passphrase_expiry']) {
            $networkMetaData['passphrase_expiry'] = $network_data_list['passphrase_expiry'];
        }
        if ($network_data_list['backup_passphrase']) {
            $networkMetaData['backup_phrase'] = $network_data_list['backup_passphrase'];
        }
        if ($network_data_list['passphrase_length']) {
            $networkMetaData['passphrase_length'] = $network_data_list['passphrase_length'];
        }
        
        Network::create($networkData);
        NetworkMeta::create($networkMetaData);

        if ($network_data_list['network_venues']) {
            //getNetworkIDByName()
            $network_venues = json_decode($network_data_list['network_venues']);
            foreach ($network_venues as $venue_id) {
                $networkVenueData = [];

                $network_venue_id_last = DB::table('network_venue_mapping')->orderBy('network_venue_id', 'desc')->first();
                if (!is_null($network_venue_id_last)) {
                    $network_venue_id = $network_venue_id_last->network_venue_id + 1;
                }
                $network_venue_id = 1;
                $networkVenueData['network_venue_id'] = $network_venue_id;
                $networkVenueData['network_id'] = $network_id;
                $networkVenueData['org_id'] = $org_id;
                $networkVenueData['venue_id'] = $venue_id;
                NetworkVenueMapping::create($networkVenueData);
            }
        }
        
        return $return_flag;
    }

    public function getAllWifiNetworks () {
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();

        $all_networks = DB::table('network')->where(['org_id' => $org_id])->get();
        $network_raw = [];
        foreach ($all_networks as $network) {
            $network_meta = DB::table('network_meta')->where(['network_id' => $network->network_id])->first();
            if (!is_null($network_meta)) {
                $network->backup_phrase = $network_meta->backup_phrase;     
            }

            $nv_mapping = DB::table('network_venue_mapping')->where(['network_id' => $network->network_id, 'org_id' => $network->org_id])->get();
            /*if (!$count_venue) {
                $count_venue = '0';
            }*/
            /*$count_venue = DB::table('network_venue_mapping')->where(['network_id' => $network->network_id, 'org_id' => $network->org_id])->count();*/
            $count_venue = 0;
            $count_ap = 0;
            if ($nv_mapping) {
                $count_venue = count($nv_mapping);
            }

            foreach ($nv_mapping as $venue) {
                $access_points = DB::table('access_point')->where(['venue_id' => $venue->venue_id, 'org_id' => $network->org_id])->get();

                if ($access_points) {
                    $count_ap = $count_ap + count($access_points);
                }
            }

            $network->count_venue = strval($count_venue);
            $network->count_ap = strval($count_ap);
            $network_raw[$network->network_id] = $network;
        }
        return $network_raw;       
    }

    public function duplicateNetworkName ($network_name) 
    {
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
        $network = DB::table('network')->where(['org_id' => $org_id, 'network_name' => $network_name])->first();
        if ($network) {
            return 'duplicate';
        }
        return 'not-duplicate';
    }

    public function getAllNetworkDataByVenueID($org_id, $venue_id) 
    {

        $query = "select DISTINCT(n.network_name) from network n, network_venue_mapping v where n.network_id = v.network_id and v.venue_id = ".$venue_id." and n.org_id = ".$org_id.";";
        
        $results = DB::select($query);
        $network_names = [];
        foreach ($results as $row) {
            $network_names[] = $row->network_name;
        }
        return $network_names;
        /*$network_mapping = DB::table('network_venue_mapping')->where(['org_id' => $org_id, 'venue_id' => $venue_id])->get();
        $network_raw = [];
        foreach ($network_mapping as $network_map) {
            $network_id = $network_map->network_id
            $network_meta = DB::table('network_meta')->where(['network_id' => $network->network_id])->first();
            if (!is_null($network_meta)) {
                $network->backup_phrase = $network_meta->backup_phrase;     
            }*/
    }
}