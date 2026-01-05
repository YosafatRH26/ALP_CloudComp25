<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 relative overflow-hidden pb-12">
        
        {{-- Background Glow --}}
        <div class="absolute top-0 left-0 w-[600px] h-[600px] bg-sky-600/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-emerald-600/10 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="py-12 relative z-10">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

                {{-- Profile Information --}}
                <div class="animate-fade-in-up">
                    @include('profile.partials.update-profile-information-form')
                </div>

                {{-- Update Password --}}
                <div class="animate-fade-in-up" style="animation-delay: 0.1s">
                    @include('profile.partials.update-password-form')
                </div>

                {{-- Delete Account --}}
                <div class="animate-fade-in-up" style="animation-delay: 0.2s">
                    @include('profile.partials.delete-user-form')
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
