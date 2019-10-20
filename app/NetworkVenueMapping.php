<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NetworkVenueMapping extends Model
{
    protected $primaryKey = 'network_venue_id';

    protected $table = 'network_venue_mapping';

    protected $fillable = [
        'network_id', 'venue_id', 'org_id'
    ];
}
