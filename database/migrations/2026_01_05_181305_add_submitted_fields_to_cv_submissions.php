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
        Schema::table('cv_submissions', function (Blueprint $table) {
            // Kita cek dulu biar tidak error kalau kolomnya sudah ada sebagian
            if (!Schema::hasColumn('cv_submissions', 'is_submitted_to_admin')) {
                $table->boolean('is_submitted_to_admin')->default(false)->after('analysis_mode');
            }
            
            if (!Schema::hasColumn('cv_submissions', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('is_submitted_to_admin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cv_submissions', function (Blueprint $table) {
            $table->dropColumn(['is_submitted_to_admin', 'submitted_at']);
        });
    }
};