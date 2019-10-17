<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessPointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_point', function (Blueprint $table) {
            $table->increments('ap_id');
            $table->integer('org_id');
            $table->integer('venue_id');
            $table->string('ap_name')->nullable();
            $table->string('ap_description')->nullable();
            $table->string('ap_serial')->nullable();
            $table->string('ap_tags')->nullable();
            $table->string('ap_status')->nullable();
            $table->string('ap_model')->nullable();
            $table->string('ap_ip_address')->nullable();
            $table->string('ap_mac_address')->nullable();
            $table->string('ap_mesh_role')->nullable();
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
        Schema::dropIfExists('access_point');
    }
}
