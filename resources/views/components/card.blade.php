@props([
    'priority' => 'alacsony',
    'showActions' => false,
])

@php
    if (!$showActions) {
        $borderColor = 'border-gray-500';
    } else {
        $borderColor = match ($priority) {
            'magas' => 'border-orange-500',
            'közepes' => 'border-amber-400',
            'alacsony' => 'border-emerald-500',
            default => 'border-gray-500',
        };
    }
@endphp

<div class="relative flex flex-col bg-white shadow-sm bg-opacity-50 p-5 rounded-md border-t-5 {{ $borderColor }}">
    <div class="flex flex-col h-full justify-between mb-2">
        <div class="pb-5">
            @isset($title)
                <h3 class="text-lg font-bold">{{ $title }}</h3>
            @endisset

            @isset($category)
                <h2>{{ $category }}</h2>
            @endisset

            @isset($created)
                <h3>{{ $created }}</h3>
            @endisset

            @isset($updated)
                <h3>{{ $updated }}</h3>
            @endisset
        </div>

        <div class="text-sm text-slate-700">
            {{ $slot }}
        </div>
    </div>

    @if ($showActions)
        <div class="flex gap-1 flex-col md:flex-row justify-center mt-10">
            {{ $actions ?? '' }}
        </div>
    @endif
</div>