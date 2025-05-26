@props(['active'])

@php
$classes = ($active ?? false)
? 'block w-full ps-4 pe-4 py-2 rounded-md bg-indigo-100 text-indigo-700 font-medium text-base transition duration-150 ease-in-out'
: 'block w-full ps-4 pe-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>