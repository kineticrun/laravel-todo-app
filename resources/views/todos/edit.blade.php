<x-layout>
    <x-slot:title>
        {{ __('labels.edit.title') }}
    </x-slot:title>

    <x-form :todo="$todo" :categories="$categories" method="PATCH" :action="route('todos.edit', $todo)"> 
        {{ __('labels.edit.title') }}
    </x-form>
</x-layout>