<?php

namespace App\Services;

use App\Network;
use App\NetworkMeta;
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
            
            $network_raw[$network->network_id] = $network;
        }
        return $network_raw;       
    }
}