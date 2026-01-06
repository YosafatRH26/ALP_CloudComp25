<x-app-layout>
    <div class="min-h-screen bg-slate-950 text-slate-100 py-12 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Header & Search Section --}}
            <div class="mb-10 bg-slate-900/50 p-8 rounded-[2rem] border border-slate-800">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <div>
                        <h1 class="text-3xl font-bold text-white tracking-tight">Select <span class="text-indigo-400">Candidates</span></h1>
                        <p class="text-slate-400 mt-1 text-sm">Cari kategori (misal: Marketing) lalu pilih <strong>tepat 2</strong> kandidat.</p>
                    </div>

                    {{-- Input Text Filter --}}
                    <form action="{{ route('admin.cv.selection') }}" method="GET" class="w-full lg:w-96">
                        <div class="relative group">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search category or name..." 
                                   class="w-full bg-slate-950 border-slate-800 rounded-2xl py-3.5 pl-5 pr-12 text-sm text-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all placeholder:text-slate-600">
                            
                            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-indigo-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                        @if(request('search'))
                            <div class="mt-2 flex justify-end">
                                <a href="{{ route('admin.cv.selection') }}" class="text-[10px] text-slate-500 hover:text-white uppercase font-bold tracking-widest">Clear Search</a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <form action="{{ route('admin.cv.compare') }}" method="POST" id="compareForm">
                @csrf
                
                {{-- Action Bar --}}
                <div class="mb-8 flex items-center justify-between bg-indigo-500/5 border border-indigo-500/20 p-6 rounded-2xl">
                    <div class="flex items-center gap-6">
                        <div class="text-sm">
                            @if(request('search'))
                                <span class="text-slate-500">Results for:</span> <span class="text-indigo-400 font-bold">"{{ request('search') }}"</span>
                            @else
                                <span class="text-slate-500">Showing:</span> <span class="text-white font-bold">{{ count($candidates) }}</span> Candidates
                            @endif
                        </div>
                        <div class="h-4 w-px bg-slate-800"></div>
                        <div class="text-sm">
                            Selected: <span id="countSelected" class="text-indigo-400 font-black">0</span><span class="text-slate-500">/2</span>
                        </div>
                    </div>
                    <button type="submit" id="compareBtn" disabled 
                            class="bg-slate-800 text-slate-500 font-bold py-3 px-10 rounded-xl transition-all cursor-not-allowed opacity-50 shadow-xl">
                        Compare Now
                    </button>
                </div>

                {{-- Grid Card --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($candidates as $candidate)
                        <div class="cv-card relative bg-slate-900/40 border-2 border-slate-800 rounded-[2rem] p-6 cursor-pointer transition-all duration-200 hover:border-slate-600 group" 
                             data-id="{{ $candidate->id }}">
                            
                            <input type="checkbox" name="cv_ids[]" value="{{ $candidate->id }}" 
                                   id="checkbox-{{ $candidate->id }}"
                                   class="candidate-checkbox absolute opacity-0 w-0 h-0">
                            
                            <div class="flex items-center gap-4 mb-4 pointer-events-none">
                                <div class="w-12 h-12 rounded-xl bg-slate-800 flex items-center justify-center font-bold text-sky-400 border border-slate-700 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                    {{ substr($candidate->cvSubmission->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-white font-bold truncate">{{ $candidate->cvSubmission->user->name }}</h3>
                                    <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">{{ $candidate->cvSubmission->analysis_mode }}</p>
                                </div>
                                <div class="indicator w-6 h-6 rounded-full border-2 border-slate-700 flex items-center justify-center transition-all">
                                    <div class="dot w-2 h-2 bg-white rounded-full scale-0 transition-transform"></div>
                                </div>
                            </div>

                            <div class="flex justify-between items-end pt-4 border-t border-slate-800/50 pointer-events-none">
                                <div>
                                    <p class="text-[10px] text-slate-500 uppercase font-bold">Resume Score</p>
                                    <p class="text-2xl font-black text-white">{{ number_format($candidate->resume_score, 1) }}</p>
                                </div>
                                <p class="text-[10px] text-slate-500">{{ $candidate->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center bg-slate-900/20 border border-dashed border-slate-800 rounded-[2rem]">
                            <svg class="w-12 h-12 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-slate-500">No candidates found matching your search.</p>
                        </div>
                    @endforelse
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.cv-card');
            const btn = document.getElementById('compareBtn');
            const countDisplay = document.getElementById('countSelected');

            cards.forEach(card => {
                card.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const checkbox = document.getElementById('checkbox-' + id);
                    const indicator = this.querySelector('.indicator');
                    const dot = this.querySelector('.dot');

                    checkbox.checked = !checkbox.checked;

                    if (checkbox.checked) {
                        this.classList.add('border-indigo-500', 'bg-indigo-500/10', 'ring-1', 'ring-indigo-500/50');
                        this.classList.remove('border-slate-800');
                        indicator.classList.add('bg-indigo-500', 'border-indigo-500');
                        dot.classList.remove('scale-0');
                    } else {
                        this.classList.remove('border-indigo-500', 'bg-indigo-500/10', 'ring-1', 'ring-indigo-500/50');
                        this.classList.add('border-slate-800');
                        indicator.classList.remove('bg-indigo-500', 'border-indigo-500');
                        dot.classList.add('scale-0');
                    }

                    updateButton();
                });
            });

            function updateButton() {
                const checkedCount = document.querySelectorAll('.candidate-checkbox:checked').length;
                countDisplay.innerText = checkedCount;

                if (checkedCount === 2) {
                    btn.disabled = false;
                    btn.classList.remove('bg-slate-800', 'text-slate-500', 'cursor-not-allowed', 'opacity-50');
                    btn.classList.add('bg-indigo-600', 'text-white', 'hover:bg-indigo-500', 'shadow-[0_10px_30px_rgba(79,70,229,0.4)]');
                } else {
                    btn.disabled = true;
                    btn.classList.add('bg-slate-800', 'text-slate-500', 'cursor-not-allowed', 'opacity-50');
                    btn.classList.remove('bg-indigo-600', 'text-white', 'hover:bg-indigo-500', 'shadow-[0_10px_30px_rgba(79,70,229,0.4)]');
                }
            }
        });
    </script>
</x-app-layout>