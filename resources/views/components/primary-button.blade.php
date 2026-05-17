<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#20b2aa] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#1a908a] focus:bg-[#1a908a] active:bg-[#15726d] focus:outline-none focus:ring-2 focus:ring-[#20b2aa] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
