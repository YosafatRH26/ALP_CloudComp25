<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 relative overflow-hidden py-12">
        
        {{-- Decorative Glow --}}
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-sky-500/10 blur-[150px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-purple-500/10 blur-[150px] rounded-full pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Header --}}
            <div class="text-center mb-10">
                <span class="px-3 py-1 rounded-full bg-sky-500/10 border border-sky-500/20 text-sky-400 text-[10px] font-bold uppercase tracking-wider">
                    AI Analysis Engine v2.0
                </span>
                <h1 class="mt-4 text-3xl font-extrabold text-white tracking-tight sm:text-4xl">
                    Upload & Analyze <span class="text-sky-400">CV</span>
                </h1>
                <p class="mt-3 text-slate-400 text-sm max-w-lg mx-auto">
                    Biarkan AI menganalisis resume Anda, menemukan celah skill, dan memberikan rekomendasi karir yang spesifik.
                </p>
            </div>

            {{-- ERROR & SUCCESS ALERTS (Bagian Penting untuk Debugging) --}}
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-start gap-3">
                    <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <span class="font-bold block text-sm mb-1">Analisis Gagal</span>
                        <p class="text-xs leading-relaxed text-rose-300">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400">
                    <span class="font-bold block text-sm mb-2">Periksa Inputan Anda:</span>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Upload Card --}}
            <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800 rounded-3xl p-8 shadow-2xl">
                <form action="{{ route('cv.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- File Input --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-300 mb-2">Upload File CV (PDF)</label>
                        <div class="relative border-2 border-dashed border-slate-700 rounded-2xl p-8 transition-all hover:border-sky-500 group bg-slate-950/30 text-center cursor-pointer" onclick="document.getElementById('cv_file').click()">
                            <input type="file" name="cv" id="cv_file" class="hidden" accept=".pdf" onchange="document.getElementById('file_name').innerText = this.files[0].name">
                            
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center mb-4 group-hover:bg-sky-500/20 group-hover:text-sky-400 transition-colors text-slate-500">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <p class="text-sm font-medium text-slate-300 group-hover:text-sky-400 transition-colors">
                                    Klik untuk pilih file
                                </p>
                                <p class="text-xs text-slate-500 mt-1">PDF Only (Max 10MB)</p>
                                <p id="file_name" class="mt-4 text-sm font-bold text-emerald-400"></p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Language Select --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Bahasa CV</label>
                            <select name="language" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-slate-200 outline-none">
                                <option value="id">Indonesia (Bahasa)</option>
                                <option value="en">English (Inggris)</option>
                            </select>
                        </div>

                        {{-- Mode Select --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tujuan Analisis</label>
                            <select name="analysis_type" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-slate-200 outline-none">
                                <option value="professional">Profesional (Lamaran Kerja)</option>
                                <option value="kepanitiaan">Kepanitiaan (Organisasi Kampus)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-bold py-4 rounded-2xl shadow-lg shadow-sky-900/20 transform hover:scale-[1.01] transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            Mulai Analisis AI
                        </button>
                        <p class="text-center text-[10px] text-slate-500 mt-3">
                            Proses analisis memerlukan waktu sekitar 5-10 detik. Mohon tunggu.
                        </p>
                    </div>
                </form>
            </div>

            <div class="text-center mt-8">
                 <a href="{{ route('dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-300 transition-colors">
                    &larr; Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>
</x-app-layout>