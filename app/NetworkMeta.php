<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NetworkMeta extends Model
{
    protected $primaryKey = 'network_meta_id';

    protected $table = 'network_meta';

    protected $fillable = [
        'network_id', 'backup_phrase', 'security_protocol', 'passphrase_format', 'passphrase_length', 'passphrase_expiry', 'captive_portal_provider', 'captive_portal_url', 'integration_key', 'walled_garden'
    ];
}
