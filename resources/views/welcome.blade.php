<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CareerLens.AI - Smart CV Analysis</title>
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
                100% { transform: translateY(0px); }
            }
            .animate-float { animation: float 3s ease-in-out infinite; }
            .glass-nav { backdrop-filter: blur(12px); background-color: rgba(15, 23, 42, 0.8); }
        </style>
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100 selection:bg-sky-500/30">
        
        {{-- Sticky Navbar --}}
        <header class="fixed top-0 w-full z-50 border-b border-slate-800/50 glass-nav">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <div class="flex items-center gap-2 group cursor-pointer">
                        <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-sky-400 to-cyan-300 flex items-center justify-center group-hover:rotate-12 transition-transform shadow-lg shadow-sky-500/20">
                            <svg class="h-5 w-5 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-white">CareerLens<span class="text-sky-400">.AI</span></span>
                    </div>

                    <div class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-400">
                        <a href="#features" class="hover:text-sky-400 transition-colors">Fitur</a>
                        <a href="#how-it-works" class="hover:text-sky-400 transition-colors">Cara Kerja</a>
                    </div>

                    <div class="flex items-center gap-3">
                        @auth
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}"
           class="px-5 py-2.5 rounded-full bg-slate-800 text-sm font-semibold hover:bg-slate-700 transition-all border border-slate-700">
            Admin Dashboard
        </a>
    @else
        <a href="{{ route('dashboard') }}"
           class="px-5 py-2.5 rounded-full bg-slate-800 text-sm font-semibold hover:bg-slate-700 transition-all border border-slate-700">
            Dashboard
        </a>
    @endif

                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-300 hover:text-white transition-colors">Login</a>
                            <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-full bg-sky-500 text-sm font-semibold text-white shadow-lg shadow-sky-500/20 hover:bg-sky-400 hover:-translate-y-0.5 transition-all">Daftar Gratis</a>
                        @endauth
                    </div>
                </div>
            </nav>
        </header>

        <main class="pt-32 pb-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                {{-- Hero Section --}}
                <div class="text-center mb-24 relative">
                    {{-- Decorative Glow --}}
                    <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-sky-500/20 blur-[120px] rounded-full pointer-events-none"></div>

                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-sky-500/10 border border-sky-500/20 text-sky-400 text-xs font-bold tracking-widest uppercase mb-8 animate-float">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-500"></span>
                        </span>
                        New: AI Model v2.4 Is Live
                    </div>

                    <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tight mb-8 leading-[1.1]">
                        Ubah CV-mu Menjadi <br/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 via-cyan-300 to-emerald-400">Magnet Karir</span>
                    </h1>
                    
                    <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                        Analisis CV cerdas berbasis AI untuk membedah potensi terbaikmu dan mencocokkannya dengan divisi impian dalam hitungan detik.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        @auth
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}"
           class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-slate-700 text-lg font-bold text-white">
            Masuk Dashboard Admin
        </a>
    @else
        <a href="{{ route('cv.create') }}"
           class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-sky-500 text-lg font-bold text-white shadow-2xl shadow-sky-500/30 hover:bg-sky-400 hover:scale-105 transition-all">
            Mulai Analisis Sekarang
        </a>
    @endif
@else
    <a href="{{ route('register') }}"
       class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-sky-500 text-lg font-bold text-white shadow-2xl shadow-sky-500/30 hover:bg-sky-400 hover:scale-105 transition-all">
        Mulai Analisis Sekarang — Gratis
    </a>
