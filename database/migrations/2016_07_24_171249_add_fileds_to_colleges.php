<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFiledsToColleges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->boolean('hot')->default(false)->index();
            $table->boolean('recommendatory')->default(false)->index();

            //College Type
            $table->enum('type', ['public', 'private'])->index();

            //Group of Eight
            //https://en.wikipedia.org/wiki/Group_of_Eight_(Australian_universities)
            $table->boolean('go8')->default(false)->index();

            //small icon path
            $table->string('icon_path')->nullable();;
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
            $table->dropColumn('hot');
            $table->dropColumn('recommendatory');
            $table->dropColumn('type');
            $table->dropColumn('go8');
            $table->dropColumn('icon_path');
        });
    }
}
