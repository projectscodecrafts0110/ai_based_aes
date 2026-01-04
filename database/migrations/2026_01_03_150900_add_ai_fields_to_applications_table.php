<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->unsignedTinyInteger('ai_score')->nullable()->after('status');
            $table->string('ai_recommendation')->nullable()->after('ai_score');
            $table->text('ai_summary')->nullable()->after('ai_recommendation');
            $table->timestamp('ai_evaluated_at')->nullable()->after('ai_summary');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'ai_score',
                'ai_recommendation',
                'ai_summary',
                'ai_evaluated_at',
            ]);
        });
    }
};