@endauth

                    </div>

                    {{-- Hero Image/Preview placeholder --}}
                    <div class="mt-20 relative max-w-5xl mx-auto group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-sky-500 to-emerald-500 rounded-[2rem] blur opacity-20 group-hover:opacity-40 transition duration-1000"></div>
                        <div class="relative bg-slate-900 border border-slate-800 rounded-[2rem] overflow-hidden shadow-2xl">
                            <div class="flex items-center gap-2 px-6 py-4 border-b border-slate-800 bg-slate-900/50">
                                <div class="flex gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-rose-500/50"></div>
                                    <div class="w-3 h-3 rounded-full bg-amber-500/50"></div>
                                    <div class="w-3 h-3 rounded-full bg-emerald-500/50"></div>
                                </div>
                                <div class="mx-auto text-xs text-slate-500 font-medium tracking-widest uppercase">AI Analysis Dashboard Preview</div>
                            </div>
                            <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=80" alt="Dashboard Preview" class="w-full opacity-60 mix-blend-luminosity hover:opacity-100 transition-opacity duration-700">
                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 mb-32">
                    @foreach([['95%', 'Akurasi AI'], ['10k+', 'User Terdaftar'], ['<30s', 'Waktu Analisis'], ['24/7', 'Akses Kapanpun']] as $stat)
                    <div class="p-8 rounded-3xl bg-slate-900/40 border border-slate-800/50 text-center hover:border-slate-700 transition-colors group">
                        <div class="text-3xl font-black text-white mb-1 group-hover:text-sky-400 transition-colors">{{ $stat[0] }}</div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ $stat[1] }}</div>
                    </div>
                    @endforeach
                </div>

                {{-- Features --}}
                <section id="features" class="mb-32">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold text-white mb-4">Fitur Terintegrasi</h2>
                        <p class="text-slate-400">Teknologi yang dirancang untuk mempercepat karirmu.</p>
                    </div>
                    <div class="grid md:grid-cols-3 gap-8">
                        {{-- Kartu Fitur bisa menggunakan komponen yang sama dengan iterasi warna --}}
                        <div class="p-8 rounded-3xl bg-slate-900/60 border border-slate-800 hover:border-sky-500/50 transition-all group">
                            <div class="w-12 h-12 rounded-2xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">Instant ATS Scoring</h3>
                            <p class="text-slate-400 text-sm leading-relaxed">Cek skor CV kamu terhadap standar sistem ATS industri modern dalam hitungan detik.</p>
                        </div>
                        {{-- Tambahkan fitur lainnya di sini --}}
                    </div>
                </section>

                {{-- How It Works dengan Visual Line --}}
                <section id="how-it-works" class="mb-32 relative">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold text-white mb-4">Cara Kerja</h2>
                    </div>
                    <div class="grid md:grid-cols-3 gap-12 relative">
                        {{-- Garis penghubung (Desktop saja) --}}
                        <div class="hidden md:block absolute top-12 left-[15%] right-[15%] h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
                        
                        @php $steps = [
                            ['1', 'Upload', 'Unggah file PDF CV kamu ke sistem kami.'],
                            ['2', 'Analyze', 'AI kami membedah setiap kalimat & skill.'],
                            ['3', 'Excel', 'Terima rekomendasi & mulai melamar!']
                        ] @endphp

                        @foreach($steps as $step)
                        <div class="text-center relative">
                            <div class="w-24 h-24 rounded-full bg-slate-900 border-4 border-slate-800 flex items-center justify-center mx-auto mb-6 relative z-10 shadow-xl group-hover:border-sky-500 transition-colors">
                                <span class="text-2xl font-black text-sky-400">{{ $step[0] }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">{{ $step[1] }}</h3>
                            <p class="text-slate-400 text-sm">{{ $step[2] }}</p>
                        </div>
                        @endforeach
                    </div>
                </section>

            </div>
        </main>

        {{-- Footer --}}
        <footer class="border-t border-slate-900 pt-16 pb-8 bg-slate-950">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <div class="flex items-center justify-center gap-2 mb-8">
                    <div class="h-8 w-8 rounded-lg bg-sky-500 flex items-center justify-center font-bold text-slate-900">C</div>
                    <span class="text-lg font-bold">CareerLens<span class="text-sky-400">.AI</span></span>
                </div>
                <p class="text-slate-500 text-sm mb-8">Empowering professionals with AI-driven career insights.</p>
                <div class="flex justify-center gap-6 text-slate-400 text-sm mb-12">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">Contact</a>
                </div>
                <div class="text-slate-600 text-xs tracking-widest uppercase">
                    © {{ date('Y') }} CareerLens.AI — Build with ❤️ in Indonesia
                </div>
            </div>
        </footer>

    </body>
</html>