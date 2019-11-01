<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    protected $primaryKey = 'org_id';

    protected $table = 'organisation';

    protected $fillable = [
        'org_id', 'org_name', 'org_address', 'org_city', 'org_state', 'org_country', 'org_status'
    ];
}
