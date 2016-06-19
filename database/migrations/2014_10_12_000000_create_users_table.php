<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name');
            $table->string("phone_number", 20)->unique();
            $table->string('email')->nullable();
            $table->string('password');

            $table->timestamp("register_time");
            $table->string("register_ip", 40);
            $table->string("last_login_ip")->nullable();
            $table->timestamp("last_login_time")->nullable();

            $table->rememberToken();
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
        Schema::drop('users');
    }
}
