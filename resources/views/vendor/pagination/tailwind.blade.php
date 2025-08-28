<nav role="navigation" aria-label="Pagination Navigation" class="w-full overflow-x-auto mt-6">
    <ul class="inline-flex items-center space-x-1 text-sm">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="px-2 py-1 text-gray-400 cursor-not-allowed">&laquo;</li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}" class="px-2 py-1 bg-white text-blue-500 hover:underline rounded">&laquo;</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="px-2 py-1 text-gray-500">{{ $element }}</li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="px-2 py-1 bg-blue-500 text-white rounded">{{ $page }}</li>
                    @else
                        <li>
                            <a href="{{ $url }}" class="px-2 py-1 bg-white text-blue-500 hover:bg-blue-100 rounded">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" class="px-2 py-1 bg-white text-blue-500 hover:underline rounded">&raquo;</a>
            </li>
        @else
            <li class="px-2 py-1 text-gray-400 cursor-not-allowed">&raquo;</li>
        @endif
    </ul>
</nav>
