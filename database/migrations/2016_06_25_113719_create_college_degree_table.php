<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollegeDegreeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('college_degree', function (Blueprint $table) {
            $table->integer('college_id')->unsigned()->index();
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('cascade');
            $table->integer('degree_id')->unsigned()->index();
            $table->foreign('degree_id')->references('id')->on('degrees');
            $table->integer('examination_score_weight_id')->unsigned()->nullable()->index();
            $table->foreign('examination_score_weight_id')->references('id')->on('examination_score_weights');
            $table->primary(['college_id', 'degree_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('college_degree');
    }
}
