<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExaminationScoreWeightIdToColleges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->integer('examination_score_weight_id')->unsigned()->nullable();
            $table->foreign('examination_score_weight_id')
                  ->references('id')
                  ->on('examination_score_weights');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->dropForeign('colleges_examination_score_weight_id_foreign');
            $table->dropColumn('examination_score_weight_id');
        });
    }
}
