<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 relative overflow-hidden">
        
        {{-- Background Effects --}}
        <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Header --}}
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-white">
                        Comparison <span class="text-indigo-400">Result</span>
                    </h1>
                    <p class="text-slate-400 text-sm mt-1">
                        Membandingkan {{ count($candidates) }} kandidat secara berdampingan.
                    </p>
                </div>
                <a href="{{ route('admin.cv.selection') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg text-sm font-medium transition-colors text-slate-300">
                    &larr; Pilih Ulang
                </a>
            </div>

            {{-- COMPARISON GRID --}}
            {{-- Grid otomatis menyesuaikan jumlah kandidat --}}
            <div class="grid grid-cols-1 md:grid-cols-{{ count($candidates) > 3 ? 3 : count($candidates) }} gap-6">
                
                @php
                    // Mencari skor tertinggi untuk menentukan pemenang
                    $maxScore = $candidates->max('resume_score');
                @endphp

                @foreach($candidates as $candidate)
                    @php
                        // Helper untuk decode JSON aman
                        $safeDecode = function($json) {
                            if (is_array($json)) return $json;
                            $decoded = json_decode($json, true);
                            return is_array($decoded) ? $decoded : [];
                        };

                        $divisions = $safeDecode($candidate->division_recommendations_json);
                        $skills = $safeDecode($candidate->main_skills_json); // Asumsi ada kolom ini
                        $isWinner = $candidate->resume_score == $maxScore;
                    @endphp

                    <div class="relative flex flex-col bg-slate-900/60 backdrop-blur-xl border {{ $isWinner ? 'border-amber-500/50 shadow-2xl shadow-amber-500/10' : 'border-slate-800' }} rounded-3xl overflow-hidden transition-all hover:scale-[1.01]">
                        
                        {{-- Winner Badge --}}
                        @if($isWinner)
                            <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                            <div class="absolute top-4 right-4 bg-amber-500/20 border border-amber-500/50 text-amber-400 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-lg">
                                ⭐ Top Rated
                            </div>
                        @endif

                        {{-- Header Profile --}}
                        <div class="p-6 text-center border-b border-slate-800/50">
                            <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-br {{ $isWinner ? 'from-amber-500 to-orange-600' : 'from-slate-700 to-slate-600' }} flex items-center justify-center text-3xl font-bold text-white mb-4 shadow-lg">
                                {{ substr($candidate->cvSubmission->user->name, 0, 1) }}
                            </div>
                            <h3 class="text-xl font-bold text-white truncate px-2">
                                {{ $candidate->cvSubmission->user->name }}
                            </h3>
                            <p class="text-xs text-slate-500 uppercase mt-1">{{ $candidate->cvSubmission->analysis_mode }}</p>

                            {{-- BIG SCORE --}}
                            <div class="mt-4">
                                <span class="text-4xl font-black {{ $isWinner ? 'text-amber-400' : 'text-slate-200' }}">
                                    {{ number_format($candidate->resume_score, 1) }}
                                </span>
                                <span class="text-xs text-slate-500 block">Resume Score</span>
                            </div>
                        </div>

                        {{-- Details Body --}}
                        <div class="p-6 space-y-6 flex-grow">
                            
                            {{-- Recommended Divisions --}}
                            <div>
                                <h4 class="text-[10px] uppercase font-bold text-slate-500 mb-3 text-center">Rekomendasi Divisi</h4>
                                <div class="flex flex-wrap justify-center gap-2">
                                    @forelse(array_slice($divisions, 0, 3) as $div)
                                        @php $divName = is_array($div) ? ($div['division_name'] ?? '-') : $div; @endphp
                                        <span class="px-3 py-1 rounded-lg text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">
                                            {{ $divName }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-600">-</span>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Skills (Jika ada datanya) --}}
                            @if(count($skills) > 0)
                            <div>
                                <h4 class="text-[10px] uppercase font-bold text-slate-500 mb-3 text-center">Skill Utama</h4>
                                <div class="flex flex-wrap justify-center gap-1.5">
                                    @foreach(array_slice($skills, 0, 5) as $skill)
                                        @php $skillName = is_array($skill) ? ($skill['name'] ?? '-') : $skill; @endphp
                                        <span class="px-2 py-1 rounded text-[10px] font-medium bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                                            {{ $skillName }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- AI Summary (Short) --}}
                            <div class="bg-slate-950/50 rounded-xl p-3 border border-slate-800">
                                <p class="text-xs text-slate-400 italic text-center line-clamp-3">
                                    "{{ Str::limit($candidate->summary_profile ?? 'Tidak ada ringkasan profil.', 100) }}"
                                </p>
                            </div>

                        </div>

                        {{-- Footer Action --}}
                        <div class="p-4 border-t border-slate-800/50">
                            <a href="{{ route('admin.cv.result', $candidate->id) }}" class="block w-full py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-center text-sm font-bold text-white transition-colors">
                                Lihat Detail Lengkap
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>