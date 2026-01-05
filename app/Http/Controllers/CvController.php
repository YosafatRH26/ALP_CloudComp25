<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\CvAnalysis;
use App\Models\CvSubmission;
use App\Services\CvAnalysisService; // Load Service AI

use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser as PdfParser;

class CvController extends Controller
{
    protected CvAnalysisService $cvAnalysisService;

    // Inject Service AI ke Controller
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
     * STORE & ANALYZE CV (REAL AI)
     * ========================= */
    public function store(Request $request)
{
    // TAMBAHKAN INI DI BARIS PERTAMA FUNCTION STORE
    // Agar proses tidak mati (timeout) saat menunggu AI (maks 2 menit)
    set_time_limit(120); 

    // 1. Validasi Input
    $request->validate([
        'cv' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
        'language' => 'required|in:id,en',
        'analysis_type' => 'required|in:kepanitiaan,professional',
    ]);

        $file = $request->file('cv');

        // 2. Simpan File Fisik
        $storedPath = $file->storeAs(
            'cvs',
            Str::uuid() . '.' . $file->getClientOriginalExtension(),
            'public'
        );

        // 3. Ekstrak Teks dari PDF (PENTING: AI butuh teks, bukan file mentah)
        $cvText = $this->extractTextFromUploadedFile($file);

        if (!$cvText || strlen(trim($cvText)) < 50) {
            return back()->with('error', 'Gagal membaca teks CV. Pastikan PDF Anda berisi teks (bukan hasil scan gambar).')->withInput();
        }

        // 4. Tentukan Mode Analisis
        $analysisMode = $request->analysis_type === 'kepanitiaan' ? 'committee' : 'professional';
        $language = $request->language;

        // 5. Simpan Data Submission ke Database
        $submission = CvSubmission::create([
            'user_id' => Auth::id(),
            'stored_path' => $storedPath,
            'original_filename' => $file->getClientOriginalName(),
            'input_mode' => 'file',
            'language' => $language,
            'analysis_mode' => $analysisMode,
        ]);

        // 6. PANGGIL AI SERVICE (REAL ANALYSIS)
        try {
            // Ini akan mengirim teks ke Groq/Gemini via Service
            $aiResult = $this->cvAnalysisService->analyze($cvText, $analysisMode, $language);

        } catch (\Exception $e) {
            // Jika AI Gagal, Hapus submission biar bersih dan minta user coba lagi
            $submission->delete();
            Log::error("AI Analysis Error: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghubungi AI: ' . $e->getMessage());
        }

        // Cek jika hasil kosong
        if (!$aiResult) {
            $submission->delete();
            return back()->with('error', 'AI tidak memberikan respon. Silakan coba lagi.');
        }

        // 7. Simpan Hasil AI ke Tabel Analysis
        $analysis = $submission->analysis()->create([
            'resume_score' => (int) ($aiResult['resume_score'] ?? 0),
            'ats_score' => (int) ($aiResult['ats_score'] ?? 0),
            
            // Simpan JSON (Laravel otomatis encode karena di model sudah di-cast array)
            'main_skills_json' => $aiResult['main_skills'] ?? [],
            'division_recommendations_json' => $aiResult['division_recommendations'] ?? [],
            'readiness_scores_json' => $aiResult['readiness_scores'] ?? [],
            'skill_gap_json' => $aiResult['skill_gaps'] ?? [],
            
            'feedback_text' => $aiResult['feedback'] ?? 'Analisis selesai.',
            
            // Simpan raw response buat jaga-jaga (termasuk strengths, weaknesses, grammar, dll)
            'raw_ai_response' => $aiResult, 
        ]);

        // 8. Redirect ke Halaman Result
        return redirect()
            ->route('cv.result', $analysis->id)
            ->with('success', 'Analisis AI Berhasil Selesai!');
    }

    /* =========================
     * RESULT PAGE
     * ========================= */
    public function result(CvAnalysis $analysis)
    {
        $analysis->load('cvSubmission');

        if (!$analysis->cvSubmission) {
            abort(404, 'Data submission tidak ditemukan.');
        }

        // Security Check: Hanya pemilik CV atau Admin yang boleh lihat
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

        return view('cv.history', compact('histories'));
    }

    /* =========================
     * DELETE HISTORY
     * ========================= */
    public function destroyHistory($id)
    {
        $analysis = CvAnalysis::with('cvSubmission')->findOrFail($id);

        if (!$analysis->cvSubmission || (Auth::user()->role !== 'admin' && $analysis->cvSubmission->user_id !== Auth::id())) {
            abort(403);
        }

        if (Storage::disk('public')->exists($analysis->cvSubmission->stored_path)) {
            Storage::disk('public')->delete($analysis->cvSubmission->stored_path);
        }

        $analysis->cvSubmission->delete(); 
        // Analysis terhapus otomatis via cascade database atau biarkan sisa garbage collector
        
        return redirect()->route('cv.history')->with('success', 'Riwayat berhasil dihapus.');
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
     * TEXT EXTRACTOR
     * ========================= */
    protected function extractTextFromUploadedFile(UploadedFile $file): ?string
    {
        try {
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension === 'txt') {
                return trim(file_get_contents($file->getRealPath()));
            }

            if ($extension === 'pdf') {
                // Menggunakan Smalot PDF Parser
                $parser = new PdfParser();
                $pdf = $parser->parseFile($file->getRealPath());
                return trim($pdf->getText());
            }

            return null;
        } catch (\Throwable $e) {
            Log::error('Text extraction failed: ' . $e->getMessage());
            return null;
        }
    }
}