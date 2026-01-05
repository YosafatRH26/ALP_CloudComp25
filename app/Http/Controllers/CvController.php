<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\CvAnalysis;
use App\Models\CvSubmission;
// use App\Services\CvAnalysisService; // Kita comment dulu biar tidak error

use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser as PdfParser;

class CvController extends Controller
{
    // protected CvAnalysisService $cvAnalysisService;

    // public function __construct(CvAnalysisService $cvAnalysisService)
    // {
    //     $this->cvAnalysisService = $cvAnalysisService;
    // }

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
        // 1. Validasi
        $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
            'language' => 'required|in:id,en',
            'analysis_type' => 'required|in:kepanitiaan,professional',
        ]);

        $file = $request->file('cv');

        // 2. Simpan File
        $storedPath = $file->storeAs(
            'cvs',
            Str::uuid() . '.' . $file->getClientOriginalExtension(),
            'public'
        );

        // 3. Extract Text (Opsional, kita try-catch biar gak error)
        $cvText = "";
        try {
            $cvText = $this->extractTextFromUploadedFile($file);
        } catch (\Throwable $e) {
            Log::warning('Gagal extract text: ' . $e->getMessage());
            $cvText = "Text extraction failed.";
        }

        // 4. Tentukan Mode
        $analysisMode = $request->analysis_type === 'kepanitiaan' ? 'committee' : 'professional';

        // 5. Simpan ke Tabel Submission
        $submission = CvSubmission::create([
            'user_id' => Auth::id(),
            'stored_path' => $storedPath,
            'original_filename' => $file->getClientOriginalName(),
            'input_mode' => 'file',
            'language' => $request->language,
            'analysis_mode' => $analysisMode,
        ]);

        // 6. --- MOCKUP ANALYSIS (Supaya Result Pasti Muncul) ---
        // Kita bypass dulu CvAnalysisService untuk memastikan data masuk DB
        $result = [
            'resume_score' => 85,
            'ats_score' => 80,
            'main_skills' => ['Communication', 'Project Management', 'Laravel', 'Teamwork'],
            'division_recommendations' => ['Project Manager', 'Backend Developer'],
            'readiness_scores' => ['Project Manager' => 90, 'Backend' => 75],
            'skill_gaps' => ['Public Speaking', 'Cloud Computing'],
            'feedback' => 'CV Anda sudah sangat baik secara struktur. Pengalaman organisasi cukup menonjol. (Ini adalah hasil analisis dummy untuk testing).',
        ];

        // 7. Simpan ke Tabel Analysis
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

        // 8. Redirect ke Result
        return redirect()
            ->route('cv.result', $analysis->id)
            ->with('success', 'CV berhasil dianalisis (Mode Testing)!');
    }

    /* =========================
     * RESULT PAGE
     * ========================= */
    public function result(CvAnalysis $analysis)
    {
        // Load relasi submission agar tidak error saat dipanggil di view
        $analysis->load('cvSubmission');

        if (!$analysis->cvSubmission) {
            abort(404, 'Data submission tidak ditemukan.');
        }

        // Cek hak akses (User sendiri atau Admin)
        if (Auth::user()->role !== 'admin' && $analysis->cvSubmission->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
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

        // Cek apakah ada CV yang sudah disubmit ke admin
        $alreadySubmitted = $histories
            ->filter(fn ($h) => optional($h->cvSubmission)->is_submitted_to_admin)
            ->isNotEmpty();

        return view('cv.history', compact('histories', 'alreadySubmitted'));
    }

    /* =========================
     * DELETE HISTORY
     * ========================= */
    public function destroyHistory($id)
    {
        // Kita cari manual berdasarkan ID agar bisa cek hak akses
        $analysis = CvAnalysis::with('cvSubmission')->findOrFail($id);

        if (
            !$analysis->cvSubmission ||
            (Auth::user()->role !== 'admin' && $analysis->cvSubmission->user_id !== Auth::id())
        ) {
            abort(403);
        }

        // Hapus file fisik
        if (Storage::disk('public')->exists($analysis->cvSubmission->stored_path)) {
            Storage::disk('public')->delete($analysis->cvSubmission->stored_path);
        }

        // Hapus record (Cascade delete biasanya handle ini, tapi manual lebih aman)
        $analysis->cvSubmission->delete(); 
        // Note: Analysis akan terhapus otomatis jika on delete cascade aktif di migration,
        // jika tidak, baris ini menghapus analysis:
        $analysis->delete();

        return redirect()
            ->route('cv.history')
            ->with('success', 'CV berhasil dihapus.');
    }

    /* =========================
     * SUBMIT TO ADMIN
     * ========================= */
    public function submitToAdmin($id)
    {
        $submission = CvSubmission::findOrFail($id);

        if ($submission->user_id !== Auth::id()) {
            abort(403);
        }

        if ($submission->is_submitted_to_admin) {
            return back()->with('info', 'CV ini sudah dikirim ke admin sebelumnya.');
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
            // Pastikan Anda sudah install: composer require smalot/pdfparser
            return match (strtolower($file->getClientOriginalExtension())) {
                'txt' => trim(file_get_contents($file->getRealPath())),
                'pdf' => trim((new PdfParser())->parseFile($file->getRealPath())->getText()),
                default => null,
            };
        } catch (\Throwable $e) {
            Log::error('CV extraction failed: ' . $e->getMessage());
            return null;
        }
    }
}