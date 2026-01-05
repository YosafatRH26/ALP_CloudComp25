<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 relative overflow-hidden">
        
        {{-- Animated Background Glow --}}
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-sky-600/10 blur-[120px] rounded-full pointer-events-none animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-emerald-600/10 blur-[120px] rounded-full pointer-events-none animate-pulse" style="animation-delay: 1s;"></div>

        <div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8 relative">
            
            {{-- Header --}}
            <div class="mb-10">
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-3">
                    Compare CV (Admin)
                    <span class="inline-flex items-center ml-3 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-sm font-medium">
                        Admin Tool
                    </span>
                </h1>
                <p class="text-slate-400 max-w-2xl">
                    Pilih dua berkas CV dari database untuk membandingkan skor dan kualifikasi secara mendalam.
                </p>
            </div>

            {{-- Selection Form --}}
            <form action="{{ route('admin.cv.compare') }}" method="POST">
                @csrf
                
                <div class="grid md:grid-cols-2 gap-8 mb-10">
                    
                    {{-- CV A SELECTION CARD --}}
                    <div class="group rounded-3xl bg-slate-900/60 backdrop-blur-xl border border-slate-700/60 p-8 shadow-xl hover:border-sky-500/50 transition-all duration-300">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-sky-500 to-cyan-400 flex items-center justify-center shadow-lg shadow-sky-500/30">
                                <span class="text-xl font-bold text-white">A</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-sky-400">CV A</h3>
                                <p class="text-xs text-slate-500 uppercase tracking-widest">Pilih CV Pertama</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-slate-300">Daftar Analisis CV</label>
                            <div class="relative">
                                <select name="cv_a" required class="w-full rounded-xl bg-slate-950/60 border border-slate-700 text-slate-200 py-3.5 px-4 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 transition-all appearance-none cursor-pointer">
                                    @foreach ($analysis as $cv)
                                        <option value="{{ $cv->id }}">
                                            {{ $cv->cvSubmission->original_filename }} - {{ $cv->cvSubmission->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CV B SELECTION CARD --}}
                    <div class="group rounded-3xl bg-slate-900/60 backdrop-blur-xl border border-slate-700/60 p-8 shadow-xl hover:border-emerald-500/50 transition-all duration-300">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-green-400 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                <span class="text-xl font-bold text-white">B</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-emerald-400">CV B</h3>
                                <p class="text-xs text-slate-500 uppercase tracking-widest">Pilih CV Kedua</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-slate-300">Daftar Analisis CV</label>
                            <div class="relative">
                                <select name="cv_b" required class="w-full rounded-xl bg-slate-950/60 border border-slate-700 text-slate-200 py-3.5 px-4 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all appearance-none cursor-pointer">
                                    @foreach ($analysis as $cv)
                                        <option value="{{ $cv->id }}">
                                            {{ $cv->cvSubmission->original_filename }} - {{ $cv->cvSubmission->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Action Button --}}
                <div class="flex justify-center">
                    <button type="submit" class="group relative inline-flex items-center gap-3 px-10 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-2xl transition-all shadow-xl shadow-emerald-900/20 hover:-translate-y-1 active:scale-95">
                        <svg class="w-6 h-6 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Compare CV Now
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>