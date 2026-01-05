<section class="relative p-6 sm:p-8 bg-slate-900/60 backdrop-blur-xl border border-rose-500/30 rounded-3xl shadow-xl overflow-hidden space-y-6">
    
    {{-- Decorative Glow (Rose/Merah untuk Danger Zone) --}}
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-rose-500/10 blur-[80px] rounded-full pointer-events-none"></div>

    <header class="relative z-10">
        <h2 class="text-xl font-bold text-rose-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-2 text-sm text-slate-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <div class="relative z-10">
        <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >{{ __('Delete Account') }}</x-danger-button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="rounded-2xl border border-slate-700/70 bg-slate-900/95 p-6 shadow-xl shadow-slate-950/70">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-slate-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-slate-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 rounded-xl border border-slate-700/80 bg-slate-950/70 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all duration-150"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-xs text-rose-400" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button 
                    x-on:click="$dispatch('close')"
                    class="inline-flex items-center justify-center rounded-full border border-slate-600 bg-slate-950/80 px-5 py-2.5 text-sm font-semibold text-slate-100 hover:border-slate-500 hover:bg-slate-900 transition-all duration-150"
                >
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="inline-flex items-center justify-center rounded-full bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-rose-600/30 hover:bg-rose-500 transition-all duration-150">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>