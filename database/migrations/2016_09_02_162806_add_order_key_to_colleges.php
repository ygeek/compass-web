<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderKeyToColleges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->double('average_enrollment')->default(0);
            $table->double('international_ratio')->default(0);
            $table->integer('read_count')->default(0);
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
            $table->dropColumn('average_enrollment');
            $table->dropColumn('international_ratio');
            $table->dropColumn('read_count');
        });
    }
}
