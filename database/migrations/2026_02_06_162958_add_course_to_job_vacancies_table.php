<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->string('course', 100)->after('title');
        });
    }

    public function down()
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->dropColumn('course');
        });
    }
};
