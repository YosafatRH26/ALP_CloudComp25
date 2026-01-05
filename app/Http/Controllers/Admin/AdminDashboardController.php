<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CvAnalysis;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedMode = $request->analysis_mode;
        $selectedDivision = $request->division;

        // Query Utama
        $query = CvAnalysis::with(['cvSubmission.user'])
            ->whereHas('cvSubmission', function ($q) use ($selectedMode) {
                // 1. WAJIB: Hanya ambil CV yang sudah di-Push ke Admin
                $q->where('is_submitted_to_admin', true);

                // 2. Filter Mode (Professional/Kepanitiaan) jika dipilih
                if ($selectedMode) {
                    $q->where('analysis_mode', $selectedMode);
                }
            });

        // 🔥 FILTER DIVISION DARI JSON
        if ($selectedDivision) {
            $query->where(function ($q) use ($selectedDivision) {
                $q->whereRaw(
                    "LOWER(division_recommendations_json) LIKE ?",
                    ['%' . strtolower($selectedDivision) . '%']
                );
            });
        }

        // Eksekusi Query dengan Sorting
        $analysis = $query
            ->orderByDesc('resume_score') // Prioritas 1: Skor tertinggi
            ->orderByDesc('id')           // Prioritas 2: Data terbaru (jika skor sama)
            ->get();

        return view('admin.dashboard', compact(
            'analysis',
            'selectedMode',
            'selectedDivision'
        ));
    }
}