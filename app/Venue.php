<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $primaryKey = 'venue_id';

    protected $table = 'venue';

    protected $fillable = [
        'org_id', 'venue_name', 'venue_description', 'venue_address', 'venue_address_notes', 'venue_status'
    ];
}
