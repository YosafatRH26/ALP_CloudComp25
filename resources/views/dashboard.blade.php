<x-app-layout>
    <div class="min-h-screen bg-slate-950 text-slate-100">
        {{-- Background Decoration --}}
        <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-600/10 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[10%] right-[-5%] w-[30%] h-[30%] bg-sky-600/10 blur-[120px] rounded-full"></div>
        </div>

        <div class="relative z-10 py-12 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">
            
            {{-- Header & Greeting --}}
            <div class="mb-10">
                <h1 class="text-3xl font-extrabold tracking-tight text-white">
                    Selamat Datang, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-sky-400">{{ explode(' ', auth()->user()->name)[0] }}!</span>
                </h1>
                <p class="text-slate-400 mt-2">Siap untuk mengoptimalkan CV kamu hari ini?</p>
            </div>

            {{-- Main Action Cards --}}
            <div class="grid md:grid-cols-2 gap-8 mb-12">
                {{-- Analyze CV Card --}}
                <a href="{{ route('cv.create') }}" 
                   class="group relative overflow-hidden p-8 rounded-3xl bg-slate-900 border border-slate-800 hover:border-indigo-500/50 transition-all duration-300 shadow-2xl">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-32 h-32 text-indigo-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-indigo-600/20 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-2">Analyze New CV</h2>
                        <p class="text-slate-400 leading-relaxed">Gunakan AI untuk membedah kualitas CV kamu dan dapatkan skor ATS secara instan.</p>
                        
                        <div class="mt-6 flex items-center text-indigo-400 font-semibold group-hover:translate-x-2 transition-transform">
                            Mulai Analisis <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </div>
                    </div>
                </a>

                {{-- History CV Card --}}
                <a href="{{ route('cv.history') }}" 
                   class="group relative overflow-hidden p-8 rounded-3xl bg-slate-900 border border-slate-800 hover:border-slate-600 transition-all duration-300 shadow-2xl">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-32 h-32 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>

                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-slate-800 rounded-2xl flex items-center justify-center mb-6 border border-slate-700 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-2">Riwayat Analisis</h2>
                        <p class="text-slate-400 leading-relaxed">Lihat kembali hasil analisis sebelumnya dan pantau progres peningkatan CV kamu.</p>
                        
                        <div class="mt-6 flex items-center text-slate-400 font-semibold group-hover:translate-x-2 transition-transform">
                            Lihat History <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                {{-- Stat 1 --}}
                <div class="p-6 rounded-2xl bg-slate-900/50 backdrop-blur-xl border border-slate-800">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Total Analisis</p>
                    <div class="flex items-end gap-2">
                        <span class="text-4xl font-black text-white">{{ auth()->user()->cvSubmissions()->count() }}</span>
                        <span class="text-slate-500 mb-1 text-sm font-medium">Dokumen</span>
                    </div>
                </div>

                {{-- Stat 2 --}}
                <div class="p-6 rounded-2xl bg-slate-900/50 backdrop-blur-xl border border-slate-800">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Skor Terakhir</p>
                    <div class="flex items-end gap-2">
                        @php 
                            $lastScore = optional(auth()->user()->cvSubmissions()->latest()->first()?->analysis)->resume_score ?? 0;
                        @endphp
                        <span class="text-4xl font-black {{ $lastScore >= 70 ? 'text-emerald-400' : ($lastScore >= 40 ? 'text-amber-400' : 'text-rose-400') }}">
                            {{ $lastScore ?: '-' }}
                        </span>
                        <span class="text-slate-500 mb-1 text-sm font-medium">/ 100</span>
                    </div>
                </div>

                {{-- Stat 3 --}}
                <div class="p-6 rounded-2xl bg-slate-900/50 backdrop-blur-xl border border-slate-800">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Upload Terakhir</p>
                    <div class="flex items-end gap-2">
                        <span class="text-xl font-bold text-white">
                            {{ optional(auth()->user()->cvSubmissions()->latest()->first())->created_at?->diffForHumans() ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>