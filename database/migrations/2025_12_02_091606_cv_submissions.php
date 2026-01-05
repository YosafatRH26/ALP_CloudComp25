<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cv_submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->string('original_filename');
            $table->string('stored_path')->nullable();
            $table->longText('extracted_text')->nullable();

            $table->enum('input_mode', ['file', 'manual'])->default('file');
            $table->enum('analysis_mode', ['professional', 'committee'])->default('professional');
            $table->enum('language', ['id', 'en'])->default('id');
            $table->boolean('is_submitted_to_admin')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_submissions');
    }
};
