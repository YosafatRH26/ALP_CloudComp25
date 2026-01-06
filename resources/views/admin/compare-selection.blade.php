<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 relative overflow-hidden">
        
        {{-- Background Effects --}}
        <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Header --}}
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-white">
                        Compare <span class="text-indigo-400">Candidates</span>
                    </h1>
                    <p class="text-slate-400 text-sm mt-1">
                        {{-- FIX: Menggunakan $candidates sesuai Controller --}}
                        Pilih dari {{ count($candidates ?? []) }} kandidat yang tersedia untuk dibandingkan.
                    </p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg text-sm font-medium transition-colors text-slate-300">
                    &larr; Back to Dashboard
                </a>
            </div>

            {{-- FORM COMPARE --}}
            <form action="{{ route('admin.cv.compare') }}" method="POST" id="compareForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-32">
                    {{-- FIX: Loop menggunakan $candidates --}}
                    @forelse($candidates as $candidate)
                        @php
                            $rawDivisions = $candidate->division_recommendations_json;
                            $divisions = is_string($rawDivisions) ? json_decode($rawDivisions, true) : $rawDivisions;
                            $divisions = $divisions ?? [];
                        @endphp

                        <label class="cursor-pointer group relative flex flex-col bg-slate-900/40 backdrop-blur-md border border-slate-800 rounded-3xl p-6 transition-all duration-300 hover:bg-slate-900/80 hover:scale-[1.02]"
                               id="card-{{ $candidate->id }}">
                            
                            {{-- Checkbox --}}
                            <div class="absolute top-4 right-4 z-20">
                                <input type="checkbox" 
                                       name="cv_ids[]" 
                                       value="{{ $candidate->id }}" 
                                       class="peer sr-only candidate-checkbox" 
                                       onchange="toggleSelection({{ $candidate->id }})">
                                
                                <div class="w-8 h-8 rounded-full border-2 border-slate-600 bg-slate-900 peer-checked:bg-indigo-500 peer-checked:border-indigo-500 flex items-center justify-center transition-all shadow-lg">
                                    <svg class="w-5 h-5 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-slate-800 flex items-center justify-center text-xl font-bold text-sky-400 border border-slate-700">
                                    {{ substr($candidate->cvSubmission->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div class="overflow-hidden">
                                    <h3 class="font-bold text-white text-lg truncate pr-8">{{ $candidate->cvSubmission->user->name ?? 'Unknown' }}</h3>
                                    <div class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">{{ $candidate->cvSubmission->analysis_mode }}</div>
                                </div>
                            </div>

                            <div class="mt-auto pt-4 border-t border-slate-800 flex justify-between items-end">
                                <div>
                                    <span class="text-[10px] text-slate-500 uppercase font-bold">Score</span>
                                    <div class="text-3xl font-black text-sky-400">
                                        {{ number_format($candidate->resume_score ?? 0, 1) }}
                                    </div>
                                </div>
                                <div class="text-right text-xs text-slate-500">
                                    {{ $candidate->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </label>
                    @empty
                        <div class="col-span-full text-center py-20 bg-slate-900/20 border border-dashed border-slate-800 rounded-3xl">
                            <p class="text-slate-500">Belum ada data kandidat untuk dibandingkan.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Floating Bar --}}
                <div id="compareBar" class="fixed bottom-10 left-1/2 transform -translate-x-1/2 bg-slate-900/95 backdrop-blur-xl border border-indigo-500/50 rounded-2xl shadow-2xl px-6 py-4 flex items-center gap-6 z-50 translate-y-40 transition-transform duration-500">
                    <div class="text-sm font-medium text-white">
                        <span id="countSelected" class="font-bold text-indigo-400 text-lg">0</span> Kandidat Terpilih
                    </div>
                    <div class="h-8 w-px bg-slate-700"></div>
                    <button type="submit" id="compareBtn" disabled class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 px-6 rounded-xl transition-all disabled:opacity-50">
                        Compare Now
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSelection(id) {
            const card = document.getElementById(`card-${id}`);
            const checkbox = card.querySelector('input[type="checkbox"]');
            if (checkbox.checked) {
                card.classList.add('border-indigo-500', 'bg-slate-800/80');
                card.classList.remove('border-slate-800');
            } else {
                card.classList.remove('border-indigo-500', 'bg-slate-800/80');
                card.classList.add('border-slate-800');
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

            if (count > 0) bar.classList.remove('translate-y-40');
            else bar.classList.add('translate-y-40');

            btn.disabled = count < 2;
            btn.innerText = count < 2 ? 'Pilih Min. 2' : 'Compare Now';
        }
    </script>
</x-app-layout>