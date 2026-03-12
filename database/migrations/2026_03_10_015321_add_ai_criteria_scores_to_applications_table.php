<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {

            $table->integer('ai_education_score')->nullable();
            $table->integer('ai_experience_score')->nullable();
            $table->integer('ai_training_score')->nullable();
            $table->integer('ai_potential_score')->nullable();
            $table->integer('ai_accomplishments_score')->nullable();
            $table->integer('ai_psychosocial_score')->nullable();

            $table->integer('ai_total_score')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {

            $table->dropColumn([
                'ai_education_score',
                'ai_experience_score',
                'ai_training_score',
                'ai_potential_score',
                'ai_accomplishments_score',
                'ai_psychosocial_score',
                'ai_total_score'
            ]);
        });
    }
};
