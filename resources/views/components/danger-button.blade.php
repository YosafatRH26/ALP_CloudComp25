<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-lg bg-rose-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-rose-500/30 hover:bg-rose-400 transition-all disabled:opacity-50']) }}>
    {{ $slot }}
</button>