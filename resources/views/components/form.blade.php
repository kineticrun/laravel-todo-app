@props(['method' => 'POST', 'action' => '#', 'categories' => null, 'todo' => null])

<div aria-label="Új feladat hozzáadása" class="bg-white rounded shadow-md p-6 max-w-lg mx-auto">
    <h2 class="text-2xl font-semibold text-center mb-4 text-slate-700">{{ $slot }}</h2>
    <form class="space-y-4" action="{{ $action }}" method="POST">
        @csrf
        @if($method === 'PATCH')
            @method('PATCH')
        @endif
        <div>
            <label for="title" class="block text-slate-600 mb-1 font-medium">{{ __('labels.form.task-name') }}</label>
            <input type="text" id="title" name="title" placeholder="{{ __('labels.form.task-placeholder-name') }}"
                value="{{ old('title', $todo->title ?? '') }}"
                class="w-full p-2 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-300" />
        </div>
        <div>
            <label for="task" class="block text-slate-600 mb-1 font-medium">{{ __('labels.form.task-description') }}</label>
            <textarea id="task" name="task" placeholder="{{ __('labels.form.task-placeholder-description') }}" rows="6"
                class="w-full p-2 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-300 resize-none">{{ old('task', $todo->task ?? '') }}</textarea>
        </div>
        <div>
            <label for="category_id" class="block text-slate-600 mb-1 font-medium">{{ __('labels.form.task-category') }}</label>
            <select id="category_id" name="category_id"
                class="w-full p-2 border border-slate-300 rounded bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-300">
                <option value="" disabled selected>{{ __('labels.form.task-select-category') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $todo->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name ?? '' }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="priority" class="block text-slate-600 mb-1 font-medium">{{ __('labels.form.task-priority') }}</label>
            <select id="priority" name="priority"
                class="w-full p-2 border border-slate-300 rounded bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-300">
                <option value="" disabled selected>{{ __('labels.form.task-select-priority') }}</option>
                <option value="alacsony" {{ old('priority', $todo->priority ?? '') == 'alacsony' ? 'selected' : '' }}>Alacsony
                </option>
                <option value="közepes" {{ old('priority', $todo->priority ?? '') == 'közepes' ? 'selected' : '' }}>Közepes
                </option>
                <option value="magas" {{ old('priority', $todo->priority ?? '') == 'magas' ? 'selected' : '' }}>Magas
                </option>
            </select>
        </div>
        <div class="pt-4 flex gap-1 justify-center">
            <x-button type="button" size="lg" variant="primary">
                @if($method === "PATCH")
                    {{ __('labels.form.task-save') }}
                @else
                    {{ __('labels.form.task-add') }}
                @endif
            </x-button>

        </div>
        
        @include('partials.form-error')

    </form>
</div>
