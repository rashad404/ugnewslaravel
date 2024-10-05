@extends('user.layouts.app')

@section('content')
<div class="container mx-auto">
    @php
    $breadcrumbs = [
    ['title' => 'Dashboard', 'url' => route('user.dashboard')],
    ['title' => 'News', 'url' => '#']
    ];
    @endphp

    <h1 class="text-3xl font-bold mb-6 text-gray-800">News Articles</h1>

    <div class="mb-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
        <form action="{{ route('user.news.index') }}" method="GET" class="w-full md:w-auto">
            <div class="flex">
                <input type="text" name="search" placeholder="Search news..."
                    class="pl-4 w-full md:w-64 rounded-l-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ request('search') }}">
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-r-lg hover:bg-blue-600 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </form>
        <a href="{{ route('user.news.create') }}"
            class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-200 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                    clip-rule="evenodd" />
            </svg>
            Create News
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="w-[70px] py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image
                    </th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title
                    </th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category
                    </th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created
                        at</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($news as $article)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 pl-4">
                        <img src="{{Storage::url($article->image ?? "defaults/news.jpg")}}" alt="{{ $article->title }}"
                            class="w-16 h-16 object-cover rounded">
                    </td>
                    <td class="py-4 px-4">
                        <a href="{{ route('user.news.show', $article) }}" class="text-gray-800 hover:text-blue-600">
                            {{ $article->title }}
                        </a>
                    </td>
                    <td class="py-4 px-4">{{ $article->catName }}</td>
                    <td class="py-4 px-4">{{ date('Y-m-d H:i', $article->time) }}</td>
                    <td class="py-4 px-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('user.news.show', $article) }}" class="text-blue-500 hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd"
                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="{{ route('user.news.edit', $article) }}"
                                class="text-yellow-500 hover:text-yellow-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </a>
                            <form action="{{ route('user.news.destroy', $article) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700"
                                    onclick="return confirm('Are you sure?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $news->links() }}
    </div>
</div>
@endsection