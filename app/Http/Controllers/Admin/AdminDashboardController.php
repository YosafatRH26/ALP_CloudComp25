<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CvAnalysis;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    // Halaman Dashboard (Filter & View Only)
    public function index(Request $request)
    {
        $selectedMode = $request->analysis_mode;
        $selectedDivision = $request->division;

        $query = CvAnalysis::with(['cvSubmission.user'])
            ->whereHas('cvSubmission', function ($q) use ($selectedMode) {
                // 1. WAJIB: Hanya ambil CV yang sudah di-Push ke Admin
                $q->where('is_submitted_to_admin', true);

                // 2. Filter Mode (Professional/Kepanitiaan)
                if ($selectedMode) {
                    $q->where('analysis_mode', $selectedMode);
                }
            });

        // 🔥 FILTER DIVISION DARI JSON
        if ($selectedDivision) {
            $query->where(function ($q) use ($selectedDivision) {
                // Mencari string di dalam kolom JSON (bukan cara terbaik tapi cukup untuk MVP)
                $q->whereRaw(
                    "LOWER(division_recommendations_json) LIKE ?",
                    ['%' . strtolower($selectedDivision) . '%']
                );
            });
        }

        // Eksekusi Query dengan Sorting
        $analysis = $query
            ->orderByDesc('resume_score') // Prioritas 1: Skor tertinggi
            ->orderByDesc('id')           // Prioritas 2: Data terbaru
            ->get();

        return view('admin.dashboard', compact(
            'analysis',
            'selectedMode',
            'selectedDivision'
        ));
    }

    // Halaman Pemilihan untuk Compare (BARU)
    public function compareSelection()
    {
        $analysis = CvAnalysis::with(['cvSubmission.user'])
            ->whereHas('cvSubmission', function ($q) {
                $q->where('is_submitted_to_admin', true);
            })
            ->orderByDesc('resume_score')
            ->get();

        // Menggunakan view: resources/views/admin/compare-selection.blade.php
        return view('admin.compare-selection', compact('analysis'));
    }

    // Proses Logika Compare
    public function compare(Request $request)
    {
        // 1. Validasi: Pastikan user memilih minimal 2 CV
        $request->validate([
            'cv_ids' => 'required|array|min:2',
            'cv_ids.*' => 'exists:cv_analyses,id' // Pastikan ID valid
        ], [
            'cv_ids.required' => 'Silakan pilih minimal 2 kandidat.',
            'cv_ids.min' => 'Pilih minimal 2 kandidat untuk dibandingkan.'
        ]);

        // 2. Ambil data kandidat yang dipilih
        $candidates = CvAnalysis::with('cvSubmission.user')
                        ->whereIn('id', $request->cv_ids)
                        ->orderByDesc('resume_score') // Urutkan biar yang skor tinggi di kiri
                        ->get();

        // 3. Tampilkan View Compare Result
        // Menggunakan view: resources/views/admin/compare-result.blade.php
        return view('admin.compare-result', compact('candidates'));
    }
}