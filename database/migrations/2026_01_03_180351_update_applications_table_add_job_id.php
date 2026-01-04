<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('job_qualifications'); // remove old field
            $table->dropColumn('job_applying');
            $table->unsignedBigInteger('job_id')->after('id')->nullable();

            $table->foreign('job_id')
                ->references('id')
                ->on('job_vacancies')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('job_qualifications')->after('id')->nullable();
            $table->dropForeign(['job_id']);
            $table->dropColumn('job_id');
        });
    }
};
