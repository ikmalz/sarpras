@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block px-4 py-2 rounded-md bg-gray-100 text-gray-700 font-medium transition duration-150 ease-in-out'
    : 'block px-4 py-2 rounded-md text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
