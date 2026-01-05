<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 relative overflow-hidden">
        
        {{-- Background Glow --}}
        <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-sky-500/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-purple-500/10 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-3xl mx-auto py-16 px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Header --}}
            <div class="text-center mb-10">
                <span class="px-3 py-1 rounded-full bg-sky-500/10 border border-sky-500/20 text-sky-400 text-[10px] font-bold uppercase tracking-wider">
                    AI Analysis Engine v2.0
                </span>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    Mulai <span class="text-sky-400">Analisis CV</span> Kamu
                </h1>
                <p class="mt-4 text-slate-400 text-sm max-w-lg mx-auto">
                    Upload dokumen PDF kamu dan biarkan AI kami membedah potensi terbaikmu untuk berbagai divisi.
                </p>
            </div>

            {{-- ERROR ALERTS (LOGIC PENTING UNTUK DEBUGGING) --}}
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-start gap-3 shadow-lg shadow-rose-900/10">
                    <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <span class="font-bold block text-sm mb-1">Gagal Memproses</span>
                        <p class="text-xs leading-relaxed text-rose-300">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 shadow-lg shadow-amber-900/10">
                    <span class="font-bold block text-sm mb-2">Periksa Inputan Anda:</span>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM UPLOAD --}}
            <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800 rounded-3xl p-8 shadow-2xl">
                <form action="{{ route('cv.store') }}" method="POST" enctype="multipart/form-data" id="analysis-form" class="space-y-8">
                    @csrf

                    {{-- Upload Zone --}}
                    <div class="group relative">
                        <label for="cv-upload" class="block group-hover:cursor-pointer">
                            <div id="drop-zone" class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-slate-700 rounded-3xl bg-slate-900/40 backdrop-blur-sm transition-all duration-300 hover:border-sky-500/50 hover:bg-slate-900/60 group-hover:shadow-lg group-hover:shadow-sky-500/5">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="p-4 rounded-full bg-slate-800 mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                    </div>
                                    <p class="mb-2 text-sm text-slate-200 font-semibold" id="file-name-display">
                                        Klik atau seret file PDF ke sini
                                    </p>
                                    <p class="text-xs text-slate-500 uppercase tracking-widest">Maksimal 10MB (Hanya PDF)</p>
                                </div>
                                <input id="cv-upload" name="cv" type="file" accept=".pdf" class="hidden" required />
                            </div>
                        </label>
                    </div>

                    {{-- Configuration Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Bahasa --}}
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Bahasa Analisis</label>
                            <div class="relative">
                                <select name="language" required class="w-full appearance-none rounded-2xl bg-slate-950 border border-slate-700 px-4 py-3 text-sm focus:border-sky-500 focus:ring-sky-500 text-slate-200 transition-all outline-none">
                                    <option value="id">🇮🇩 Bahasa Indonesia</option>
                                    <option value="en">🇺🇸 English</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Jenis Analisis --}}
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Fokus Analisis</label>
                            <div class="relative">
                                <select name="analysis_type" required class="w-full appearance-none rounded-2xl bg-slate-950 border border-slate-700 px-4 py-3 text-sm focus:border-sky-500 focus:ring-sky-500 text-slate-200 transition-all outline-none">
                                    <option value="kepanitiaan">🎯 Kepanitiaan Kampus</option>
                                    <option value="professional">💼 Karir Profesional</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-bold text-sm shadow-xl shadow-sky-900/20 transition-all transform hover:scale-[1.01] active:scale-[0.98] flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Mulai Analisis AI
                    </button>
                    
                </form>
            </div>
            
            <div class="text-center mt-8">
                 <a href="{{ route('dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-300 transition-colors">
                    &larr; Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    {{-- ADVANCED LOADING OVERLAY --}}
    <div id="loading-overlay" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/90 backdrop-blur-md transition-opacity duration-300">
        <div class="flex flex-col items-center max-w-xs text-center">
            {{-- Circular Loader --}}
            <div class="relative w-24 h-24 mb-6">
                <div class="absolute inset-0 border-4 border-sky-500/20 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-t-sky-500 rounded-full animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-8 h-8 text-sky-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>

            <h3 class="text-white font-bold text-lg mb-2">AI Sedang Bekerja...</h3>
            <p class="text-slate-400 text-sm leading-relaxed min-h-[40px]" id="loading-text">
                Mengekstrak data dan mencocokkan kualifikasi kamu dengan database divisi.
            </p>
            
            {{-- Progress Indicator --}}
            <div class="mt-8 w-full h-1 bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-sky-500 animate-[loading_20s_ease-in-out_infinite]"></div>
            </div>
        </div>
    </div>

    <style>
        @keyframes loading {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 95%; }
        }
    </style>

    <script>
        const form = document.getElementById('analysis-form');
        const overlay = document.getElementById('loading-overlay');
        const fileInput = document.getElementById('cv-upload');
        const fileNameDisplay = document.getElementById('file-name-display');
        const dropZone = document.getElementById('drop-zone');

        // Visual feedback for file selection
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                fileNameDisplay.innerText = `File terpilih: ${e.target.files[0].name}`;
                fileNameDisplay.classList.remove('text-slate-200');
                fileNameDisplay.classList.add('text-sky-400', 'font-bold');
            }
        });

        // Drag and Drop enhancement
        ['dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        dropZone.addEventListener('dragover', () => dropZone.classList.add('border-sky-500', 'bg-slate-900/80'));
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-sky-500', 'bg-slate-900/80'));
        
        dropZone.addEventListener('drop', (e) => {
            dropZone.classList.remove('border-sky-500', 'bg-slate-900/80');
            const files = e.dataTransfer.files;
            if (files.length > 0 && files[0].type === 'application/pdf') {
                fileInput.files = files;
                fileNameDisplay.innerText = `File terpilih: ${files[0].name}`;
                fileNameDisplay.classList.remove('text-slate-200');
                fileNameDisplay.classList.add('text-sky-400', 'font-bold');
            }
        });

        // Change loading text dynamically
        const messages = [
            "Mengekstrak teks dari PDF...",
            "Membaca struktur pengalaman & skill...",
            "Menganalisis kecocokan divisi...",
            "Menghitung skor ATS & Grammar...",
            "Menyusun feedback personal..."
        ];
        
        let msgIndex = 0;
        
        form.addEventListener('submit', () => {
            // Tampilkan Overlay
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            
            // Loop pesan loading
            setInterval(() => {
                msgIndex = (msgIndex + 1) % messages.length;
                const txt = document.getElementById('loading-text');
                // Efek fade out dikit (opsional, tapi biar halus langsung ganti aja)
                txt.innerText = messages[msgIndex];
            }, 3000);
        });
    </script>
</x-app-layout>