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

<body class="font-sans antialiased text-slate-100">
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950">

        {{-- Navigation --}}
        @include('layouts.navigation')

        {{-- Page Content --}}
        <main class="py-8 px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
