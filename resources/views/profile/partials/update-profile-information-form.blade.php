<section class="relative p-6 sm:p-8 bg-slate-900/60 backdrop-blur-xl border border-slate-700/50 rounded-3xl shadow-xl overflow-hidden">
    
    {{-- Decorative Glow (Background) --}}
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-sky-500/10 blur-[80px] rounded-full pointer-events-none"></div>

    <header class="relative z-10">
        <h2 class="text-xl font-bold text-slate-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-2 text-sm text-slate-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-6 relative z-10">
        @csrf
        @method('patch')

        {{-- Name Input --}}
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-sm font-medium text-slate-300 mb-2" />
            <x-text-input 
                id="name" 
                name="name" 
                type="text" 
                class="mt-1 block w-full rounded-xl border border-slate-700/80 bg-slate-950/70 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all duration-150" 
                :value="old('name', $user->name)" 
                required 
                autofocus 
                autocomplete="name" 
            />
            <x-input-error class="mt-2 text-xs text-rose-400" :messages="$errors->get('name')" />
        </div>

        {{-- Email Input --}}
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-slate-300 mb-2" />
            <x-text-input 
                id="email" 
                name="email" 
                type="email" 
                class="mt-1 block w-full rounded-xl border border-slate-700/80 bg-slate-950/70 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all duration-150" 
                :value="old('email', $user->email)" 
                required 
                autocomplete="username" 
            />
            <x-input-error class="mt-2 text-xs text-rose-400" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 rounded-xl bg-rose-950/30 border border-rose-500/30">
                    <p class="text-sm text-rose-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-rose-400 hover:text-rose-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-rose-500 transition-colors">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-400 font-medium flex items-center gap-1"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>