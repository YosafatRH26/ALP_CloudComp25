<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 relative overflow-hidden">
        
        {{-- Animated Background Glow --}}
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-sky-600/10 blur-[120px] rounded-full pointer-events-none animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-emerald-600/10 blur-[120px] rounded-full pointer-events-none animate-pulse" style="animation-delay: 1s;"></div>

        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Header Section --}}
            <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3 tracking-tight">
                        Talent Leaderboard <span class="text-sky-500">Analytics</span>
                    </h1>
                    <p class="text-slate-400 max-w-2xl">
                        Temukan kandidat terbaik berdasarkan skor AI. Pantau peringkat, divisi rekomendasi, dan performa resume secara real-time.
                    </p>
                </div>
                
                {{-- Stats Ringkas --}}
                <div class="flex gap-4">
                    <div class="bg-slate-900/40 border border-slate-800 rounded-2xl px-5 py-3 backdrop-blur-md">
                        <span class="block text-xs text-slate-500 uppercase font-bold tracking-wider">Total CV</span>
                        <span class="text-2xl font-bold text-sky-400">{{ count($analysis) }}</span>
                    </div>
                </div>
            </div>

            {{-- ERROR MESSAGE --}}
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400">
                    <ul class="list-disc list-inside text-sm font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FILTER PANEL --}}
            <div class="mb-10 p-2 bg-slate-900/60 backdrop-blur-xl border border-slate-700/60 rounded-2xl shadow-2xl">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-col md:flex-row gap-4 p-4">
                    {{-- Filter Mode --}}
                    <div class="flex-1">
                        <label class="block text-[10px] uppercase font-bold text-slate-500 mb-1 ml-1">Analysis Type</label>
                        <select name="analysis_mode"
                                class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sky-500 transition-all outline-none">
                            <option value="">All Analysis Modes</option>
                            <option value="professional" {{ ($selectedMode ?? '')=='professional'?'selected':'' }}>Professional</option>
                            <option value="committee" {{ ($selectedMode ?? '')=='committee'?'selected':'' }}>Committee</option>
                        </select>
                    </div>

                    {{-- Filter Division --}}
                    <div class="flex-1">
                        <label class="block text-[10px] uppercase font-bold text-slate-500 mb-1 ml-1">Division Keyword</label>
                        <input type="text"
                               name="division"
                               placeholder="e.g. Software Engineer, HR..."
                               value="{{ $selectedDivision ?? '' }}"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sky-500 transition-all placeholder:text-slate-600">
                    </div>

                    <div class="flex items-end gap-2">
                        <button class="bg-sky-600 hover:bg-sky-500 text-white px-8 py-2.5 rounded-xl font-bold transition-all shadow-lg shadow-sky-900/20 active:scale-95">
                            Apply Filter
                        </button>
                        <a href="{{ route('admin.dashboard') }}"
                           class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-4 py-2.5 rounded-xl font-medium transition-all text-sm flex items-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- FORM WRAPPER (Untuk Fitur Compare) --}}
            <form action="{{ route('admin.cv.compare') }}" method="POST">
                @csrf
                
                {{-- Sticky Action Bar --}}
                <div class="sticky top-4 z-30 mb-6 flex justify-end pointer-events-none">
                    <button type="submit" class="pointer-events-auto bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 px-8 rounded-2xl shadow-xl shadow-indigo-900/30 flex items-center gap-2 transform hover:-translate-y-1 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Compare Selected
                    </button>
                </div>

                {{-- TALENT LIST --}}
                <div class="grid gap-4">
                    @forelse($analysis as $rank => $candidate)
                        @php
                            $rawDivisions = $candidate->division_recommendations_json;
                            if (is_string($rawDivisions)) {
                                $divisions = json_decode($rawDivisions, true);
                            } else {
                                $divisions = $rawDivisions;
                            }
                            $divisions = $divisions ?? [];
                            $isTopThree = $rank < 3;
                        @endphp

                        <div class="group relative flex flex-col md:flex-row md:items-center gap-6 bg-slate-900/40 backdrop-blur-md border border-slate-800 rounded-2xl p-6 hover:border-sky-500/50 hover:bg-slate-900/60 transition-all duration-300">
                            
                            {{-- CHECKBOX (Wajib ada untuk fitur Compare) --}}
                            <div class="absolute top-6 right-6 md:relative md:top-auto md:right-auto z-20">
                                <input type="checkbox" name="cv_ids[]" value="{{ $candidate->id }}" 
                                    class="w-6 h-6 rounded bg-slate-800 border-slate-600 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            </div>

                            {{-- RANK BADGE --}}
                            <div class="flex-shrink-0 flex items-center justify-center w-14 h-14 rounded-2xl 
                                {{ $rank == 0 ? 'bg-gradient-to-br from-yellow-400 to-orange-500 shadow-orange-500/20' : 
                                   ($rank == 1 ? 'bg-gradient-to-br from-slate-300 to-slate-400 shadow-slate-400/20' : 
                                   ($rank == 2 ? 'bg-gradient-to-br from-amber-600 to-amber-800 shadow-amber-800/20' : 'bg-slate-800 border border-slate-700')) }} shadow-xl">
                                <span class="text-xl font-black {{ $isTopThree ? 'text-slate-900' : 'text-slate-400' }}">
                                    #{{ $rank+1 }}
                                </span>
                            </div>

                            {{-- CANDIDATE INFO --}}
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center gap-3 mb-1">
                                    <h2 class="text-xl font-bold text-white truncate group-hover:text-sky-400 transition-colors">
                                        {{ $candidate->cvSubmission->user->name ?? 'Unknown Candidate' }}
                                    </h2>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-800 text-slate-400 border border-slate-700">
                                        {{ $candidate->cvSubmission->analysis_mode ?? '-' }}
                                    </span>
                                </div>

                                {{-- Recommended Divisions --}}
                                <div class="flex flex-wrap gap-2 mt-3">
                                    @forelse($divisions as $div)
                                        @php
                                            $divName = is_array($div) ? ($div['division_name'] ?? '') : $div;
                                            $match = isset($selectedDivision) && $selectedDivision && str_contains(strtolower($divName), strtolower($selectedDivision));
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                            {{ $match ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800/50 text-slate-400 border border-slate-700' }}">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $divName ?: '-' }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-600 italic">No specific division recommended</span>
                                    @endforelse
                                </div>
                            </div>

                            {{-- SCORE & ACTION --}}
                            <div class="hidden md:flex flex-col items-end gap-1 pl-4 border-l border-slate-800 min-w-[120px]">
                                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Score</div>
                                <div class="text-4xl font-black bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-cyan-400">
                                    {{ number_format($candidate->resume_score ?? 0, 1) }}
                                </div>
                                <a href="{{ route('admin.cv.result', $candidate->id) }}"
                                   class="mt-2 text-sm font-semibold text-sky-400 hover:text-sky-300 flex items-center group/link transition-colors">
                                    View Full Report 
                                    <svg class="w-4 h-4 ml-1 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20 bg-slate-900/20 border border-dashed border-slate-800 rounded-3xl">
                            <div class="w-20 h-20 bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-600">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-400">Data Tidak Ditemukan</h3>
                            <p class="text-slate-500">Belum ada kandidat yang sesuai dengan kriteria filter Anda.</p>
                        </div>
                    @endforelse
                </div>
            </form>

        </div>
    </div>
</x-app-layout>