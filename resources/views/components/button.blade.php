@props([
    'type' => 'button',
    'href' => null,
    'variant' => null,
    'size' => 'sm',
    'isActive' => false,
])

@php
    $classes = match ($variant) {
        'primary' => 'bg-cyan-500 hover:bg-cyan-600',
        'danger' => 'bg-rose-500 hover:bg-rose-600',
        'success' => 'bg-green-500 hover:bg-green-600',
        'active' => 'bg-cyan-500 bg-cyan-600',
        default => 'bg-gray-500 hover:bg-gray-600',
    };

    $sizes = match ($size) {
        'lg' => 'text-lg px-5 py-3',
        'md' => 'text-md px-3 py-2',
        'sm' => 'text-sm px-2 py-1',
        default => 'text-sm px-2 py-1',
    };

    $active = $isActive ? 'bg-pink-600 hover:bg-pink-700' : '';

    $classes = collect(['w-full md:w-auto inline-flex items-center justify-center p-2 rounded text-white font-semibold', $classes, $sizes, $active])
        ->filter()
        ->implode(' ');
@endphp

@if ($type === 'link')
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="submit" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
