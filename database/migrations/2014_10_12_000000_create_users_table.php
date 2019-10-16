<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('org_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('role')->nullable();
            $table->string('status')->nullable();
            $table->string('mobile_number')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the mi
     grations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
