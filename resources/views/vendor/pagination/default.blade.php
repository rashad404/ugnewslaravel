<div class="container mx-auto">
    <div class="grid grid-cols-1 gap-4">
        @foreach($items as $item)
            <!-- Display your items here -->
            <div>{{ $item->name }}</div>
        @endforeach
    </div>

    <!-- Pagination links -->
    <div class="mt-4 overflow-x-auto">
        <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center">
            <div class="flex justify-between flex-1 sm:hidden">
                @if ($items->onFirstPage())
                    <span class="relative inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                        {!! __('pagination.previous') !!}
                    </span>
                @else
                    <a href="{{ $items->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500">
                        {!! __('pagination.previous') !!}
                    </a>
                @endif

                @if ($items->hasMorePages())
                    <a href="{{ $items->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-1 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500">
                        {!! __('pagination.next') !!}
                    </a>
                @else
                    <span class="relative inline-flex items-center px-2 py-1 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                        {!! __('pagination.next') !!}
                    </span>
                @endif
            </div>

            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 leading-5">
                        {!! __('Showing') !!}
                        <span class="font-medium">{{ $items->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium">{{ $items->lastItem() }}</span>
                        {!! __('of') !!}
                        <span class="font-medium">{{ $items->total() }}</span>
                        {!! __('results') !!}
                    </p>
                </div>

                <div>
                    <span class="relative z-0 inline-flex shadow-sm rounded-md">
                        @if ($items->onFirstPage())
                            <span class="relative inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-l-md" aria-hidden="true">&larr;</span>
                        @else
                            <a href="{{ $items->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-white border border-gray-300 leading-5 hover:text-gray-400 rounded-l-md" aria-label="{{ __('pagination.previous') }}">&larr;</a>
                        @endif

                        @foreach ($items->links()->elements as $element)
                            @if (is_string($element))
                                <span class="relative inline-flex items-center px-2 py-1 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 cursor-default">{{ $element }}</span>
                            @endif

                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $items->currentPage())
                                        <span aria-current="page" class="relative inline-flex items-center px-2 py-1 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="relative inline-flex items-center px-2 py-1 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 leading-5 hover:text-gray-400">{{ $page }}</a>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        @if ($items->hasMorePages())
                            <a href="{{ $items->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-1 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 leading-5 hover:text-gray-400 rounded-r-md" aria-label="{{ __('pagination.next') }}">&rarr;</a>
                        @else
                            <span class="relative inline-flex items-center px-2 py-1 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-r-md">&rarr;</span>
                        @endif
                    </span>
                </div>
            </div>
        </nav>
    </div>
</div>
