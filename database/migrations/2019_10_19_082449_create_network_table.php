<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network', function (Blueprint $table) {
            $table->increments('network_id');
            $table->integer('org_id');
            $table->string('network_name')->nullable();
            $table->string('network_description')->nullable();
            $table->string('network_type')->nullable();
            $table->string('network_status')->nullable();
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
        Schema::dropIfExists('network');
    }
}
