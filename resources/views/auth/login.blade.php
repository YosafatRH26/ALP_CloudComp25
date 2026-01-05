<x-guest-layout>
    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-50 mb-2">Welcome Back</h2>
        <p class="text-slate-400">Sign in to continue to CareerLens</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
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
                          autocomplete="username"
                          placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" 
                          class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required 
                          autocomplete="current-password"
                          placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" 
                       type="checkbox" 
                       class="h-4 w-4 rounded border-slate-600 bg-slate-950/50 text-sky-500 focus:ring-sky-400/20 focus:ring-offset-0" 
                       name="remember">
                <span class="ms-2 text-sm text-slate-300">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-sky-400 hover:text-sky-300 font-medium transition-colors" 
                   href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <x-primary-button>
            {{ __('Sign In') }}
        </x-primary-button>

        <!-- Divider -->
        <div class="relative my-8">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-700"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-slate-900/70 text-slate-400">{{ __('New to CareerLens?') }}</span>
            </div>
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div>
                <a href="{{ route('register') }}"
                   class="block w-full rounded-lg border border-slate-600 px-4 py-3 text-center text-base font-semibold text-slate-200 hover:border-sky-400 hover:bg-slate-800 transition-all">
                    {{ __('Create an Account') }}
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>