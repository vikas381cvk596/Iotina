<?php

namespace App\Services;

use App\Organisation;
use DB;
use Illuminate\Support\Facades\Auth;


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
}