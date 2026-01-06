<nav x-data="{ open: false }" class="bg-slate-900/80 backdrop-blur border-b border-slate-700 sticky top-0 z-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Left Side --}}
            <div class="flex items-center gap-8">
                {{-- Logo --}}
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
                   class="text-lg font-semibold text-sky-400">
                    CareerLens.AI
                </a>

                {{-- Desktop Navigation --}}
                <div class="hidden sm:flex gap-6">
                    
                    {{-- 1. Dashboard User --}}
                    @if(auth()->user()->role === 'user')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endif

                    {{-- 2. Menu Khusus Admin --}}
                    @if(auth()->user()->role === 'admin')
                        {{-- Admin Panel --}}
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Admin Panel') }}
                        </x-nav-link>

                        {{-- Compare CV (Menu Baru) --}}
                        <x-nav-link :href="route('admin.cv.selection')" :active="request()->routeIs('admin.cv.selection')">
                            {{ __('Compare CV') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            {{-- Right Side (User Profile) --}}
            <div class="hidden sm:flex items-center gap-4">
                <span class="text-sm text-slate-300">
                    {{ Auth::user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-400 hover:text-red-300 transition-colors">
                        Logout
                    </button>
                </form>
            </div>

            {{-- Mobile menu button --}}
            <button @click="open = !open" class="sm:hidden text-slate-400 hover:text-sky-400 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu (Dropdown) --}}
    <div x-show="open" class="sm:hidden border-t border-slate-700 px-4 py-2 space-y-2 bg-slate-900">
        
        {{-- User Menu --}}
        @if(auth()->user()->role === 'user')
            <a href="{{ route('dashboard') }}" class="block py-2 text-slate-300 hover:text-sky-400">Dashboard</a>
        @endif

        {{-- Admin Menu --}}
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="block py-2 text-slate-300 hover:text-sky-400">Admin Panel</a>
            <a href="{{ route('admin.cv.selection') }}" class="block py-2 text-slate-300 hover:text-sky-400">Compare CV</a>
        @endif

        <div class="border-t border-slate-800 my-2 pt-2">
            <div class="text-xs text-slate-500 mb-1 px-1">{{ Auth::user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="block w-full text-left py-2 text-red-400 hover:text-red-300">Logout</button>
            </form>
        </div>
    </div>
</nav>