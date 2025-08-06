<nav
    class="flex flex-col items-center justify-center md:flex-row md:justify-between md:items-center mb-8 p-2 bg-white shadow-md rounded">
    <h1 class="text-3xl font-bold text-cyan-500 mb-5 md:mb-0">
        ToDo App
    </h1>
    <div>
        <x-button type="link" size="sm" href="{{ route('todos.set-locale', 'hu') }}" :isActive="App::isLocale('hu') ? true : false">HU</x-button>
        <x-button type="link" size="sm" href="{{ route('todos.set-locale', 'en') }}" :isActive="App::isLocale('en') ? true : false">ENG</x-button>
    </div>
    <div class="flex flex-col justify-end gap-4 md:flex-row">
        @if (request()->routeIs('todos.index'))
            <x-button type="link" variant="primary" size="md" href="{{ route('todos.add') }}">
                {{ __('labels.navigation.new-task') }}
            </x-button>
            <x-button type="link" variant="success" size="md" href="{{ route('todos.completed') }}">
                {{ __('labels.navigation.completed-task') }}
            </x-button>
        @else
            <x-button type="link" variant="danger" size="md" href="{{ route('todos.index') }}">
                {{ __('labels.navigation.back-btn') }}
            </x-button>
        @endif
    </div>
</nav>
