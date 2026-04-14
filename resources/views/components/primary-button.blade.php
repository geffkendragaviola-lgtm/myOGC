<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#F8650C] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#E55A00] focus:bg-[#E55A00] active:bg-[#820000] focus:outline-none focus:ring-2 focus:ring-[#F8650C] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
