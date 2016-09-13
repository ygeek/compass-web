<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIndexInSpecialities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('specialities', function (Blueprint $table) {
            $table->dropUnique('name_degree_id_college_id_unique');
            $table->unique(['name', 'degree_id', 'college_id', 'category_id'],'name_degree_id_college_id_category_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('specialities', function (Blueprint $table) {
            $table->dropUnique('name_degree_id_college_id_category_id_unique');
            $table->unique(['name', 'degree_id', 'college_id'],'name_degree_id_college_id_unique');
        });
    }
}
