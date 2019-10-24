<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class AccessPoint extends Eloquent
{
	//protected $connection = 'mongodb';
    protected $primaryKey = 'ap_id';

    protected $table = 'access_point';

    protected $fillable = [
        'org_id', 'venue_id', 'ap_name', 'ap_description', 'ap_serial', 'ap_tags', 'ap_status', 'ap_model', 'ap_ip_address', 'ap_mac_address', 'ap_mesh_role'
    ];
}
