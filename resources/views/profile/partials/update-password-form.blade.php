<section class="relative p-6 sm:p-8 bg-slate-900/60 backdrop-blur-xl border border-slate-700/50 rounded-3xl shadow-xl overflow-hidden">
    
    {{-- Decorative Glow (Bedakan posisi dengan card atas biar estetis) --}}
    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-500/10 blur-[80px] rounded-full pointer-events-none"></div>

    <header class="relative z-10">
        <h2 class="text-xl font-bold text-slate-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-2 text-sm text-slate-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-8 space-y-6 relative z-10">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-sm font-medium text-slate-300 mb-2" />
            <x-text-input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                class="mt-1 block w-full rounded-xl border border-slate-700/80 bg-slate-950/70 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all duration-150" 
                autocomplete="current-password" 
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-xs text-rose-400" />
        </div>

        {{-- New Password --}}
        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-sm font-medium text-slate-300 mb-2" />
            <x-text-input 
                id="update_password_password" 
                name="password" 
                type="password" 
                class="mt-1 block w-full rounded-xl border border-slate-700/80 bg-slate-950/70 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all duration-150" 
                autocomplete="new-password" 
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-xs text-rose-400" />
        </div>

        {{-- Confirm Password --}}
        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-slate-300 mb-2" />
            <x-text-input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                type="password" 
                class="mt-1 block w-full rounded-xl border border-slate-700/80 bg-slate-950/70 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all duration-150" 
                autocomplete="new-password" 
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-xs text-rose-400" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-400 font-medium flex items-center gap-1"
                >
                    {{-- Icon Centang Kecil --}}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>