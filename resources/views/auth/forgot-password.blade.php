<x-guest-layout>
    {{-- Header --}}
    <div class="mb-8">
        <div class="inline-flex h-14 w-14 items-center justify-center rounded-xl bg-sky-500/20 border border-sky-500/30 mb-4">
            <svg class="h-7 w-7 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
        </div>
        <h2 class="text-3xl font-bold text-slate-50 mb-2">Forgot Password?</h2>
        <p class="text-slate-400">No problem. We'll send you a reset link.</p>
    </div>

    <div class="mb-6 rounded-xl border border-slate-700/80 bg-slate-950/60 px-4 py-3 text-sm text-slate-300">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" 
                          class="block mt-1 w-full" 
                          type="email" 
                          name="email" 
                          :value="old('email')" 
                          required 
                          autofocus
                          placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button>
            {{ __('Email Password Reset Link') }}
        </x-primary-button>

        <!-- Back to Login -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-sky-400 hover:text-sky-300 font-medium transition-colors">
                {{ __('Back to Login') }}
            </a>
        </div>
    </form>
</x-guest-layout>