<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntentionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intentions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('degree_id')->unsigned();
            $table->foreign('degree_id')->references('id')->on('degrees');

            $table->integer('college_id')->unsigned();
            $table->foreign('college_id')->references('id')->on('colleges');

            //专业名称
            $table->string('speciality_name');

            //学生姓名
            $table->string('student_name');

            //学生联系电话
            $table->string('student_phone_number');

            //学生Email
            $table->string('student_email')->nullable();

            //三种状态 未提交 已提交 已分配
            $table->enum('state', ['uncommitted', 'committed', 'assigned'])->default('uncommitted')->index();

            //用户是否删除了
            $table->boolean('user_deleted')->default(false)->index();

            $table->json('requirement_contrast');
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
