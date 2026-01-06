<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 relative overflow-hidden">
        
        {{-- Background Effects --}}
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-sky-600/10 blur-[120px] rounded-full pointer-events-none animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-emerald-600/10 blur-[120px] rounded-full pointer-events-none animate-pulse" style="animation-delay: 1s;"></div>

        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Header --}}
            <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3 tracking-tight">
                        Talent Leaderboard <span class="text-sky-500">Analytics</span>
                    </h1>
                    <p class="text-slate-400 max-w-2xl">
                        Pilih kandidat di bawah ini untuk dibandingkan (Compare).
                    </p>
                </div>
                
                {{-- Stats Ringkas --}}
                <div class="bg-slate-900/40 border border-slate-800 rounded-2xl px-5 py-3 backdrop-blur-md">
                    <span class="block text-xs text-slate-500 uppercase font-bold tracking-wider">Total CV</span>
                    <span class="text-2xl font-bold text-sky-400">{{ count($analysis) }}</span>
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
            <div class="mb-8 p-2 bg-slate-900/60 backdrop-blur-xl border border-slate-700/60 rounded-2xl shadow-2xl">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-col md:flex-row gap-4 p-4">
                    <div class="flex-1">
                        <label class="block text-[10px] uppercase font-bold text-slate-500 mb-1 ml-1">Analysis Type</label>
                        <select name="analysis_mode" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                            <option value="">All Analysis Modes</option>
                            <option value="professional" {{ ($selectedMode ?? '')=='professional'?'selected':'' }}>Professional</option>
                            <option value="committee" {{ ($selectedMode ?? '')=='committee'?'selected':'' }}>Committee</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-[10px] uppercase font-bold text-slate-500 mb-1 ml-1">Division Keyword</label>
                        <input type="text" name="division" placeholder="e.g. Software Engineer..." value="{{ $selectedDivision ?? '' }}" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sky-500 outline-none placeholder:text-slate-600">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="bg-sky-600 hover:bg-sky-500 text-white px-6 py-2.5 rounded-xl font-bold transition-all shadow-lg">Filter</button>
                        <a href="{{ route('admin.dashboard') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-4 py-2.5 rounded-xl font-medium transition-all text-sm flex items-center">Reset</a>
                    </div>
                </form>
            </div>

            {{-- FORM COMPARE (Wajib membungkus list kandidat) --}}
            <form action="{{ route('admin.cv.compare') }}" method="POST" id="compareForm">
                @csrf
                
                <div class="grid gap-4 pb-24"> {{-- PB-24 agar tidak tertutup tombol melayang --}}
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

                        {{-- CARD ITEM --}}
                        <label class="cursor-pointer group relative flex flex-col md:flex-row md:items-center gap-6 bg-slate-900/40 backdrop-blur-md border border-slate-800 rounded-2xl p-6 transition-all duration-300 hover:bg-slate-900/80"
                               id="card-{{ $candidate->id }}">
                            
                            {{-- CHECKBOX AREA (Hidden Native, Styled Custom) --}}
                            <div class="absolute left-0 top-0 bottom-0 w-2 bg-transparent rounded-l-2xl transition-colors duration-300" id="indicator-{{ $candidate->id }}"></div>
                            
                            <div class="flex-shrink-0 mr-2 z-20">
                                <input type="checkbox" 
                                       name="cv_ids[]" 
                                       value="{{ $candidate->id }}" 
                                       class="peer sr-only candidate-checkbox" 
                                       onchange="toggleSelection({{ $candidate->id }})">
                                
                                {{-- Custom Checkbox UI --}}
                                <div class="w-6 h-6 rounded-lg border-2 border-slate-600 bg-slate-800 peer-checked:bg-indigo-500 peer-checked:border-indigo-500 flex items-center justify-center transition-all">
                                    <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>

                            {{-- RANK BADGE --}}
                            <div class="flex-shrink-0 flex items-center justify-center w-14 h-14 rounded-2xl 
                                {{ $rank == 0 ? 'bg-gradient-to-br from-yellow-400 to-orange-500 shadow-orange-500/20' : 
                                   ($rank == 1 ? 'bg-gradient-to-br from-slate-300 to-slate-400 shadow-slate-400/20' : 
                                   ($rank == 2 ? 'bg-gradient-to-br from-amber-600 to-amber-800 shadow-amber-800/20' : 'bg-slate-800 border border-slate-700')) }} shadow-xl">
                                <span class="text-xl font-black {{ $isTopThree ? 'text-slate-900' : 'text-slate-400' }}">#{{ $rank+1 }}</span>
                            </div>

                            {{-- INFO --}}
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center gap-3 mb-1">
                                    <h2 class="text-xl font-bold text-white truncate">{{ $candidate->cvSubmission->user->name ?? 'Unknown' }}</h2>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-800 text-slate-400 border border-slate-700">
                                        {{ $candidate->cvSubmission->analysis_mode ?? '-' }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap gap-2 mt-3">
                                    @forelse($divisions as $div)
                                        @php
                                            $divName = is_array($div) ? ($div['division_name'] ?? '') : $div;
                                            $match = isset($selectedDivision) && $selectedDivision && str_contains(strtolower($divName), strtolower($selectedDivision));
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $match ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800/50 text-slate-400 border border-slate-700' }}">
                                            {{ $divName ?: '-' }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-600">No specific division</span>
                                    @endforelse
                                </div>
                            </div>

                            {{-- SCORE --}}
                            <div class="hidden md:flex flex-col items-end gap-1 pl-4 border-l border-slate-800 min-w-[120px]">
                                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Score</div>
                                <div class="text-4xl font-black bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-cyan-400">
                                    {{ number_format($candidate->resume_score ?? 0, 1) }}
                                </div>
                                <a href="{{ route('admin.cv.result', $candidate->id) }}" class="mt-2 text-sm font-semibold text-sky-400 hover:text-sky-300 flex items-center z-30 relative">
                                    View Detail &rarr;
                                </a>
                            </div>
                        </label>
                    @empty
                        <div class="text-center py-20 bg-slate-900/20 border border-dashed border-slate-800 rounded-3xl">
                            <h3 class="text-xl font-bold text-slate-400">Data Tidak Ditemukan</h3>
                        </div>
                    @endforelse
                </div>

                {{-- FLOATING ACTION BAR (Hanya muncul jika ada yang dipilih) --}}
                <div id="compareBar" class="fixed bottom-10 left-1/2 transform -translate-x-1/2 bg-slate-900/90 backdrop-blur-xl border border-indigo-500/50 rounded-2xl shadow-2xl shadow-indigo-500/20 px-6 py-4 flex items-center gap-6 z-50 translate-y-32 transition-transform duration-500">
                    <div class="flex items-center gap-3">
                        <div class="flex -space-x-2" id="selectedAvatars">
                            {{-- Avatar akan diisi JS --}}
                        </div>
                        <div class="text-sm font-medium text-white">
                            <span id="countSelected" class="font-bold text-indigo-400 text-lg">0</span> Kandidat Dipilih
                        </div>
                    </div>
                    <div class="h-8 w-px bg-slate-700"></div>
                    <button type="submit" id="compareBtn" disabled class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        Compare Now
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- Script untuk Interaksi Checkbox & Button --}}
    <script>
        function toggleSelection(id) {
            const card = document.getElementById(`card-${id}`);
            const indicator = document.getElementById(`indicator-${id}`);
            const checkbox = card.querySelector('input[type="checkbox"]');
            
            if (checkbox.checked) {
                card.classList.add('border-indigo-500', 'bg-slate-800');
                card.classList.remove('border-slate-800', 'bg-slate-900/40');
                indicator.classList.add('bg-indigo-500');
            } else {
                card.classList.remove('border-indigo-500', 'bg-slate-800');
                card.classList.add('border-slate-800', 'bg-slate-900/40');
                indicator.classList.remove('bg-indigo-500');
            }

            updateFloatingBar();
        }

        function updateFloatingBar() {
            const checkboxes = document.querySelectorAll('.candidate-checkbox:checked');
            const bar = document.getElementById('compareBar');
            const countSpan = document.getElementById('countSelected');
            const btn = document.getElementById('compareBtn');
            const count = checkboxes.length;

            countSpan.innerText = count;

            if (count > 0) {
                bar.classList.remove('translate-y-32'); // Munculkan Bar
            } else {
                bar.classList.add('translate-y-32'); // Sembunyikan Bar
            }

            // Validasi Minimal 2
            if (count >= 2) {
                btn.disabled = false;
                btn.innerHTML = `Compare (${count}) Candidates <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>`;
            } else {
                btn.disabled = true;
                btn.innerHTML = `Pilih minimal 2`;
            }
        }
    </script>
</x-app-layout>