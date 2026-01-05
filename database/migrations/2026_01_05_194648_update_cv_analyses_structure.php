<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cv_analyses', function (Blueprint $table) {
            // 1. Hapus kolom lama yang tidak terpakai (jika ada)
            // Kita pakai if (Schema::hasColumn...) biar aman kalau kolomnya sudah hilang
            if (Schema::hasColumn('cv_analyses', 'score')) {
                $table->dropColumn(['score', 'analysis_result', 'keywords_found']);
            }

            // 2. Tambahkan kolom baru sesuai Controller
            $table->integer('resume_score')->default(0)->after('cv_submission_id');
            $table->integer('ats_score')->default(0)->after('resume_score');
            
            $table->json('main_skills_json')->nullable()->after('ats_score');
            $table->json('division_recommendations_json')->nullable()->after('main_skills_json');
            $table->json('readiness_scores_json')->nullable()->after('division_recommendations_json');
            $table->json('skill_gap_json')->nullable()->after('readiness_scores_json');
            
            $table->text('feedback_text')->nullable()->after('skill_gap_json');
            $table->json('raw_ai_response')->nullable()->after('feedback_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cv_analyses', function (Blueprint $table) {
            // Kembalikan ke struktur lama jika di-rollback
            $table->dropColumn([
                'resume_score', 
                'ats_score', 
                'main_skills_json', 
                'division_recommendations_json', 
                'readiness_scores_json', 
                'skill_gap_json', 
                'feedback_text', 
                'raw_ai_response'
            ]);
            
            $table->integer('score')->default(0);
            $table->text('analysis_result')->nullable();
            $table->json('keywords_found')->nullable();
        });
    }
};