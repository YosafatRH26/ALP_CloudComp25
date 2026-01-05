<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cv_analyses', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel submissions
            $table->foreignId('cv_submission_id')->constrained('cv_submissions')->onDelete('cascade');
            
            // Kolom data analisis
            $table->text('analysis_result')->nullable(); // Menyimpan teks hasil AI
            $table->integer('score')->default(0);        // Menyimpan skor (0-100)
            $table->json('keywords_found')->nullable();  // Menyimpan keyword (opsional)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_analyses');
    }
};