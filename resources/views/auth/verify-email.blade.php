<x-guest-layout>
    {{-- Header --}}
    <div class="mb-8">
        <div class="inline-flex h-14 w-14 items-center justify-center rounded-xl bg-emerald-500/20 border border-emerald-500/30 mb-4">
            <svg class="h-7 w-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        <h2 class="text-3xl font-bold text-slate-50 mb-2">Verify Your Email</h2>
        <p class="text-slate-400">Check your inbox for verification link</p>
    </div>

    <div class="mb-6 rounded-xl border border-slate-700/80 bg-slate-950/60 px-4 py-3 text-sm text-slate-300">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 rounded-xl border border-emerald-500/40 bg-emerald-900/20 px-4 py-3 text-sm text-emerald-100">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full rounded-lg border border-slate-600 px-4 py-3 text-center text-base font-semibold text-slate-200 hover:border-sky-400 hover:bg-slate-800 transition-all">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>