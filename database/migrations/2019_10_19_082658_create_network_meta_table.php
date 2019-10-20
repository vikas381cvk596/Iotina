<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_meta', function (Blueprint $table) {
            $table->increments('network_meta_id');
            $table->integer('network_id');
            $table->string('backup_phrase')->nullable();
            $table->string('security_protocol')->nullable();
            $table->string('passphrase_format')->nullable();
            $table->string('passphrase_length')->nullable();
            $table->string('passphrase_expiry')->nullable();
            $table->string('captive_portal_provider')->nullable();
            $table->string('captive_portal_url')->nullable();
            $table->string('integration_key')->nullable();
            $table->string('walled_garden')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('network_meta');
    }
}
