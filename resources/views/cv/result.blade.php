<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 relative overflow-hidden">
        
        {{-- Decorative Glow --}}
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-sky-500/10 blur-[150px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-emerald-500/5 blur-[150px] rounded-full pointer-events-none"></div>

        <div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- NOTIFICATION ALERT --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-between shadow-lg shadow-emerald-900/20">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-300">&times;</button>
                </div>
            @endif

            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 rounded bg-sky-500/20 text-sky-400 text-[10px] font-bold uppercase tracking-wider border border-sky-500/30">
                            AI Analysis Engine v2.0
                        </span>
                    </div>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight sm:text-4xl">
                        CV <span class="text-sky-400">Insights</span> Report
                    </h1>
                </div>
                <div class="flex gap-3">
                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('cv.history') }}" class="px-4 py-2 rounded-xl bg-slate-800 text-xs font-bold hover:bg-slate-700 transition-all border border-slate-700">History</a>
                        <a href="{{ route('cv.create') }}" class="px-4 py-2 rounded-xl bg-sky-600 text-white text-xs font-bold hover:bg-sky-500 transition-all shadow-lg shadow-sky-500/20">Analyze New</a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-800 text-white text-xs font-bold hover:bg-slate-700">Dashboard Admin</a>
                    @endif
                </div>
            </div>

            {{-- LOGIC PENGAMAN DATA (DATA PROCESSING) --}}
            @php
                // 1. Helper Function: Pastikan data selalu array, tidak pernah string JSON/null
                $safeDecode = function($data) {
                    if (is_array($data)) return $data;
                    if (is_string($data)) {
                        // Decode JSON, jika gagal kembalikan array kosong
                        $decoded = json_decode($data, true);
                        return is_array($decoded) ? $decoded : [];
                    }
                    return [];
                };

                // 2. Load & Decode Data dari Database
                $mainSkills = $safeDecode($analysis->main_skills_json);
                $divisionRecs = $safeDecode($analysis->division_recommendations_json);
                $readinessScores = $safeDecode($analysis->readiness_scores_json);
                $skillGaps = $safeDecode($analysis->skill_gap_json);
                $raw = $safeDecode($analysis->raw_ai_response);

                // 3. Fallback Values (Ambil dari RAW jika di kolom utama kosong)
                $resumeScore = $analysis->resume_score ?: ($raw['resume_score'] ?? 0);
                $atsScore = $analysis->ats_score ?: ($raw['ats_score'] ?? 0);
                $experienceLevel = $raw['experience_level'] ?? 'N/A';
                
                $strengths = $raw['strengths'] ?? [];
                $weaknesses = $raw['weaknesses'] ?? [];
                $missingSections = $raw['missing_sections'] ?? [];
                $suggestedImprovements = $raw['suggested_improvements'] ?? [];
                $grammarIssues = $raw['grammar_issues'] ?? ['critical' => 0, 'minor' => 0, 'spelling' => 0];

                // 4. Normalisasi Skill Gaps agar mudah dipanggil (Key = Nama Divisi)
                // AI kadang return object { "Role": ... } atau array [{ "division_name": ... }]
                $normalizedGaps = [];
                foreach($skillGaps as $key => $val) {
                    if (is_array($val) && isset($val['division_name'])) {
                        // Format: [{division_name: 'A', missing_skills: []}]
                        $normalizedGaps[$val['division_name']] = $val['missing_skills'] ?? [];
                    } elseif (is_string($key)) {
                        // Format: {"Role A": {missing_skills: []}}
                        $normalizedGaps[$key] = $val['missing_skills'] ?? $val;
                    }
                }
            @endphp

            {{-- 1. SCORE CARDS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                
                {{-- Overall Score --}}
                <div class="lg:col-span-2 relative overflow-hidden rounded-3xl border border-slate-800 bg-slate-900/40 p-8 shadow-2xl backdrop-blur-sm">
                    {{-- Latar belakang icon samar --}}
                    <div class="absolute top-0 right-0 p-6 opacity-20 pointer-events-none">
                        <svg class="w-32 h-32 text-sky-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>

                    <div class="relative flex flex-col md:flex-row md:items-center gap-8 z-10">
                        <div class="flex-shrink-0 text-center md:text-left">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-[0.2em] mb-1">Resume Score</p>
                            <div class="flex items-center justify-center md:justify-start">
                                <span class="text-7xl font-black text-white leading-none tracking-tighter">{{ $resumeScore }}</span>
                                <span class="text-xl font-bold text-slate-600 ml-2 mt-4">/100</span>
                            </div>
                        </div>

                        <div class="flex-grow w-full space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase mb-1">ATS Friendly</p>
                                    <p class="text-xl font-bold {{ $atsScore > 75 ? 'text-emerald-400' : 'text-amber-400' }}">{{ $atsScore }}%</p>
                                </div>
                                <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase mb-1">Experience Level</p>
                                    <p class="text-xl font-bold text-sky-400 truncate">{{ $experienceLevel }}</p>
                                </div>
                            </div>
                            
                            {{-- Progress Bar --}}
                            <div>
                                <div class="flex justify-between text-xs text-slate-400 mb-1">
                                    <span>Optimization Level</span>
                                    <span>{{ $resumeScore }}%</span>
                                </div>
                                <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-sky-600 to-emerald-500 transition-all duration-1000 ease-out" style="width: {{ $resumeScore }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Skills Cloud --}}
                <div class="rounded-3xl border border-slate-800 bg-slate-900/40 p-6 backdrop-blur-sm flex flex-col">
                    <h3 class="text-sm font-bold text-slate-200 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Detected Skills
                    </h3>
                    <div class="flex flex-wrap gap-2 content-start">
                        @forelse($mainSkills as $skill)
                            <span class="px-3 py-1.5 rounded-lg bg-sky-500/10 border border-sky-500/20 text-[11px] font-semibold text-sky-300 hover:bg-sky-500/20 transition-colors cursor-default">
                                {{ is_string($skill) ? $skill : ($skill['name'] ?? 'Skill') }}
                            </span>
                        @empty
                            <p class="text-xs text-slate-500 italic">No specific skills detected.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- 2. RECOMMENDATIONS & READINESS --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                
                {{-- Recommended Divisions --}}
                <div class="rounded-3xl border border-slate-800 bg-slate-900/40 p-6">
                    <h3 class="text-sm font-bold text-slate-200 mb-5 flex items-center justify-between">
                        <span>Rekomendasi Posisi</span>
                        <span class="text-[10px] text-slate-500 font-normal uppercase tracking-widest">AI Matching</span>
                    </h3>
                    <div class="space-y-4">
                        @forelse($divisionRecs as $rec)
                            @php
                                $divName = is_array($rec) ? ($rec['division_name'] ?? '-') : $rec;
                                $match = is_array($rec) ? ($rec['keyword_match'] ?? 0) : 0;
                                $reason = is_array($rec) ? ($rec['reason'] ?? '') : '';
                            @endphp
                            <div class="group p-4 rounded-2xl bg-slate-950/40 border border-slate-800 hover:border-sky-500/50 transition-all">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-bold text-white">{{ $divName }}</span>
                                    @if(is_array($rec))
                                        <span class="text-xs font-bold text-sky-400 bg-sky-400/10 px-2 py-0.5 rounded">{{ $match }}% Match</span>
                                    @endif
                                </div>
                                @if($reason)
                                    <p class="text-xs text-slate-400 leading-relaxed">{{ $reason }}</p>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-xs text-slate-500">Belum ada rekomendasi spesifik.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Readiness Radar --}}
                <div class="rounded-3xl border border-slate-800 bg-slate-900/40 p-6">
                    <h3 class="text-sm font-bold text-slate-200 mb-5">Kesiapan & Gap Skill</h3>
                    <div class="space-y-6">
                        @forelse($readinessScores as $key => $val)
                            @php
                                // Handle berbagai format return dari AI (Array Object vs Key-Value)
                                if (is_array($val)) {
                                    $roleName = $val['division_name'] ?? $key;
                                    $scoreVal = $val['score'] ?? 0;
                                } else {
                                    $roleName = $key;
                                    $scoreVal = $val;
                                }

                                // Cari gap skill untuk role ini
                                $missing = $normalizedGaps[$roleName] ?? [];
                            @endphp
                            
                            <div>
                                <div class="flex justify-between text-[11px] font-bold mb-2">
                                    <span class="text-slate-300">{{ $roleName }}</span>
                                    <span class="{{ $scoreVal > 70 ? 'text-emerald-400' : 'text-amber-400' }}">{{ $scoreVal }}/100</span>
                                </div>
                                <div class="h-1.5 w-full bg-slate-800 rounded-full mb-3">
                                    <div class="h-full {{ $scoreVal > 70 ? 'bg-emerald-500' : 'bg-amber-500' }} rounded-full" style="width: {{ $scoreVal }}%"></div>
                                </div>
                                
                                @if(!empty($missing))
                                    <div class="flex flex-wrap gap-1.5 mt-2">
                                        <span class="text-[10px] text-rose-400 font-bold uppercase mr-1">Missing:</span>
                                        @foreach($missing as $ms)
                                            <span class="text-[10px] text-slate-500 bg-slate-800 px-1.5 py-0.5 rounded border border-slate-700">{{ $ms }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                             <div class="text-center py-8">
                                <p class="text-xs text-slate-500">Data kesiapan tidak tersedia.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- 3. DETAILED FEEDBACK --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                
                {{-- Strengths & Weaknesses --}}
                <div class="lg:col-span-2 rounded-3xl border border-slate-800 bg-slate-900/40 overflow-hidden flex flex-col md:flex-row">
                    <div class="flex-1 p-6 border-b md:border-b-0 md:border-r border-slate-800 bg-emerald-500/[0.02]">
                        <h4 class="text-xs font-bold text-emerald-400 uppercase tracking-widest mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Strengths
                        </h4>
                        <ul class="space-y-3">
                            @forelse($strengths as $s)
                                <li class="text-xs text-slate-300 flex items-start gap-3">
                                    <span class="mt-1 flex-shrink-0 w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span class="leading-relaxed">{{ $s }}</span>
                                </li>
                            @empty
                                <li class="text-xs text-slate-500 italic">No specific strengths listed.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="flex-1 p-6 bg-rose-500/[0.02]">
                        <h4 class="text-xs font-bold text-rose-400 uppercase tracking-widest mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Weaknesses
                        </h4>
                        <ul class="space-y-3">
                            @forelse($weaknesses as $w)
                                <li class="text-xs text-slate-300 flex items-start gap-3">
                                    <span class="mt-1 flex-shrink-0 w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    <span class="leading-relaxed">{{ $w }}</span>
                                </li>
                            @empty
                                <li class="text-xs text-slate-500 italic">No critical weaknesses listed.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Issues & Grammar --}}
                <div class="space-y-6">
                    <div class="rounded-3xl border border-amber-500/20 bg-amber-500/[0.03] p-6">
                        <h4 class="text-xs font-bold text-amber-400 uppercase tracking-widest mb-4">Missing Sections</h4>
                        <div class="flex flex-wrap gap-2">
                            @forelse($missingSections as $m)
                                <span class="px-2 py-1 rounded bg-amber-500/10 text-[10px] text-amber-200 border border-amber-500/20">{{ $m }}</span>
                            @empty
                                <span class="text-xs text-slate-500 italic">Structure is complete.</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Grammar Check</h4>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="text-center p-2 rounded-xl bg-slate-950/50">
                                <p class="text-lg font-bold text-rose-500">{{ $grammarIssues['critical'] ?? 0 }}</p>
                                <p class="text-[9px] text-slate-500 uppercase">Critical</p>
                            </div>
                            <div class="text-center p-2 rounded-xl bg-slate-950/50">
                                <p class="text-lg font-bold text-amber-500">{{ $grammarIssues['minor'] ?? 0 }}</p>
                                <p class="text-[9px] text-slate-500 uppercase">Minor</p>
                            </div>
                            <div class="text-center p-2 rounded-xl bg-slate-950/50">
                                <p class="text-lg font-bold text-sky-500">{{ $grammarIssues['spelling'] ?? 0 }}</p>
                                <p class="text-[9px] text-slate-500 uppercase">Typos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. CONCLUSION & IMPROVEMENTS --}}
            <div class="rounded-3xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-8 shadow-2xl relative overflow-hidden z-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-sky-500/5 blur-3xl rounded-full pointer-events-none"></div>
                
                <h3 class="text-xl font-bold text-white mb-4 relative z-10">Kesimpulan AI</h3>
                <p class="text-slate-300 text-sm leading-relaxed mb-8 italic relative z-10 border-l-4 border-sky-500 pl-4 py-1">
                    "{{ $analysis->feedback_text ?? 'Tidak ada feedback tambahan.' }}"
                </p>
                
                <div>
                    <h4 class="text-xs font-bold text-sky-400 uppercase tracking-widest mb-4">Saran Perbaikan (Actionable Items)</h4>
                    <ul class="grid md:grid-cols-2 gap-4">
                        @forelse($suggestedImprovements as $si)
                            <li class="text-xs text-slate-300 flex items-start gap-3 bg-slate-900/50 p-3 rounded-xl border border-slate-800">
                                <svg class="w-4 h-4 text-sky-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <span class="leading-relaxed">{{ $si }}</span>
                            </li>
                        @empty
                            <li class="text-xs text-slate-500 italic">Tidak ada saran spesifik.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="mt-8 flex flex-col md:flex-row justify-between items-center gap-4 border-t border-slate-800/50 pt-6">
                    <span class="text-[10px] text-slate-600 uppercase">Ref ID: {{ $analysis->cv_submission_id }} | Generated by Groq Llama 3</span>
                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('cv.create') }}" class="relative z-20 w-full md:w-auto text-center px-6 py-2.5 rounded-xl bg-white text-slate-950 font-bold text-sm hover:bg-sky-400 hover:text-white transition-all shadow-lg">
                            Upload Revisi CV
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>