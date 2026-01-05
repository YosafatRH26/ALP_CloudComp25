<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CvAnalysis;

class AdminCvController extends Controller
{
    public function __construct()
    {
        // Middleware bisa ditaruh di route atau di sini
        // $this->middleware(['auth', 'verified', 'role:admin']);
    }

    // Form pilih 2 CV untuk dibandingkan
    public function compareForm()
    {
        $analysis = CvAnalysis::with('cvSubmission.user')->get();
        return view('admin.compare', compact('analysis'));
    }

    // Hasil perbandingan (POST)
    public function compareResult(Request $request)
    {
        $request->validate([
            // PERBAIKAN: Mengubah 'cv_analysis' menjadi 'cv_analyses' (nama tabel default Laravel berbentuk jamak)
            'cv_a' => 'required|different:cv_b|exists:cv_analyses,id',
            'cv_b' => 'required|exists:cv_analyses,id',
        ]);

        // Ambil data CV
        $cvA = CvAnalysis::with('cvSubmission.user')->findOrFail($request->cv_a);
        $cvB = CvAnalysis::with('cvSubmission.user')->findOrFail($request->cv_b);

        // --- Decode raw AI response ---
        // PERBAIKAN: Menambahkan cek is_string agar aman jika Model sudah meng-cast ke array
        $cvA_raw = is_string($cvA->raw_ai_response) ? json_decode($cvA->raw_ai_response, true) : $cvA->raw_ai_response;
        $cvA_raw = $cvA_raw ?? [];

        $cvB_raw = is_string($cvB->raw_ai_response) ? json_decode($cvB->raw_ai_response, true) : $cvB->raw_ai_response;
        $cvB_raw = $cvB_raw ?? [];

        // --- CV A ---
        $cvA_strengths       = $cvA_raw['strengths'] ?? [];
        $cvA_weaknesses      = $cvA_raw['weaknesses'] ?? [];
        $cvA_atsScore        = $cvA_raw['ats_score'] ?? null;
        $cvA_experience      = $cvA_raw['experience_level'] ?? null;
        $cvA_achievements    = $cvA_raw['achievements'] ?? [];
        $cvA_suggestedImpr   = $cvA_raw['suggested_improvements'] ?? [];
        $cvA_missingSections = $cvA_raw['missing_sections'] ?? [];
        $cvA_grammarIssues   = $cvA_raw['grammar_issues'] ?? [];

        $cvA_grammarCritical = $cvA_grammarIssues['critical'] ?? 0;
        $cvA_grammarMinor    = $cvA_grammarIssues['minor'] ?? 0;
        $cvA_grammarSpelling = $cvA_grammarIssues['spelling'] ?? 0;

        // --- CV B ---
        $cvB_strengths       = $cvB_raw['strengths'] ?? [];
        $cvB_weaknesses      = $cvB_raw['weaknesses'] ?? [];
        $cvB_atsScore        = $cvB_raw['ats_score'] ?? null;
        $cvB_experience      = $cvB_raw['experience_level'] ?? null;
        $cvB_achievements    = $cvB_raw['achievements'] ?? [];
        $cvB_suggestedImpr   = $cvB_raw['suggested_improvements'] ?? [];
        $cvB_missingSections = $cvB_raw['missing_sections'] ?? [];
        $cvB_grammarIssues   = $cvB_raw['grammar_issues'] ?? [];

        $cvB_grammarCritical = $cvB_grammarIssues['critical'] ?? 0;
        $cvB_grammarMinor    = $cvB_grammarIssues['minor'] ?? 0;
        $cvB_grammarSpelling = $cvB_grammarIssues['spelling'] ?? 0;

        // --- Data tambahan dari kolom JSON ---
        $cvA_mainSkills = is_string($cvA->main_skills_json) ? json_decode($cvA->main_skills_json, true) : $cvA->main_skills_json;
        $cvB_mainSkills = is_string($cvB->main_skills_json) ? json_decode($cvB->main_skills_json, true) : $cvB->main_skills_json;

        $cvA_divRecs = is_string($cvA->division_recommendations_json) ? json_decode($cvA->division_recommendations_json, true) : $cvA->division_recommendations_json;
        $cvB_divRecs = is_string($cvB->division_recommendations_json) ? json_decode($cvB->division_recommendations_json, true) : $cvB->division_recommendations_json;

        $cvA_readiness = is_string($cvA->readiness_scores_json) ? json_decode($cvA->readiness_scores_json, true) : $cvA->readiness_scores_json;
        $cvB_readiness = is_string($cvB->readiness_scores_json) ? json_decode($cvB->readiness_scores_json, true) : $cvB->readiness_scores_json;

        $cvA_skillGaps = is_string($cvA->skill_gap_json) ? json_decode($cvA->skill_gap_json, true) : $cvA->skill_gap_json;
        $cvB_skillGaps = is_string($cvB->skill_gap_json) ? json_decode($cvB->skill_gap_json, true) : $cvB->skill_gap_json;

        return view('admin.compare-result', compact(
            'cvA','cvB',
            'cvA_strengths','cvA_weaknesses','cvA_atsScore','cvA_experience','cvA_achievements','cvA_suggestedImpr','cvA_missingSections','cvA_grammarCritical','cvA_grammarMinor','cvA_grammarSpelling',
            'cvB_strengths','cvB_weaknesses','cvB_atsScore','cvB_experience','cvB_achievements','cvB_suggestedImpr','cvB_missingSections','cvB_grammarCritical','cvB_grammarMinor','cvB_grammarSpelling',
            'cvA_mainSkills','cvB_mainSkills',
            'cvA_divRecs','cvB_divRecs',
            'cvA_readiness','cvB_readiness',
            'cvA_skillGaps','cvB_skillGaps'
        ));
    }
}