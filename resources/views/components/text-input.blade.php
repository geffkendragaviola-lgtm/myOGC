@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#F8650C] focus:ring-[#F8650C] rounded-md shadow-sm']) }}>
