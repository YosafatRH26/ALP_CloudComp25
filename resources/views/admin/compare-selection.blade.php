<x-app-layout>
    <div class="min-h-screen bg-slate-950 text-slate-100 py-12 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="mb-10 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Select <span class="text-indigo-400">Candidates</span></h1>
                    <p class="text-slate-400 mt-2 text-sm">Klik kartu untuk memilih. Pilih <strong>tepat 2</strong> kandidat.</p>
                </div>
                <div class="text-xs text-slate-500 bg-slate-900 px-4 py-2 rounded-xl border border-slate-800">
                    Total: <span class="text-indigo-400 font-bold">{{ count($candidates) }}</span> Candidates
                </div>
            </div>

            <form action="{{ route('admin.cv.compare') }}" method="POST" id="compareForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-32">
                    @foreach($candidates as $candidate)
                        {{-- Card Utama --}}
                        <div class="cv-card relative bg-slate-900/40 border-2 border-slate-800 rounded-[2rem] p-6 cursor-pointer transition-all duration-200 hover:border-slate-600 group" 
                             data-id="{{ $candidate->id }}">
                            
                            {{-- Checkbox - Sekarang TIDAK tersembunyi secara fungsional, hanya secara visual --}}
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
                                {{-- Status Indicator --}}
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
                    @endforeach
                </div>

                {{-- Floating Action Button --}}
                <div class="fixed bottom-10 left-1/2 -translate-x-1/2 z-50 w-full max-w-xs px-4">
                    <button type="submit" id="compareBtn" disabled 
                            class="w-full bg-slate-800 text-slate-500 font-bold py-4 rounded-2xl shadow-xl transition-all cursor-not-allowed opacity-50">
                        Select 2 Candidates
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.cv-card');
            const btn = document.getElementById('compareBtn');
            const checkboxes = document.querySelectorAll('.candidate-checkbox');

            cards.forEach(card => {
                card.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const checkbox = document.getElementById('checkbox-' + id);
                    const indicator = this.querySelector('.indicator');
                    const dot = this.querySelector('.dot');

                    // Toggle Checkbox
                    checkbox.checked = !checkbox.checked;

                    // Update UI Card
                    if (checkbox.checked) {
                        this.classList.add('border-indigo-500', 'bg-indigo-500/10');
                        this.classList.remove('border-slate-800');
                        indicator.classList.add('bg-indigo-500', 'border-indigo-500');
                        dot.classList.remove('scale-0');
                    } else {
                        this.classList.remove('border-indigo-500', 'bg-indigo-500/10');
                        this.classList.add('border-slate-800');
                        indicator.classList.remove('bg-indigo-500', 'border-indigo-500');
                        dot.classList.add('scale-0');
                    }

                    updateButton();
                });
            });

            function updateButton() {
                const checkedCount = document.querySelectorAll('.candidate-checkbox:checked').length;

                if (checkedCount === 2) {
                    btn.disabled = false;
                    btn.innerText = 'Compare Now';
                    btn.classList.remove('bg-slate-800', 'text-slate-500', 'cursor-not-allowed', 'opacity-50');
                    btn.classList.add('bg-indigo-600', 'text-white', 'hover:bg-indigo-500', 'shadow-[0_10px_30px_rgba(79,70,229,0.4)]');
                } else {
                    btn.disabled = true;
                    btn.classList.add('bg-slate-800', 'text-slate-500', 'cursor-not-allowed', 'opacity-50');
                    btn.classList.remove('bg-indigo-600', 'text-white', 'hover:bg-indigo-500', 'shadow-[0_10px_30px_rgba(79,70,229,0.4)]');
                    
                    if (checkedCount === 1) {
                        btn.innerText = 'Select 1 More';
                    } else if (checkedCount > 2) {
                        btn.innerText = 'Too Many (Max 2)';
                        btn.disabled = true;
                    } else {
                        btn.innerText = 'Select 2 Candidates';
                    }
                }
            }
        });
    </script>
</x-app-layout>