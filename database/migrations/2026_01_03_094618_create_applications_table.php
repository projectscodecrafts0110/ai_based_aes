<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // links to logged-in user
            $table->string('full_name');
            $table->string('email');
            $table->string('contact_number');
            $table->text('address');
            $table->string('job_applying');
            $table->text('job_qualifications');
            $table->string('higher_education');
            $table->string('major');

            // File uploads stored as paths
            $table->string('application_letter');
            $table->string('resume');
            $table->string('pds');
            $table->string('otr');
            $table->json('certificates')->nullable(); // multiple certificates

            $table->string('ratings')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
