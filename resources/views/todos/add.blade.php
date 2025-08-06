<x-layout>
    <x-slot:title>
        {{ __('labels.add.title') }}
    </x-slot:title>

    <x-form :categories="$categories" :action="route('todos.add')">
        {{ __('labels.add.title') }}
    </x-form>
</x-layout>
