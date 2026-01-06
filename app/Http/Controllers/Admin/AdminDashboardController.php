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

        $query = CvAnalysis::with(['cvSubmission.user'])
            ->whereHas('cvSubmission', function ($q) use ($selectedMode) {
                $q->where('is_submitted_to_admin', true);
                if ($selectedMode) {
                    $q->where('analysis_mode', $selectedMode);
                }
            });

        if ($selectedDivision) {
            $query->where(function ($q) use ($selectedDivision) {
                $q->whereRaw(
                    "LOWER(division_recommendations_json) LIKE ?",
                    ['%' . strtolower($selectedDivision) . '%']
                );
            });
        }

        $analysis = $query->orderByDesc('resume_score')->orderByDesc('id')->get();

        return view('admin.dashboard', compact('analysis', 'selectedMode', 'selectedDivision'));
    }

    public function compareSelection()
    {
        $analysis = CvAnalysis::with(['cvSubmission.user'])
            ->whereHas('cvSubmission', function ($q) {
                $q->where('is_submitted_to_admin', true);
            })
            ->orderByDesc('resume_score')
            ->get();

        return view('admin.compare-selection', ['candidates' => $analysis]);
    }

    public function compare(Request $request)
    {
        // Validasi TEPAT 2 Kandidat
        $request->validate([
            'cv_ids' => 'required|array|min:2|max:2', 
            'cv_ids.*' => 'exists:cv_analyses,id' 
        ], [
            'cv_ids.required' => 'Silakan pilih 2 kandidat.',
            'cv_ids.min' => 'Pilih 2 kandidat untuk dibandingkan.',
            'cv_ids.max' => 'Anda hanya bisa membandingkan maksimal 2 kandidat.'
        ]);

        $candidates = CvAnalysis::with('cvSubmission.user')
                        ->whereIn('id', $request->cv_ids)
                        ->get();

        return view('admin.compare-result', compact('candidates'));
    }
}