@extends('user.layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => route('user.dashboard')],
    ['title' => 'API Keys', 'url' => '#'],
];
@endphp
<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">{{ __('API Keys') }}</h1>

    <div class="mb-6 flex justify-end">
        <a href="{{ route('user.api-keys.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">{{ __('Create API Key') }}</a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Name') }}</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">{{ __('API Key') }}</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($apiKeys as $apiKey)
                <tr>
                    <td class="py-4 px-4">{{ $apiKey->name }}</td>
                    <td class="py-4 px-4">{{ $apiKey->api_key }}</td>
                    <td class="py-4 px-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $apiKey->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $apiKey->is_active ? __('Active') : __('Inactive') }}
                        </span>
                    </td>
                    <td class="py-4 px-4">
                        <a href="{{ route('user.api-keys.edit', $apiKey) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                        <form action="{{ route('user.api-keys.destroy', $apiKey) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $apiKeys->links() }}
    </div>
</div>
@endsection
