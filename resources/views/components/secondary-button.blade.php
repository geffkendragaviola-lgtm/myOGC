<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-[#FFC917] rounded-md font-semibold text-xs text-[#820000] uppercase tracking-widest shadow-sm hover:bg-[#FFF9E6] focus:outline-none focus:ring-2 focus:ring-[#FFC917] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
