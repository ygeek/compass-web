<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colleges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('chinese_name');
            $table->string('english_name');
            $table->text('description');

            $table->string('telephone_number');
            $table->string('founded_in');
            $table->string('address');
            $table->string('website');

            $table->integer('qs_ranking');
            $table->integer('us_new_ranking');
            $table->integer('times_ranking');
            $table->integer('domestic_ranking');

            $table->string('badge_path');
            $table->string('background_image_path');

            $table->integer('city_id')->unsigned();
            $table->foreign('city_id')
                ->references('id')->on('cities')
                ->onDelete('cascade');

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
        Schema::drop('colleges');
    }
}
