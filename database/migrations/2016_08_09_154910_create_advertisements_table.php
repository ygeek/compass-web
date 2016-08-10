<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->increments('id');

            $table->string('background_image_path');
            $table->string('link');
            $table->integer('priority');

            $table->boolean('page_colleges_index')->default(false)->index();
            $table->boolean('page_colleges_show')->default(false)->index();
            $table->boolean('page_colleges_rank')->default(false)->index();
            $table->boolean('page_estimate_index')->default(false)->index();

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
        Schema::drop('advertisements');
    }
}
