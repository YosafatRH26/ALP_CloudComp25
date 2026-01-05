<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 relative overflow-hidden">

        {{-- Animated Background Glow --}}
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-sky-600/10 blur-[120px] rounded-full pointer-events-none animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-emerald-600/10 blur-[120px] rounded-full pointer-events-none animate-pulse" style="animation-delay: 1s;"></div>

        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 relative">

            {{-- Header with Back Button --}}
            <div class="mb-10 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <a href="{{ route('cv.history') }}" 
                           class="inline-flex items-center gap-2 text-slate-400 hover:text-sky-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span class="text-sm font-medium">Back to History</span>
                        </a>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-white mb-3">
                        CV Comparison
                        <span class="inline-flex items-center ml-3 px-3 py-1 rounded-full bg-sky-500/20 border border-sky-500/30 text-sky-400 text-sm font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            Side by Side
                        </span>
                    </h1>
                    <p class="text-slate-400 max-w-2xl">
                        Analisis dua versi CV untuk melihat perbedaan skor, divisi rekomendasi, dan kekuatan utama.
                    </p>
                </div>
            </div>

            {{-- Score Comparison Bar --}}
            <div class="mb-10 rounded-3xl bg-slate-900/60 backdrop-blur-xl border border-slate-700/60 p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-slate-200 mb-4">Resume Score Comparison</h3>
                <div class="grid md:grid-cols-2 gap-6">
                    {{-- CV A Score --}}
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-sky-400">CV A</span>
                            <span class="text-2xl font-bold text-sky-400">{{ $cvA->resume_score }}</span>
                        </div>
                        <div class="relative h-3 bg-slate-800 rounded-full overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-sky-500 to-cyan-400 rounded-full transition-all duration-1000 ease-out shadow-lg shadow-sky-500/50"
                                 style="width: {{ $cvA->resume_score }}%">
                            </div>
                        </div>
                    </div>

                    {{-- CV B Score --}}
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-emerald-400">CV B</span>
                            <span class="text-2xl font-bold text-emerald-400">{{ $cvB->resume_score }}</span>
                        </div>
                        <div class="relative h-3 bg-slate-800 rounded-full overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-green-400 rounded-full transition-all duration-1000 ease-out shadow-lg shadow-emerald-500/50"
                                 style="width: {{ $cvB->resume_score }}%">
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $winner = $cvA->resume_score > $cvB->resume_score ? 'A' : ($cvB->resume_score > $cvA->resume_score ? 'B' : 'Tie');
                    $scoreDiff = abs($cvA->resume_score - $cvB->resume_score);
                @endphp
                
                <div class="mt-6 text-center">
                    @if($winner !== 'Tie')
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full 
                            {{ $winner === 'A' ? 'bg-sky-500/20 border border-sky-500/40 text-sky-300' : 'bg-emerald-500/20 border border-emerald-500/40 text-emerald-300' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="font-semibold">CV {{ $winner }} leads by {{ $scoreDiff }} points</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-700/40 border border-slate-600/40 text-slate-300">
                            <span class="font-semibold">Both CVs have equal scores!</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Main Comparison Cards Section --}}
            <div x-data="{ tabA: 'info', tabB: 'info' }" class="grid lg:grid-cols-2 gap-8 mb-10">

                {{-- CARD CV A --}}
                <div class="group rounded-3xl bg-slate-900/60 backdrop-blur-xl border border-slate-700/60 p-6 shadow-xl 
                            hover:border-sky-500/50 hover:shadow-2xl hover:shadow-sky-500/20 transition-all duration-300 
                            hover:scale-[1.02] hover:-translate-y-1">
                    
                    {{-- Card Header --}}
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-sky-500 to-cyan-400 flex items-center justify-center shadow-lg shadow-sky-500/30">
                                <span class="text-xl font-bold text-white">A</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-sky-400">CV Version A</h3>
                                <p class="text-xs text-slate-500">Original Submission</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-sky-500/10 border border-sky-500/30 flex items-center justify-center">
                             <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Card Tabs Navigation --}}
                    <div class="flex gap-2 mb-6">
                        <button @click="tabA='info'" :class="tabA==='info' ? 'bg-sky-500/20 text-sky-400 border-sky-500/30' : 'bg-slate-800/40 text-slate-400 border-transparent'" 
                                class="px-4 py-1.5 rounded-full text-xs font-medium border transition-all">Info</button>
                        <button @click="tabA='divisi'" :class="tabA==='divisi' ? 'bg-sky-500/20 text-sky-400 border-sky-500/30' : 'bg-slate-800/40 text-slate-400 border-transparent'" 
                                class="px-4 py-1.5 rounded-full text-xs font-medium border transition-all">Divisi</button>
                        <button @click="tabA='strengths'" :class="tabA==='strengths' ? 'bg-sky-500/20 text-sky-400 border-sky-500/30' : 'bg-slate-800/40 text-slate-400 border-transparent'" 
                                class="px-4 py-1.5 rounded-full text-xs font-medium border transition-all">Kekuatan</button>
                    </div>

                    {{-- Card Tab Content --}}
                    <div class="min-h-[250px]">
                        {{-- Info Tab --}}
                        <div x-show="tabA==='info'" x-transition class="space-y-4">
                            <div class="rounded-xl bg-slate-950/60 p-4 border border-slate-800/60">
                                <span class="text-slate-500 text-xs block mb-1">File Name</span>
                                <p class="font-medium text-slate-200 truncate">{{ $cvA->cvSubmission->original_filename }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-950/60 p-4 border border-slate-800/60">
                                <span class="text-slate-500 text-xs block mb-1">User</span>
                                <p class="text-slate-200 font-medium">{{ $cvA->cvSubmission->user->name }}</p>
                            </div>
                            <div class="rounded-xl bg-gradient-to-br from-sky-500/10 to-cyan-500/10 p-4 border border-sky-500/30">
                                <span class="text-slate-400 text-xs block mb-1">Resume Score</span>
                                <p class="text-3xl font-bold text-sky-400">{{ $cvA->resume_score }}<span class="text-lg text-slate-500 ml-1">/100</span></p>
                            </div>
                        </div>

                        {{-- Division Tab --}}
                        <div x-show="tabA==='divisi'" x-transition class="space-y-3">
                            <div class="flex flex-wrap gap-2">
                                @forelse($cvA_divRecs as $rec)
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-sky-500/10 border border-sky-500/30 text-sky-300 text-xs font-medium">
                                        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        {{ is_array($rec) ? ($rec['division_name'] ?? implode(', ', $rec)) : $rec }}
                                    </span>
                                @empty
                                    <p class="text-slate-500 text-xs italic">No recommendations available</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Strengths Tab --}}
                        <div x-show="tabA==='strengths'" x-transition class="space-y-3">
                            <ul class="space-y-2">
                                @forelse($cvA_strengths as $item)
                                    <li class="flex items-start gap-2 text-slate-300">
                                        <svg class="w-4 h-4 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        <span class="text-xs">{{ is_array($item) ? implode(', ', $item) : $item }}</span>
                                    </li>
                                @empty
                                    <p class="text-slate-500 text-xs italic">No strengths identified</p>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- CARD CV B --}}
                <div class="group rounded-3xl bg-slate-900/60 backdrop-blur-xl border border-slate-700/60 p-6 shadow-xl 
                            hover:border-emerald-500/50 hover:shadow-2xl hover:shadow-emerald-500/20 transition-all duration-300 
                            hover:scale-[1.02] hover:-translate-y-1">
                    
                    {{-- Card Header --}}
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-green-400 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                <span class="text-xl font-bold text-white">B</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-emerald-400">CV Version B</h3>
                                <p class="text-xs text-slate-500">Revised Submission</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Card Tabs Navigation --}}
                    <div class="flex gap-2 mb-6">
                        <button @click="tabB='info'" :class="tabB==='info' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-slate-800/40 text-slate-400 border-transparent'" 
                                class="px-4 py-1.5 rounded-full text-xs font-medium border transition-all">Info</button>
                        <button @click="tabB='divisi'" :class="tabB==='divisi' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-slate-800/40 text-slate-400 border-transparent'" 
                                class="px-4 py-1.5 rounded-full text-xs font-medium border transition-all">Divisi</button>
                        <button @click="tabB='strengths'" :class="tabB==='strengths' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-slate-800/40 text-slate-400 border-transparent'" 
                                class="px-4 py-1.5 rounded-full text-xs font-medium border transition-all">Kekuatan</button>
                    </div>

                    {{-- Card Tab Content --}}
                    <div class="min-h-[250px]">
                        {{-- Info Tab --}}
                        <div x-show="tabB==='info'" x-transition class="space-y-4">
                            <div class="rounded-xl bg-slate-950/60 p-4 border border-slate-800/60">
                                <span class="text-slate-500 text-xs block mb-1">File Name</span>
                                <p class="font-medium text-slate-200 truncate">{{ $cvB->cvSubmission->original_filename }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-950/60 p-4 border border-slate-800/60">
                                <span class="text-slate-500 text-xs block mb-1">User</span>
                                <p class="text-slate-200 font-medium">{{ $cvB->cvSubmission->user->name }}</p>
                            </div>
                            <div class="rounded-xl bg-gradient-to-br from-emerald-500/10 to-green-500/10 p-4 border border-emerald-500/30">
                                <span class="text-slate-400 text-xs block mb-1">Resume Score</span>
                                <p class="text-3xl font-bold text-emerald-400">{{ $cvB->resume_score }}<span class="text-lg text-slate-500 ml-1">/100</span></p>
                            </div>
                        </div>

                        {{-- Division Tab --}}
                        <div x-show="tabB==='divisi'" x-transition class="space-y-3">
                            <div class="flex flex-wrap gap-2">
                                @forelse($cvB_divRecs as $rec)
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs font-medium">
                                        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        {{ is_array($rec) ? ($rec['division_name'] ?? implode(', ', $rec)) : $rec }}
                                    </span>
                                @empty
                                    <p class="text-slate-500 text-xs italic">No recommendations available</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Strengths Tab --}}
                        <div x-show="tabB==='strengths'" x-transition class="space-y-3">
                            <ul class="space-y-2">
                                @forelse($cvB_strengths as $item)
                                    <li class="flex items-start gap-2 text-slate-300">
                                        <svg class="w-4 h-4 text-sky-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        <span class="text-xs">{{ is_array($item) ? implode(', ', $item) : $item }}</span>
                                    </li>
                                @empty
                                    <p class="text-slate-500 text-xs italic">No strengths identified</p>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

            </div> {{-- End Grid Comparison --}}
        </div> {{-- End Max-width Container --}}
    </div> {{-- End Background Wrapper --}}

    {{-- Script Alpine.js --}}
    <script src="//unpkg.com/alpinejs" defer></script>
</x-app-layout>