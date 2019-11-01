<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVlanToNetworkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('network', function (Blueprint $table) {
            $table->string('network_vlan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('network', function (Blueprint $table) {
            $table->dropColumn('network_vlan');
        });
    }
}

{
    {
        //
    },
    {
        //
    },

}
