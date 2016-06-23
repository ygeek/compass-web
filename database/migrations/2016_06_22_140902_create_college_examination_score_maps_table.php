<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollegeExaminationScoreMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('college_examination_score_maps', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('college_id')->unsigned();
            $table->foreign('college_id')
                  ->references('id')
                  ->on('colleges');

            $table->json('map');
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
        Schema::drop('college_examination_score_maps');
    }
}
