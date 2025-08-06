<x-layout>
    <x-slot:title>
        {{ __('labels.tasklist.title') }}
    </x-slot:title>

    @include('partials.flash-message')

    @include('partials.empty-results', [
        'list' => $todos,
        'message' => __('labels.tasklist.empty-todos'),
    ])

    <section class="flex md:flex-row flex-col">
        @foreach ($todos as $priority => $groupedTodos)
            <div class="flex-1 flex flex-col gap-y-5 p-5">
                @foreach ($groupedTodos as $todo)
                    <x-card :priority="$todo->priority" :show-actions="!$todo->is_completed">
                        <x-slot:title>
                            {{ $todo->title }}
                        </x-slot:title>

                        <x-slot:category>
                            {{ $todo->category->name }}
                        </x-slot:category>

                        <x-slot:updated>
                            {{ __('labels.tasklist.creation-date') }} {{ $todo->created_at->format('Y.m.d H:i') }}
                        </x-slot:updated>

                        {{ $todo->task }}

                        <x-slot:actions>
                            <form action="{{ route('todos.complete', $todo->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <x-button type="button" variant="success">
                                    {{ __('labels.tasklist.success-btn') }}
                                </x-button>
                            </form>

                            <x-button type="link" variant="primary"
                                href="{{ route('todos.edit', $todo->id) }}">
                                {{ __('labels.tasklist.edit-btn') }}
                            </x-button>

                            <form action="{{ route('todos.delete', $todo->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <x-button type="button" variant="danger">
                                    {{ __('labels.tasklist.delete-btn') }}
                                </x-button>
                            </form>
                        </x-slot:actions>
                    </x-card>
                @endforeach
            </div>
        @endforeach
    </section>
</x-layout>
