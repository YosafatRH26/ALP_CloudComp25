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
                        Membandingkan {{ count($candidates) }} kandidat pilihan.
                    </p>
                </div>
                <a href="{{ route('admin.cv.selection') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg text-sm font-medium transition-colors text-slate-300">
                    &larr; Kembali Memilih
                </a>
            </div>

            {{-- GRID PERBANDINGAN --}}
            <div class="grid grid-cols-1 md:grid-cols-{{ count($candidates) > 3 ? 3 : count($candidates) }} gap-6 items-start">
                
                @php
                    $maxScore = $candidates->max('resume_score');
                @endphp

                @foreach($candidates as $candidate)
                    @php
                        $rawDivisions = $candidate->division_recommendations_json;
                        $divisions = is_string($rawDivisions) ? json_decode($rawDivisions, true) : $rawDivisions;
                        $divisions = $divisions ?? [];
                        
                        $isWinner = $candidate->resume_score == $maxScore;
                    @endphp

                    <div class="relative flex flex-col bg-slate-900/60 backdrop-blur-xl border {{ $isWinner ? 'border-amber-500/50 shadow-2xl shadow-amber-500/10' : 'border-slate-800' }} rounded-3xl overflow-hidden transition-all">
                        
                        @if($isWinner)
                            <div class="absolute top-0 inset-x-0 h-1 bg-amber-500"></div>
                            <div class="absolute top-4 right-4 bg-amber-500 text-slate-900 px-2 py-0.5 rounded text-[10px] font-bold uppercase">Best Score</div>
                        @endif

                        <div class="p-6 text-center border-b border-slate-800/50">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-800 flex items-center justify-center text-2xl font-bold text-sky-400 mb-4 border border-slate-700">
                                {{ substr($candidate->cvSubmission->user->name, 0, 1) }}
                            </div>
                            <h3 class="text-lg font-bold text-white truncate">{{ $candidate->cvSubmission->user->name }}</h3>
                            <div class="text-[10px] text-slate-500 uppercase font-bold mt-1 tracking-widest">{{ $candidate->cvSubmission->analysis_mode }}</div>
                            
                            <div class="mt-4">
                                <div class="text-4xl font-black {{ $isWinner ? 'text-amber-400' : 'text-white' }}">
                                    {{ number_format($candidate->resume_score, 1) }}
                                </div>
                                <div class="text-[10px] uppercase font-bold text-slate-500">Resume Score</div>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            {{-- Division --}}
                            <div>
                                <h4 class="text-[10px] uppercase font-bold text-slate-500 mb-3 tracking-widest">Recommended Division</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(array_slice($divisions, 0, 3) as $div)
                                        <span class="px-2 py-1 rounded-md text-[10px] bg-slate-800 text-slate-300 border border-slate-700">
                                            {{ is_array($div) ? ($div['division_name'] ?? '-') : $div }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            {{-- AI Summary --}}
                            <div>
                                <h4 class="text-[10px] uppercase font-bold text-slate-500 mb-2 tracking-widest">AI Profile Summary</h4>
                                <p class="text-xs text-slate-400 leading-relaxed italic">
                                    "{{ Str::limit($candidate->summary_profile ?? 'No summary provided.', 150) }}"
                                </p>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-950/40 border-t border-slate-800">
                            <a href="{{ route('admin.cv.result', $candidate->id) }}" class="block w-full py-2 text-center text-xs font-bold text-sky-400 hover:text-sky-300 transition-colors">
                                View Detailed Analysis &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>