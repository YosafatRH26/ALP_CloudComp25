<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full rounded-lg bg-gradient-to-r from-sky-500 to-cyan-400 px-4 py-3 text-base font-semibold text-white shadow-lg shadow-sky-500/30 hover:shadow-sky-500/50 hover:from-sky-400 hover:to-cyan-300 transition-all disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>