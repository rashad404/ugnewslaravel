<nav aria-label="Breadcrumb" class="w-full">
    <ol class="flex flex-wrap items-center space-x-2 text-sm text-gray-500">
        @foreach ($breadcrumbs as $breadcrumb)
            <li class="flex items-center">
                @if (!$loop->last)
                    <a href="{{ $breadcrumb['url'] }}" class="text-gray-400 hover:text-gray-600 transition duration-150 ease-in-out">
                        {{ $breadcrumb['title'] }}
                    </a>
                    <svg class="h-5 w-5 text-gray-300 mx-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                @else
                    <span class="font-medium text-black" aria-current="page">{{ $breadcrumb['title'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
