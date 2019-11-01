<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    protected $primaryKey = 'network_id';

    protected $table = 'network';

    protected $fillable = [
        'org_id', 'network_name', 'network_description', 'network_type', 'network_status', 'network_vlan'
    ];
}
