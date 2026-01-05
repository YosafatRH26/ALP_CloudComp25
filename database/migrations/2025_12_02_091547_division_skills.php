<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('division_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->string('skill_name');
            $table->unsignedTinyInteger('importance_level')->default(1); // range: 1–5
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('division_skills');
    }
};