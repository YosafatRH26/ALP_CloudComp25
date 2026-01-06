<x-app-layout>
    <div class="min-h-screen bg-slate-950 text-slate-100 py-12 relative overflow-hidden">
        {{-- Background Effect --}}
        <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-3xl font-extrabold text-white">Versus <span class="text-indigo-400">Comparison</span></h1>
                    <p class="text-slate-400 text-sm mt-1">Perbandingan kompetensi dan keunggulan kandidat pilihan.</p>
                </div>
                <a href="{{ route('admin.cv.selection') }}" class="px-5 py-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-sm font-semibold transition-all text-slate-300">
                    &larr; Pilih Ulang
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative">
                {{-- VS Badge --}}
                <div class="hidden md:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20 w-14 h-14 bg-indigo-600 rounded-full items-center justify-center font-black text-white shadow-[0_0_30px_rgba(79,70,229,0.4)] border-4 border-slate-950">
                    VS
                </div>

                @php $maxScore = $candidates->max('resume_score'); @endphp

                @foreach($candidates as $candidate)
                    @php
                        // Helper untuk decode JSON Skills
                        $skills = is_string($candidate->main_skills_json) 
                            ? json_decode($candidate->main_skills_json, true) 
                            : $candidate->main_skills_json;
                        
                        // Helper untuk decode JSON Strengths
                        $strengths = is_string($candidate->strengths_json) 
                            ? json_decode($candidate->strengths_json, true) 
                            : $candidate->strengths_json;

                        $isWinner = $candidate->resume_score == $maxScore;
                    @endphp

                    <div class="bg-slate-900/50 backdrop-blur-xl border {{ $isWinner ? 'border-amber-500/50 shadow-2xl shadow-amber-500/10' : 'border-slate-800' }} rounded-[2.5rem] p-8 transition-all hover:scale-[1.01]">
                        
                        {{-- Winner Tag --}}
                        @if($isWinner)
                            <div class="flex justify-center mb-4">
                                <span class="bg-amber-500/20 text-amber-400 border border-amber-500/30 px-4 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">
                                    ⭐ Best Match
                                </span>
                            </div>
                        @endif

                        <div class="text-center border-b border-slate-800/50 pb-8">
                            <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-br from-slate-800 to-slate-700 flex items-center justify-center text-3xl font-bold text-sky-400 mb-4 border border-slate-700 shadow-xl">
                                {{ substr($candidate->cvSubmission->user->name, 0, 1) }}
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-1">{{ $candidate->cvSubmission->user->name }}</h3>
                            <div class="text-[10px] text-slate-500 uppercase font-black tracking-widest">{{ $candidate->cvSubmission->analysis_mode }}</div>
                            
                            <div class="mt-6">
                                <div class="text-6xl font-black {{ $isWinner ? 'text-amber-400' : 'text-white' }}">
                                    {{ number_format($candidate->resume_score, 1) }}
                                </div>
                                <div class="text-xs uppercase font-bold text-slate-500 mt-1">Resume Score</div>
                            </div>
                        </div>

                        <div class="py-8 space-y-8">
                            {{-- Skills Section --}}
                            <div>
                                <h4 class="text-[10px] uppercase font-bold text-slate-500 mb-4 tracking-widest text-center">Core Skills</h4>
                                <div class="flex flex-wrap justify-center gap-2">
                                    @forelse($skills ?? [] as $skill)
                                        <span class="px-3 py-1 bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 rounded-lg text-[11px] font-medium">
                                            {{ is_array($skill) ? ($skill['name'] ?? $skill['skill_name'] ?? '-') : $skill }}
                                        </span>
                                    @empty
                                        <span class="text-slate-600 text-xs italic">No skills listed</span>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Strengths Section (Ganti dari AI Insight) --}}
                            <div>
                                <h4 class="text-[10px] uppercase font-bold text-slate-500 mb-4 tracking-widest text-center">Key Strengths</h4>
                                <div class="space-y-3">
                                    @forelse($strengths ?? [] as $strength)
                                        <div class="flex items-start gap-3 bg-slate-950/40 p-3 rounded-xl border border-slate-800/50">
                                            <svg class="w-4 h-4 text-emerald-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <p class="text-xs text-slate-300 leading-relaxed">
                                                {{ is_array($strength) ? ($strength['description'] ?? $strength['point'] ?? '-') : $strength }}
                                            </p>
                                        </div>
                                    @empty
                                        <div class="text-center p-4 bg-slate-950/20 rounded-xl border border-dashed border-slate-800">
                                            <p class="text-xs text-slate-500 italic">No specific strengths identified</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('admin.cv.result', $candidate->id) }}" class="group block w-full py-4 bg-slate-800 hover:bg-indigo-600 rounded-2xl text-center text-sm font-bold text-white transition-all shadow-lg">
                                View Full Profile Details <span class="inline-block transition-transform group-hover:translate-x-1">&rarr;</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>