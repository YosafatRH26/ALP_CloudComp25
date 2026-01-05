<?php

namespace App\Services;

use App\Models\Division;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CvAnalysisService
{
    /**
     * @param string $cvText   Isi CV (plain text)
     * @param string $mode     committee | professional
     * @param string $language id | en
     */
    public function analyze(
        string $cvText,
        string $mode = 'committee',
        string $language = 'id'
    ): array {

        // =====================================================
        // 1. Ambil data divisi + skill dari database
        // =====================================================
        $divisions = Division::with('skills')->get()->map(function ($division) {
            return [
                'name'        => $division->name,
                'description' => $division->description,
                'skills'      => $division->skills->map(function ($skill) {
                    return [
                        'skill_name'       => $skill->skill_name,
                        'importance_level' => $skill->importance_level,
                    ];
                })->toArray(),
            ];
        })->toArray();

        // =====================================================
        // 2. System Prompt
        // =====================================================
        $systemPrompt = $this->buildSystemPrompt($mode, $language);

        // =====================================================
        // 3. API KEY
        // =====================================================
        $apiKey = env('GROQ_API_KEY');
        if (!$apiKey) {
            throw new RuntimeException('GROQ_API_KEY belum diset di .env');
        }

        // =====================================================
        // 4. User Content
        // =====================================================
        $userContent =
            "You are given:\n\n" .
            "1) Division and skill data (JSON):\n" .
            json_encode($divisions, JSON_PRETTY_PRINT) .
            "\n\n2) Raw CV text:\n" .
            $cvText .
            "\n\nAnalyze STRICTLY following the system instructions and JSON schema. " .
            "Return ONLY valid JSON.";

        $body = [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userContent],
            ],
            'response_format' => ['type' => 'json_object'],
            'temperature' => 0.2,
            'max_completion_tokens' => 1200,
        ];

        // =====================================================
        // 5. Call Groq API
        // =====================================================
        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey,
        ])
            ->timeout(60)
            ->post('https://api.groq.com/openai/v1/chat/completions', $body);

        if ($response->failed()) {
            throw new RuntimeException('Groq API Error: ' . $response->body());
        }

        $content = $response->json()['choices'][0]['message']['content'] ?? null;

        if (!$content) {
            throw new RuntimeException('AI response empty');
        }

        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(
                'Invalid JSON from AI: ' . json_last_error_msg() . "\nRaw: " . $content
            );
        }

        return $decoded;
    }

    // =====================================================
    // PROMPT ROUTER
    // =====================================================
    private function buildSystemPrompt(string $mode, string $language): string
    {
        return $mode === 'professional'
            ? $this->professionalPrompt($language)
            : $this->committeePrompt($language);
    }

    // =====================================================
    // KEPANITIAAN PROMPT (FINAL – KOMPLEKS)
    // =====================================================
    private function committeePrompt(string $language): string
    {
        if ($language === 'id') {
            return <<<PROMPT
Anda adalah AI evaluator tingkat senior yang mensimulasikan panel seleksi kepanitiaan kampus dan organisasi mahasiswa.

Gunakan BAHASA INDONESIA SEPENUHNYA. JANGAN mencampur bahasa.

PRINSIP PENILAIAN:
- Nilai hanya berdasarkan bukti nyata di CV.
- Penalti klaim soft-skill tanpa contoh konkret.
- Bedakan setiap divisi secara tegas.
- Jangan menaikkan skor jika pengalaman tidak relevan.

RUANG LINGKUP:
- Kualitas struktur dan bahasa CV
- Pengalaman organisasi dan kepanitiaan
- Kecocokan kandidat terhadap SETIAP divisi
- Kesiapan realistis sebagai panitia aktif

DEFINISI LEVEL:
- Beginner: pengalaman minim / akademik
- Intermediate: aktif dan berkontribusi
- Strong: leadership atau impact nyata

WAJIB OUTPUT JSON PERSIS:

{
  "resume_score": number,
  "ats_score": number,
  "experience_level": "Beginner" | "Intermediate" | "Strong",
  "main_skills": [string],
  "achievements": [string],
  "division_recommendations": [
    { "division_name": string, "reason": string, "keyword_match": number }
  ],
  "readiness_scores": [
    { "division_name": string, "score": number }
  ],
  "skill_gaps": [
    { "division_name": string, "missing_skills": [string] }
  ],
  "strengths": [string],
  "weaknesses": [string],
  "missing_sections": [string],
  "grammar_issues": {
    "critical": number,
    "minor": number,
    "spelling": number
  },
  "suggested_improvements": [string],
  "feedback": string
}

ATURAN:
- Semua key WAJIB ada
- Semua skor 0–100
- Tanpa markdown
- JSON valid saja
PROMPT;
        }

        return <<<PROMPT
You are a senior AI evaluator for campus committee selection.

USE FULL ENGLISH ONLY.

Evaluate strictly based on evidence in the CV.
Return ONLY valid JSON with the required structure.
PROMPT;
    }

    // =====================================================
    // PROFESSIONAL PROMPT (FINAL – INDUSTRIAL GRADE)
    // =====================================================
    private function professionalPrompt(string $language): string
    {
        if ($language === 'id') {
            return <<<PROMPT
Anda adalah AI evaluator profesional tingkat senior untuk seleksi kerja dan magang (entry-level hingga junior).

Gunakan BAHASA INDONESIA SEPENUHNYA. JANGAN mencampur bahasa.

ANDA MENSIMULASIKAN:
- HR Recruiter
- ATS Screening System
- Hiring Manager tahap awal

PRINSIP:
- Objektif dan berbasis bukti
- Penalti buzzword tanpa konteks
- Bedakan kandidat "cukup" vs "siap kerja"

FOKUS:
- Struktur dan profesionalisme CV
- Pengalaman kerja, magang, proyek
- Dampak pencapaian (measurable)
- Kesesuaian skill terhadap divisi
- Kesiapan industri nyata

DEFINISI LEVEL:
- Beginner: akademik dominan
- Intermediate: pengalaman praktis
- Strong: pengalaman mandiri & konsisten

WAJIB OUTPUT JSON PERSIS:

{
  "resume_score": number,
  "ats_score": number,
  "experience_level": "Beginner" | "Intermediate" | "Strong",
  "main_skills": [string],
  "achievements": [string],
  "division_recommendations": [
    { "division_name": string, "reason": string, "keyword_match": number }
  ],
  "readiness_scores": [
    { "division_name": string, "score": number }
  ],
  "skill_gaps": [
    { "division_name": string, "missing_skills": [string] }
  ],
  "strengths": [string],
  "weaknesses": [string],
  "missing_sections": [string],
  "grammar_issues": {
    "critical": number,
    "minor": number,
    "spelling": number
  },
  "suggested_improvements": [string],
  "feedback": string
}

ATURAN:
- Semua key WAJIB ada
- Semua skor 0–100
- Nada profesional, bukan motivasional
- Tanpa markdown
- JSON valid saja
PROMPT;
        }

        return <<<PROMPT
You are a senior professional AI evaluator for job and internship CV screening.

USE FULL ENGLISH ONLY.
Return ONLY valid JSON with the required structure.
PROMPT;
    }
}
