<x-app-layout>
    <div class="min-h-screen bg-slate-950 text-slate-100 py-12 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="mb-10 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Select <span class="text-indigo-400">Candidates</span></h1>
                    <p class="text-slate-400 mt-2 text-sm">Pilih **2 kandidat** untuk memulai perbandingan.</p>
                </div>
                <div class="text-xs text-slate-500 bg-slate-900 px-4 py-2 rounded-xl border border-slate-800">
                    Total: <span class="text-indigo-400 font-bold">{{ count($candidates) }}</span> Candidates
                </div>
            </div>

            <form action="{{ route('admin.cv.compare') }}" method="POST" id="mainCompareForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-32">
                    @foreach($candidates as $candidate)
                        {{-- Card Utama --}}
                        <div onclick="toggleCard({{ $candidate->id }})" 
                             id="card-{{ $candidate->id }}"
                             class="relative bg-slate-900/40 border-2 border-slate-800 rounded-[2rem] p-6 cursor-pointer transition-all duration-200 hover:border-slate-600 group">
                            
                            {{-- Checkbox Hidden (Tetap ada untuk kirim data ke server) --}}
                            <input type="checkbox" name="cv_ids[]" value="{{ $candidate->id }}" 
                                   id="checkbox-{{ $candidate->id }}"
                                   class="hidden candidate-checkbox"
                                   onclick="event.stopPropagation()"> {{-- Stop propagation agar tidak double click --}}
                            
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-800 flex items-center justify-center font-bold text-sky-400 border border-slate-700 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                    {{ substr($candidate->cvSubmission->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-white font-bold truncate">{{ $candidate->cvSubmission->user->name }}</h3>
                                    <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">{{ $candidate->cvSubmission->analysis_mode }}</p>
                                </div>
                                {{-- Status Indicator --}}
                                <div id="indicator-{{ $candidate->id }}" class="w-6 h-6 rounded-full border-2 border-slate-700 flex items-center justify-center transition-all">
                                    <div class="w-2 h-2 bg-white rounded-full scale-0 transition-transform"></div>
                                </div>
                            </div>

                            <div class="flex justify-between items-end pt-4 border-t border-slate-800/50">
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
                            class="w-full bg-slate-800 text-slate-500 font-bold py-4 rounded-2xl shadow-xl transition-all cursor-not-allowed">
                        Select 2 Candidates
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleCard(id) {
            const checkbox = document.getElementById('checkbox-' + id);
            const card = document.getElementById('card-' + id);
            const indicator = document.getElementById('indicator-' + id);
            const dot = indicator.querySelector('div');

            // Toggle checkbox
            checkbox.checked = !checkbox.checked;

            // Visual Updates
            if (checkbox.checked) {
                card.classList.add('border-indigo-500', 'bg-indigo-500/10');
                card.classList.remove('border-slate-800');
                indicator.classList.add('bg-indigo-500', 'border-indigo-500');
                dot.classList.remove('scale-0');
            } else {
                card.classList.remove('border-indigo-500', 'bg-indigo-500/10');
                card.classList.add('border-slate-800');
                indicator.classList.remove('bg-indigo-500', 'border-indigo-500');
                dot.classList.add('scale-0');
            }

            updateButton();
        }

        function updateButton() {
            const checkedCount = document.querySelectorAll('.candidate-checkbox:checked').length;
            const btn = document.getElementById('compareBtn');

            if (checkedCount === 2) {
                btn.disabled = false;
                btn.innerText = 'Compare Now';
                btn.classList.remove('bg-slate-800', 'text-slate-500', 'cursor-not-allowed');
                btn.classList.add('bg-indigo-600', 'text-white', 'hover:bg-indigo-500', 'shadow-[0_10px_30px_rgba(79,70,229,0.4)]');
            } else {
                btn.disabled = true;
                btn.innerText = checkedCount === 1 ? 'Select 1 More' : 'Select 2 Candidates';
                btn.classList.add('bg-slate-800', 'text-slate-500', 'cursor-not-allowed');
                btn.classList.remove('bg-indigo-600', 'text-white', 'hover:bg-indigo-500', 'shadow-[0_10px_30px_rgba(79,70,229,0.4)]');
            }
        }
    </script>
</x-app-layout>