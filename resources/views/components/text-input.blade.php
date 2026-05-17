@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#20b2aa] focus:ring-[#20b2aa] rounded-md shadow-sm']) }}>
