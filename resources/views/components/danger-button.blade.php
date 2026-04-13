<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#F00000] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#D40000] active:bg-[#820000] focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
