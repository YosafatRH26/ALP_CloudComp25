<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\CvAnalysis;
use App\Models\CvSubmission;
use App\Services\CvAnalysisService;

use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser as PdfParser;

class CvController extends Controller
{
    protected CvAnalysisService $cvAnalysisService;

    public function __construct(CvAnalysisService $cvAnalysisService)
    {
        $this->cvAnalysisService = $cvAnalysisService;
    }

    /* =========================
     * FORM UPLOAD
     * ========================= */
    public function create()
    {
        return view('cv.upload');
    }

    /* =========================
     * STORE & ANALYZE CV
     * ========================= */
    public function store(Request $request)
    {
        $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
            'language' => 'required|in:id,en',
            'analysis_type' => 'required|in:kepanitiaan,professional',
        ]);

        $file = $request->file('cv');

        $storedPath = $file->storeAs(
            'cvs',
            Str::uuid() . '.' . $file->getClientOriginalExtension(),
            'public'
        );

        $cvText = $this->extractTextFromUploadedFile($file);

        if (!$cvText) {
            return back()->withErrors([
                'cv' => 'Gagal membaca isi CV.',
            ])->withInput();
        }

        $analysisMode = $request->analysis_type === 'kepanitiaan'
            ? 'committee'
            : 'professional';

        $submission = CvSubmission::create([
            'user_id' => Auth::id(),
            'stored_path' => $storedPath,
            'original_filename' => $file->getClientOriginalName(),
            'input_mode' => 'file',
            'language' => $request->language,
            'analysis_mode' => $analysisMode,
        ]);

        try {
            $result = $this->cvAnalysisService->analyze(
                $cvText,
                $analysisMode,
                $request->language
            );
        } catch (\Throwable $e) {
            Log::error('CV Analysis failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors([
                'ai' => 'Gagal menganalisis CV. Silakan coba lagi.',
            ])->withInput();
        }

        if (!is_array($result) || !isset($result['resume_score'])) {
            Log::error('Invalid AI response', ['response' => $result]);
            return back()->withErrors([
                'ai' => 'Response AI tidak valid.',
            ])->withInput();
        }

        $analysis = $submission->analysis()->create([
            'resume_score' => (int) ($result['resume_score'] ?? 0),
            'ats_score' => (int) ($result['ats_score'] ?? 0),
            'main_skills_json' => $result['main_skills'] ?? [],
            'division_recommendations_json' => $result['division_recommendations'] ?? [],
            'readiness_scores_json' => $result['readiness_scores'] ?? [],
            'skill_gap_json' => $result['skill_gaps'] ?? [],
            'feedback_text' => $result['feedback'] ?? '',
            'raw_ai_response' => $result,
        ]);

        return redirect()
            ->route('cv.result', $analysis->id)
            ->with('success', 'CV berhasil dianalisis!');
    }

    /* =========================
     * RESULT PAGE
     * ========================= */
    public function result(CvAnalysis $analysis)
    {
        if (!$analysis->cvSubmission) {
            abort(404);
        }

        if (
            Auth::user()->role !== 'admin' &&
            $analysis->cvSubmission->user_id !== Auth::id()
        ) {
            abort(403);
        }

        return view('cv.result', compact('analysis'));
    }

    /* =========================
     * HISTORY PAGE
     * ========================= */
    public function history()
    {
        $histories = CvAnalysis::with('cvSubmission')
            ->whereHas('cvSubmission', fn ($q) =>
                $q->where('user_id', Auth::id())
            )
            ->latest()
            ->get();

        $alreadySubmitted = $histories
            ->filter(fn ($h) => optional($h->cvSubmission)->is_submitted_to_admin)
            ->isNotEmpty();

        return view('cv.history', compact('histories', 'alreadySubmitted'));
    }

    /* =========================
     * DELETE HISTORY
     * ========================= */
    public function destroyHistory(CvAnalysis $analysis)
    {
        if (
            !$analysis->cvSubmission ||
            (
                Auth::user()->role !== 'admin' &&
                $analysis->cvSubmission->user_id !== Auth::id()
            )
        ) {
            abort(403);
        }

        if (Storage::disk('public')->exists($analysis->cvSubmission->stored_path)) {
            Storage::disk('public')->delete($analysis->cvSubmission->stored_path);
        }

        $analysis->delete();
        $analysis->cvSubmission->delete();

        return redirect()
            ->route('cv.history')
            ->with('success', 'CV berhasil dihapus.');
    }

    public function submitToAdmin(CvSubmission $submission)
    {
        if ($submission->user_id !== Auth::id()) {
            abort(403);
        }

        if ($submission->is_submitted_to_admin) {
            return back()->with('info', 'CV ini sudah dikirim ke admin.');
        }

        $submission->update([
            'is_submitted_to_admin' => true,
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'CV berhasil dikirim ke admin.');
    }


    /* =========================
     * FILE PARSER
     * ========================= */
    protected function extractTextFromUploadedFile(UploadedFile $file): ?string
    {
        try {
            return match (strtolower($file->getClientOriginalExtension())) {
                'txt' => trim(file_get_contents($file->getRealPath())),
                'pdf' => trim((new PdfParser())->parseFile($file->getRealPath())->getText()),
                default => null,
            };
        } catch (\Throwable $e) {
            Log::error('CV extraction failed', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }
}