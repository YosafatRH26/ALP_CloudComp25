<x-app-layout>
    <div class="min-h-screen bg-slate-950 text-slate-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-bold text-white">Select <span class="text-indigo-400">Candidates</span></h1>
                    <p class="text-slate-400 mt-2">Pilih tepat 2 kandidat untuk dibandingkan.</p>
                </div>
                <div class="text-sm text-slate-500 bg-slate-900 px-4 py-2 rounded-lg border border-slate-800">
                    Total: <span class="text-indigo-400 font-bold">{{ count($candidates) }}</span> Candidates
                </div>
            </div>

            <form action="{{ route('admin.cv.compare') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($candidates as $candidate)
                        <div class="relative group">
                            {{-- Checkbox Asli --}}
                            <input type="checkbox" name="cv_ids[]" value="{{ $candidate->id }}" 
                                   id="cv-{{ $candidate->id }}"
                                   class="peer hidden candidate-checkbox"
                                   onchange="checkSelection()">
                            
                            {{-- Card UI --}}
                            <label for="cv-{{ $candidate->id }}" 
                                   class="block bg-slate-900/50 border-2 border-slate-800 rounded-3xl p-6 cursor-pointer transition-all peer-checked:border-indigo-500 peer-checked:bg-indigo-500/5 hover:border-slate-700">
                                
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-12 h-12 rounded-xl bg-slate-800 flex items-center justify-center font-bold text-sky-400 border border-slate-700">
                                        {{ substr($candidate->cvSubmission->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-white font-bold truncate">{{ $candidate->cvSubmission->user->name }}</h3>
                                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">{{ $candidate->cvSubmission->analysis_mode }}</p>
                                    </div>
                                    {{-- Indicator centang --}}
                                    <div class="w-6 h-6 rounded-full border-2 border-slate-700 peer-checked:bg-indigo-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                </div>

                                <div class="flex justify-between items-end pt-4 border-t border-slate-800/50">
                                    <div>
                                        <p class="text-[10px] text-slate-500 uppercase font-bold">Score</p>
                                        <p class="text-2xl font-black text-white">{{ number_format($candidate->resume_score, 1) }}</p>
                                    </div>
                                    <p class="text-[10px] text-slate-500">{{ $candidate->created_at->format('d M Y') }}</p>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>

                {{-- Submit Button (Sticky di bawah) --}}
                <div class="fixed bottom-10 left-1/2 -translate-x-1/2 z-50">
                    <button type="submit" id="compareBtn" disabled 
                            class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-4 px-10 rounded-2xl shadow-[0_10px_40px_rgba(79,70,229,0.4)] transition-all disabled:opacity-50 disabled:bg-slate-800 disabled:shadow-none">
                        Compare Selected (0/2)
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function checkSelection() {
            const checked = document.querySelectorAll('.candidate-checkbox:checked');
            const btn = document.getElementById('compareBtn');
            const count = checked.length;

            btn.innerText = `Compare Selected (${count}/2)`;
            
            // Aktifkan tombol hanya jika tepat 2 terpilih
            if (count === 2) {
                btn.disabled = false;
                btn.classList.add('scale-105');
            } else {
                btn.disabled = true;
                btn.classList.remove('scale-105');
            }
        }
    </script>
</x-app-layout>