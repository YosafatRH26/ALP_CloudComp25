<nav x-data="{ open: false }"
     class="bg-slate-900/80 backdrop-blur border-b border-slate-700 sticky top-0 z-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Left --}}
            <div class="flex items-center gap-8">
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
                   class="text-lg font-semibold text-sky-400">
                    CareerLens.AI
                </a>

                <div class="hidden sm:flex gap-6">
                    {{-- Dashboard user --}}
                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('dashboard') }}"
                           class="text-sm {{ request()->routeIs('dashboard') ? 'text-sky-400' : 'text-slate-300' }}">
                            Dashboard
                        </a>
                    @endif

                    {{-- Admin links --}}
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="text-sm {{ request()->routeIs('admin.dashboard') ? 'text-sky-400' : 'text-slate-300' }}">
                            Admin Panel
                        </a>

                    
                    @endif
                </div>
            </div>

            {{-- Right --}}
            <div class="hidden sm:flex items-center gap-4">
                <span class="text-sm text-slate-300">
                    {{ Auth::user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-400 hover:text-red-300">
                        Logout
                    </button>
                </form>
            </div>

            {{-- Mobile menu button --}}
            <button @click="open = !open"
                class="sm:hidden text-slate-400 hover:text-sky-400">
                ☰
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open" class="sm:hidden border-t border-slate-700 px-4 py-2 space-y-2">
        {{-- User menu --}}
        @if(auth()->user()->role === 'user')
            <a href="{{ route('dashboard') }}" class="block text-slate-300">Dashboard</a>
        @endif

        {{-- Admin menu --}}
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="block text-slate-300">Admin Panel</a>
            
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="block text-red-400">Logout</button>
        </form>
    </div>
</nav>
