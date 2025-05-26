@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-4">
        <ul class="inline-flex items-center space-x-1">

            {{-- for kiri --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed"><</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                       class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                        <
                    </a>
                </li>
            @endif

            {{-- Page Numbers --}}
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                $start = max($current - 1, 1);
                $end = min($current + 1, $last);
            @endphp

            @if ($start > 1)
                <li><a href="{{ $paginator->url(1) }}" class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100">1</a></li>
                @if ($start > 2)
                    <li><span class="px-2">...</span></li>
                @endif
            @endif

            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $current)
                    <li>
                        <span class="px-3 py-1 text-white bg-gray-600 rounded-lg font-semibold">
                            {{ $i }}
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->url($i) }}"
                           class="px-3 py-1 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                            {{ $i }}
                        </a>
                    </li>
                @endif
            @endfor

            @if ($end < $last)
                @if ($end < $last - 1)
                    <li><span class="px-2">...</span></li>
                @endif
                <li><a href="{{ $paginator->url($last) }}" class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100">{{ $last }}</a></li>
            @endif

            {{-- for Next --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                       class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                        >
                    </a>
                </li>
            @else
                <li>
                    <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">></span>
                </li>
            @endif
        </ul>
    </nav>
@endif
