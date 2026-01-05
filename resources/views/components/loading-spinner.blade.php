<div 
    x-show="loading"
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm"
>
    <div class="flex flex-col items-center gap-4">
        <!-- Spinner -->
        <svg class="h-12 w-12 animate-spin text-sky-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10"
                    stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>

        <!-- Text -->
        <p class="text-slate-200 text-sm tracking-wide">
            Analyzing CV, please wait...
        </p>
    </div>
</div>
