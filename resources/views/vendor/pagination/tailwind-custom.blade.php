@if ($paginator->hasPages())
    <div class="px-6 py-4 bg-white border-t border-gray-200">
        <nav class="flex items-center justify-between" role="navigation" aria-label="Pagination">
            {{-- Mobile Pagination --}}
            <div class="flex-1 flex justify-between sm:hidden">
                @if ($paginator->onFirstPage())
                    <span
                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-not-allowed rounded-md">
                        Previous
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}"
                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Previous
                    </a>
                @endif

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}"
                        class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Next
                    </a>
                @else
                    <span
                        class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-not-allowed rounded-md">
                        Next
                    </span>
                @endif
            </div>

            {{-- Desktop Pagination --}}
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        to
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                        of
                        <span class="font-medium">{{ $paginator->total() }}</span>
                        results
                    </p>
                </div>

                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{-- Previous Page Link --}}
                        @if ($paginator->onFirstPage())
                            <span
                                class="relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-200 bg-white text-sm font-medium text-gray-400 cursor-not-allowed">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </span>
                        @else
                            <a href="{{ $paginator->previousPageUrl() }}"
                                class="relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-200 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page"
                                    class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                    class="bg-white border-gray-200 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium focus:z-10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                            <a href="{{ $paginator->nextPageUrl() }}"
                                class="relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-200 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @else
                            <span
                                class="relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-200 bg-white text-sm font-medium text-gray-400 cursor-not-allowed">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        @endif
                    </nav>
                </div>
            </div>
        </nav>
    </div>
@endif
