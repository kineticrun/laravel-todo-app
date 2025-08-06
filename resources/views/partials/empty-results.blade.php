@props([
    'list',
    'message' => 'No result',
])

@if ($todos->isEmpty())
    <h1 class="text-center font-bold text-2xl mb-2 py-2 text-black">
        {{ $message }}
    </h1>
@endif
