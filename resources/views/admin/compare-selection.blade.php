<x-app-layout>
    <div class="min-h-screen bg-slate-950 text-slate-100 relative overflow-hidden">
        
        {{-- Background Dekorasi agar tidak flat --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-600/20 blur-[120px] rounded-full"></div>
            <div class="absolute top-1/2 -right-24 w-80 h-80 bg-purple-600/10 blur-[100px] rounded-full"></div>
        </div>

        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Header --}}
            <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black text-white tracking-tight">
                        Compare <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">Candidates</span>
                    </h1>
                    <p class="text-slate-400 mt-2 text-lg">
                        Pilih minimal 2 kandidat untuk melihat perbandingan mendalam.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 bg-slate-900/80 border border-slate-800 rounded-xl text-sm text-slate-400">
                        Total: <span class="text-indigo-400 font-bold">{{ count($candidates ?? []) }}</span> Candidates
                    </span>
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 rounded-xl text-sm font-semibold transition-all">
                        &larr; Dashboard
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.cv.compare') }}" method="POST" id="compareForm">
                @csrf
                
                {{-- Grid Card --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pb-40">
                    @forelse($candidates as $candidate)
                        <label class="group relative flex flex-col bg-slate-900/50 backdrop-blur-sm border border-slate-800 rounded-[2rem] p-8 transition-all duration-300 hover:border-indigo-500/50 hover:bg-slate-900/80 cursor-pointer" id="card-{{ $candidate->id }}">
                            
                            {{-- Checkbox Hidden --}}
                            <input type="checkbox" name="cv_ids[]" value="{{ $candidate->id }}" 
                                   class="peer sr-only candidate-checkbox" 
                                   onchange="toggleSelection({{ $candidate->id }})">

                            {{-- Custom Checkbox UI --}}
                            <div class="absolute top-6 right-6 w-7 h-7 rounded-full border-2 border-slate-700 bg-slate-950 peer-checked:bg-indigo-500 peer-checked:border-indigo-500 flex items-center justify-center transition-all shadow-xl">
                                <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4">
                                    <path d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>

                            {{-- Profile Info --}}
                            <div class="flex items-center gap-5 mb-8">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-2xl font-bold text-white shadow-lg">
                                    {{ substr($candidate->cvSubmission->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xl font-bold text-white truncate group-hover:text-indigo-400 transition-colors">
                                        {{ $candidate->cvSubmission->user->name ?? 'Unknown' }}
                                    </h3>
                                    <span class="inline-block px-2 py-0.5 mt-1 bg-indigo-500/10 text-indigo-400 text-[10px] font-bold uppercase tracking-widest rounded-md border border-indigo-500/20">
                                        {{ $candidate->cvSubmission->analysis_mode }}
                                    </span>
                                </div>
                            </div>

                            {{-- Stats --}}
                            <div class="grid grid-cols-2 gap-4 mt-auto pt-6 border-t border-slate-800/50">
                                <div>
                                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Resume Score</p>
                                    <p class="text-3xl font-black text-white group-hover:text-cyan-400 transition-colors">
                                        {{ number_format($candidate->resume_score ?? 0, 1) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Analyzed At</p>
                                    <p class="text-sm font-medium text-slate-400 mt-2">
                                        {{ $candidate->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </label>
                    @empty
                        <div class="col-span-full py-20 text-center bg-slate-900/20 border border-dashed border-slate-800 rounded-[2rem]">
                            <p class="text-slate-500 text-lg">Tidak ada kandidat untuk dipilih.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Floating Action Bar --}}
                <div id="compareBar" class="fixed bottom-8 left-1/2 -translate-x-1/2 w-[90%] max-w-md bg-slate-900/90 backdrop-blur-2xl border border-white/10 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] p-5 flex items-center justify-between z-50 translate-y-40 transition-all duration-500 ease-out opacity-0">
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-tighter">Selected</p>
                        <p class="text-xl font-black text-white"><span id="countSelected" class="text-indigo-400">0</span> Candidates</p>
                    </div>
                    <button type="submit" id="compareBtn" disabled 
                            class="bg-white text-slate-950 hover:bg-indigo-400 hover:text-white disabled:bg-slate-800 disabled:text-slate-600 font-bold py-3 px-8 rounded-2xl transition-all shadow-lg active:scale-95">
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
                card.classList.add('border-indigo-500', 'bg-indigo-500/5', 'ring-1', 'ring-indigo-500/50');
            } else {
                card.classList.remove('border-indigo-500', 'bg-indigo-500/5', 'ring-1', 'ring-indigo-500/50');
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
                bar.classList.remove('translate-y-40', 'opacity-0');
            } else {
                bar.classList.add('translate-y-40', 'opacity-0');
            }

            if (count >= 2) {
                btn.disabled = false;
                btn.innerText = 'Compare Now';
            } else {
                btn.disabled = true;
                btn.innerText = count === 1 ? 'Pilih 1 lagi' : 'Pilih Min. 2';
            }
        }
    </script>
</x-app-layout>