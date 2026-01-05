<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class CvAnalysisService
{
    /**
     * @param string $cvText   Isi CV (plain text)
     * @param string $mode     committee | professional
     * @param string $language id | en
     */
    public function analyze(string $cvText, string $mode = 'committee', string $language = 'id'): array
    {
        // 1. API KEY Check
        $apiKey = env('GROQ_API_KEY');
        if (!$apiKey) {
            throw new RuntimeException('GROQ_API_KEY belum diset di .env');
        }

        // 2. Build Prompt
        $systemPrompt = $this->buildSystemPrompt($mode, $language);
        
        // 3. User Content (Context)
        $userContent = "Analyze the following CV text strictly according to the system prompt.\n\n" .
                       "CV TEXT:\n" . substr($cvText, 0, 15000); // Limit karakter agar tidak over token

        // 4. Payload Groq
        $body = [
            'model' => 'llama-3.3-70b-versatile', // Model paling balanced (Cepat & Pintar)
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userContent],
            ],
            'response_format' => ['type' => 'json_object'], // Wajib agar outputnya JSON bersih
            'temperature' => 0.1, // Rendah agar konsisten
            'max_completion_tokens' => 2048,
        ];

        // 5. Call API
        try {
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey,
            ])
            ->timeout(60) // Timeout agak panjang buat jaga-jaga
            ->post('https://api.groq.com/openai/v1/chat/completions', $body);

            if ($response->failed()) {
                Log::error('Groq API Error: ' . $response->body());
                throw new RuntimeException('Gagal menghubungi AI Server.');
            }

            $content = $response->json()['choices'][0]['message']['content'] ?? null;

            if (!$content) {
                throw new RuntimeException('AI memberikan respon kosong.');
            }

            // 6. Decode & Validate JSON
            $decoded = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // Kadang AI ngasih text sebelum JSON, kita coba bersihkan
                $cleanJson = preg_replace('/^```json\s*|\s*```$/', '', trim($content));
                $decoded = json_decode($cleanJson, true);
                
                if (!$decoded) {
                    throw new RuntimeException('Format JSON dari AI tidak valid.');
                }
            }

            return $decoded;

        } catch (\Exception $e) {
            Log::error('CV Analysis Exception: ' . $e->getMessage());
            // Return null atau throw ulang tergantung preferensi controller
            throw $e; 
        }
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
    // KEPANITIAAN PROMPT
    // =====================================================
    private function committeePrompt(string $language): string
    {
        // Instruksi bahasa
        $langInstruction = $language === 'id' 
            ? "Gunakan BAHASA INDONESIA yang baku dan profesional untuk semua nilai output (feedback, reason, dll)." 
            : "Use English for all output values.";

        return <<<PROMPT
Role: Senior Recruiter untuk Organisasi Mahasiswa & Kepanitiaan Kampus.
Language: $langInstruction

Tugas:
Analisis teks CV yang diberikan. Tentukan apakah kandidat cocok untuk masuk ke kepanitiaan.
Identifikasi skill, pengalaman organisasi, dan soft-skill (leadership, teamwork, communication).

Karena tidak ada daftar divisi spesifik, Anda WAJIB MEREKOMENDASIKAN 2-3 Divisi yang paling umum ada di kepanitiaan (contoh: Acara/Event, Humas/Public Relation, Perlengkapan/Logistics, Sponsorship, Desain/PDD) yang PALING COCOK dengan skill kandidat.

OUTPUT FORMAT (JSON ONLY):
{
  "resume_score": (integer 0-100),
  "ats_score": (integer 0-100),
  "experience_level": "Beginner" | "Intermediate" | "Strong",
  "main_skills": ["Skill 1", "Skill 2", "Skill 3", "Skill 4", "Skill 5"],
  "achievements": ["Achievement 1", "Achievement 2"],
  "division_recommendations": [
    { "division_name": "Nama Divisi (misal: Acara)", "keyword_match": (int 0-100), "reason": "Alasan singkat padat" },
    { "division_name": "Nama Divisi Alternatif", "keyword_match": (int 0-100), "reason": "Alasan singkat padat" }
  ],
  "readiness_scores": [
    { "division_name": "Sama dengan rekomendasi 1", "score": (int 0-100) },
    { "division_name": "Sama dengan rekomendasi 2", "score": (int 0-100) }
  ],
  "skill_gaps": {
    "Sama dengan rekomendasi 1": { "missing_skills": ["Skill A", "Skill B"] },
    "Sama dengan rekomendasi 2": { "missing_skills": ["Skill C"] }
  },
  "strengths": ["Poin kuat 1", "Poin kuat 2"],
  "weaknesses": ["Kelemahan 1", "Kelemahan 2"],
  "missing_sections": ["Bagian yang kurang (misal: Kontak, Portofolio)"],
  "grammar_issues": {
    "critical": (int),
    "minor": (int),
    "spelling": (int)
  },
  "suggested_improvements": ["Saran 1", "Saran 2"],
  "feedback": "Paragraf kesimpulan singkat (maks 3 kalimat) yang konstruktif."
}
PROMPT;
    }

    // =====================================================
    // PROFESSIONAL PROMPT
    // =====================================================
    private function professionalPrompt(string $language): string
    {
        $langInstruction = $language === 'id' 
            ? "Gunakan BAHASA INDONESIA yang baku dan profesional." 
            : "Use English for all output values.";

        return <<<PROMPT
Role: Senior HR Specialist & ATS Scanner.
Language: $langInstruction

Tugas:
Analisis CV untuk keperluan lamaran kerja profesional / magang.
Fokus pada Hard Skills, Work Experience, Project Impact, dan Education.

Tentukan 2-3 Posisi Pekerjaan (Job Roles) yang paling cocok berdasarkan isi CV (misal: Backend Developer, Digital Marketer, Admin, dll).

OUTPUT FORMAT (JSON ONLY):
{
  "resume_score": (integer 0-100 based on industry standard),
  "ats_score": (integer 0-100 based on keyword readability),
  "experience_level": "Beginner" | "Intermediate" | "Strong",
  "main_skills": ["Skill 1", "Skill 2", "Skill 3", "Skill 4", "Skill 5"],
  "achievements": ["Achievement 1", "Achievement 2"],
  "division_recommendations": [
    { "division_name": "Job Role 1", "keyword_match": (int 0-100), "reason": "Reasoning" },
    { "division_name": "Job Role 2", "keyword_match": (int 0-100), "reason": "Reasoning" }
  ],
  "readiness_scores": [
    { "division_name": "Job Role 1", "score": (int 0-100) },
    { "division_name": "Job Role 2", "score": (int 0-100) }
  ],
  "skill_gaps": {
    "Job Role 1": { "missing_skills": ["Missing Tool/Skill"] },
    "Job Role 2": { "missing_skills": ["Missing Tool/Skill"] }
  },
  "strengths": ["Strength 1", "Strength 2"],
  "weaknesses": ["Weakness 1", "Weakness 2"],
  "missing_sections": ["Missing Section Name"],
  "grammar_issues": {
    "critical": (int),
    "minor": (int),
    "spelling": (int)
  },
  "suggested_improvements": ["Improvement 1", "Improvement 2"],
  "feedback": "Professional summary feedback (max 3 sentences)."
}
PROMPT;
    }
}