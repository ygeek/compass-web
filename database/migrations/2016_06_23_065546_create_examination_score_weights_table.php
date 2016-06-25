<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExaminationScoreWeightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examination_score_weights', function (Blueprint $table) {
            $table->increments('id');
            $table->json('weights');

            //权重关联国家
            $table->integer('country_id')->unsigned()->unllable();
            $table->foreign('country_id')
                ->references('id')
                ->on('administrative_areas');

            //权重关联学历
            $table->integer('degree_id')->unsigned()->unllable();
            $table->foreign('degree_id')
                ->references('id')
                ->on('degrees');
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
        Schema::drop('examination_score_weights');
    }
}
