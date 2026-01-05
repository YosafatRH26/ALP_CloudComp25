<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cv_analysis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cv_submission_id')
                  ->constrained('cv_submissions')
                  ->cascadeOnDelete();

            $table->float('resume_score')->nullable();
            $table->float('ats_score')->nullable();

            $table->json('main_skills_json')->nullable();
            $table->json('division_recommendations_json')->nullable();
            $table->json('skill_gap_json')->nullable();
            $table->json('readiness_scores_json')->nullable();
            $table->json('raw_ai_response')->nullable();

            $table->text('feedback_text')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_analysis');
    }
};
