<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NetworkAPMapping extends Model
{
    protected $primaryKey = 'network_ap_id';

    protected $table = 'network_ap_mapping';

    protected $fillable = [
        'network_id', 'ap_id', 'org_id'
    ];
}
