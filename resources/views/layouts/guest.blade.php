<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CareerLens') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-slate-100 antialiased">
<div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 flex flex-col">

    {{-- Header --}}
    <header class="py-6 px-6">
        <a href="/" class="flex items-center gap-2">
            <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-sky-400 to-cyan-300 flex items-center justify-center">
                <svg class="h-6 w-6 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="text-xl font-semibold">CareerLens<span class="text-sky-400">.AI</span></span>
        </a>
    </header>

    {{-- Main --}}
    <main class="flex-1 flex items-center justify-center px-4">
        <div class="w-full max-w-6xl grid lg:grid-cols-2 gap-10 items-center">

            {{-- Left marketing --}}
            <div class="hidden lg:block space-y-6">
                <h1 class="text-4xl font-bold leading-tight">
                    Analyze Your CV with <span class="text-sky-400">AI</span>
                </h1>
                <p class="text-slate-300">
                    Get division recommendations, readiness score, and professional feedback.
                </p>
            </div>

            {{-- Right slot --}}
            <div class="rounded-2xl bg-slate-900/70 border border-slate-700 p-8 backdrop-blur">
                {{ $slot }}
            </div>

        </div>
    </main>

    {{-- Footer --}}
    <footer class="text-center text-xs text-slate-500 py-4">
        © {{ date('Y') }} CareerLens.AI
    </footer>

</div>
</body>
</html>
