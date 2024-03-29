<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            //是否考试不同学历要求不一致
            $table->boolean('multiple_degree')->default(false);
            $table->boolean('is_requirement')->default(false);
            //成绩需要打标签 如高考省份
            $table->boolean('tagable')->default(false);
            //该考试的子科目
            $table->json('sections');
            $table->json('score_sections');
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
        Schema::drop('examinations');
    }
}
