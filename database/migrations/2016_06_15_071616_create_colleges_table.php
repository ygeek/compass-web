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
            $table->text('description')->nullable();

            $table->string('telephone_number')->nullable();
            $table->string('founded_in')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();

            $table->integer('qs_ranking')->nullable();
            $table->integer('us_new_ranking')->nullable();
            $table->integer('times_ranking')->nullable();
            $table->integer('domestic_ranking')->nullable();

            $table->string('badge_path')->nullable();
            $table->string('background_image_path')->nullable();

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
