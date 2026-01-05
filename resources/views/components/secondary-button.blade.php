<button {{ $attributes->merge(['type' => 'button', 'class' => 'w-full rounded-lg border border-slate-600 px-4 py-3 text-center text-base font-semibold text-slate-200 hover:border-sky-400 hover:bg-slate-800 transition-all']) }}>
    {{ $slot }}
</button>