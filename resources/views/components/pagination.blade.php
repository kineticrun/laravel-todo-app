@props(['paginator' => null])

@if ($paginator)
    @php
        $disabledClass = 'pointer-events-none select-none opacity-50';
        $lastPage = $paginator->lastPage() === $paginator->currentPage() ? $disabledClass : '';
        $firstPage = $paginator->onFirstPage() ? $disabledClass : '';
    @endphp
@endif

@if ($paginator && $paginator->hasPages())
    <nav class="mt-10">
        <ul class="flex justify-center font-semibold">
            <li>
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-4 py-2 bg-yellow-400 border border-yellow-500 text-gray-800 rounded-l-md hover:bg-yellow-300 {{ $firstPage }}">
                    {{ __('labels.pagination.previous') }}
                </a>
            </li>

            @for ($page = 1; $page <= $paginator->lastPage(); $page++)
                <li>
                    <a href="{{ $paginator->url($page) }}"
                        class="px-4 py-2 border border-yellow-500 text-gray-800 hover:bg-yellow-300
                              {{ $paginator->currentPage() == $page ? 'bg-blue-100 text-blue-700' : 'bg-yellow-400' }}">
                        {{ $page }}
                    </a>
                </li>
            @endfor

            <li>
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-4 py-2 bg-yellow-400 border border-yellow-500 text-gray-800 rounded-r-md hover:bg-yellow-300 {{ $lastPage }}">
                    {{ __('labels.pagination.next') }}
                </a>
            </li>
        </ul>
    </nav>
@endif
