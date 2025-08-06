<x-layout>
    <x-slot:title>
        {{ __('labels.completed.title') }}
    </x-slot:title>

    <h2 class="text-3xl font-semibold mb-4 text-center">{{ __('labels.completed.title') }}</h2>

    @foreach ($todos as $todo)
        <div class="flex flex-col p-5">
            <x-card :priority="$todo->priority" :show-actions="!$todo->is_completed">
                <x-slot:title>
                    {{ $todo->title }}
                </x-slot:title>

                @if ($todo->is_completed)
                    <x-slot:created>
                        {{ __('labels.completed.creation-date') }} {{ $todo->created_at->format('Y.m.d H:i') }}
                    </x-slot:created>

                    <x-slot:updated>
                        {{ __('labels.completed.completed-date') }} {{ $todo->updated_at->format('Y.m.d H:i') }}
                    </x-slot:updated>
                @endif

                {{ $todo->task }}
            </x-card>
        </div>
    @endforeach
    <x-pagination :paginator="$todos" />
</x-layout>
