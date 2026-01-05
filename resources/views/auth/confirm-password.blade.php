<x-guest-layout>
    {{-- Header --}}
    <div class="mb-8">
        <div class="inline-flex h-14 w-14 items-center justify-center rounded-xl bg-amber-500/20 border border-amber-500/30 mb-4">
            <svg class="h-7 w-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h2 class="text-3xl font-bold text-slate-50 mb-2">Secure Area</h2>
        <p class="text-slate-400">Please confirm your password to continue</p>
    </div>

    <div class="mb-6 rounded-xl border border-amber-500/40 bg-amber-900/20 px-4 py-3 text-sm text-amber-100">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

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

        <x-primary-button>
            {{ __('Confirm') }}
        </x-primary-button>
    </form>
</x-guest-layout>