@extends('user.layouts.app')

@section('content')
<div class="container mx-auto">
    @php
    $breadcrumbs = [
        ['title' => 'Dashboard', 'url' => route('user.dashboard')],
        ['title' => 'Ads', 'url' => '#']
    ];
    @endphp

    <h1 class="text-3xl font-bold mb-6 text-gray-800">Ads</h1>

    <div class="mb-6 flex justify-end">
        <a href="{{ route('user.ads.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-200">
            Create Ad
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clicks</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($ads as $ad)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-4">
                        <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->title }}" class="w-16 h-16 object-cover rounded">
                    </td>
                    <td class="py-4 px-4">{{ $ad->title }}</td>
                    <td class="py-4 px-4">{{ $ad->click }}</td>
                    <td class="py-4 px-4">{{ $ad->view }}</td>
                    <td class="py-4 px-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $ad->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $ad->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="py-4 px-4">
                        <a href="{{ route('user.ads.edit', $ad) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <form action="{{ route('user.ads.destroy', $ad) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $ads->links() }}
    </div>
</div>
@endsection