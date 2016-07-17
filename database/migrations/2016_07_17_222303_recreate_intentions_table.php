<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecreateIntentionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('intentions');

        Schema::create('intentions', function (Blueprint $table) {
            $table->increments('id');
            $table->json('data');
            $table->string('name');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->enum('state', ['unassigned', 'assigned'])->default('unassigned')->index();
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
        Schema::drop('intentions');
    }
}
