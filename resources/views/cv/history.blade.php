<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 relative overflow-hidden">
        
        {{-- Animated Background Glow --}}
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-sky-600/10 blur-[120px] rounded-full pointer-events-none animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-emerald-600/10 blur-[120px] rounded-full pointer-events-none animate-pulse" style="animation-delay: 1s;"></div>

        <div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- NOTIFICATION ALERT (Flash Message) --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition 
                     class="mb-8 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-between shadow-lg shadow-emerald-900/20">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="h-px w-8 bg-sky-500"></span>
                        <p class="text-xs font-bold tracking-[0.3em] text-sky-400 uppercase">
                            CareerLens.AI
                        </p>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                        CV Evolution <span class="text-slate-500">History</span>
                    </h1>
                    <p class="mt-2 text-slate-400 max-w-md">
                        Pantau progres resume Anda, bandingkan skor antar versi, dan kirimkan kualifikasi terbaik Anda ke Admin.
                    </p>
                </div>

                <a href="{{ route('cv.create') }}"
                   class="relative z-20 group inline-flex items-center justify-center rounded-2xl bg-sky-600 px-6 py-3 text-sm font-bold text-white shadow-xl shadow-sky-900/20 hover:bg-sky-500 hover:-translate-y-1 transition-all active:scale-95">
                    <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Analyze CV Baru
                </a>
            </div>

            {{-- History List --}}
            <div class="grid gap-6">
                @forelse($histories as $index => $h)
                    <div class="group relative rounded-3xl border border-slate-800 bg-slate-900/40 p-6 backdrop-blur-xl transition-all duration-300 hover:border-slate-600 hover:bg-slate-900/60 shadow-xl overflow-hidden">
                        
                        {{-- Background Decoration (FIXED: pointer-events-none agar tidak menghalangi klik) --}}
                        <div class="absolute top-0 right-0 p-8 opacity-[0.03] group-hover:opacity-[0.07] transition-opacity pointer-events-none">
                            <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/></svg>
                        </div>

                        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                            
                            {{-- Info Section --}}
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400 font-bold">
                                    #{{ $histories->count() - $index }}
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-lg font-bold text-slate-100 truncate pr-10">
                                        {{ $h->cvSubmission->original_filename }}
                                    </h3>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-2">
                                        <span class="flex items-center text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            {{ $h->created_at->format('d M Y • H:i') }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-sky-500/10 text-sky-400 border border-sky-500/20">
                                            {{ $h->cvSubmission->input_mode }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Action Section --}}
                            <div class="flex flex-wrap items-center gap-3 lg:pl-6 lg:border-l lg:border-slate-800">
                                
                                {{-- Score --}}
                                <div class="mr-4 text-center">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Score</p>
                                    <p class="text-2xl font-black text-emerald-400">{{ $h->resume_score }}<span class="text-xs text-slate-600 ml-0.5">/100</span></p>
                                </div>

                                {{-- Buttons --}}
                                <div class="flex items-center gap-2 relative z-20">
                                    <a href="{{ route('cv.result', $h->id) }}"
                                       class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-200 transition-colors border border-slate-700">
                                        View Result
                                    </a>

                                    <form method="POST" action="{{ route('cv.push-to-admin', $h->cvSubmission->id) }}">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold transition-all
                                                {{ $h->cvSubmission->is_submitted_to_admin 
                                                    ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 cursor-default' 
                                                    : 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-900/20 active:scale-95' }}"
                                                {{ $h->cvSubmission->is_submitted_to_admin ? 'disabled' : '' }}>
                                            @if($h->cvSubmission->is_submitted_to_admin)
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                Pushed
                                            @else
                                                Push to Admin
                                            @endif
                                        </button>
                                    </form>

                                    {{-- DELETE FORM (FIXED: z-index higher) --}}
                                    <form method="POST" action="{{ route('cv.history.delete', $h->id) }}" 
                                          onsubmit="return confirm('Hapus riwayat analisis ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="group/del p-2 rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all border border-rose-500/20">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl border-2 border-dashed border-slate-800 p-12 text-center relative z-10">
                        <div class="w-16 h-16 bg-slate-900 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-800">
                            <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-300">Belum ada riwayat</h3>
                        <p class="text-slate-500 mt-1 max-w-xs mx-auto">Mulai analisis CV pertama Anda dan temukan peluang karir yang lebih baik.</p>
                        <a href="{{ route('cv.create') }}" class="inline-block mt-6 text-sky-400 font-bold hover:text-sky-300 transition-colors">
                            Analyze Sekarang →
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>