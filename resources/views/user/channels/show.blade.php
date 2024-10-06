@extends('user.layouts.app')

@section('content')
<div class="container mx-auto">
    @php
    $breadcrumbs = [
        ['title' => 'Dashboard', 'url' => route('user.dashboard')],
        ['title' => 'Channels', 'url' => route('user.channels.index')],
        ['title' => $channel->name, 'url' => '#']
    ];
    @endphp

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ $channel->name }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                {{ __('Channel Details') }}
            </p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ __('Image') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($channel->image)
                            <img src="{{ Storage::url($channel->image) }}" alt="{{ $channel->name }}" class="w-32 h-32 object-cover rounded">
                        @else
                            <span class="text-gray-400">{{ __('No image available') }}</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ __('Category') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $channel->category ? $channel->category->name : __('No category assigned') }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ __('Country') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $channel->country ? $channel->country->name : __('No country assigned') }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ __('Language') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $channel->language ? $channel->language->name : __('No language assigned') }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ __('Tags') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $channel->tags ?: __('No tags') }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ __('Status') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $channel->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $channel->status ? __('Active') : __('Inactive') }}
                        </span>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ __('Subscribers') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $channel->subscribers()->count() }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ __('About') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $channel->text ?: __('No description available') }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    
    <div class="mt-4 flex justify-between">
        <a href="{{ route('user.channels.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            {{ __('Back to List') }}
        </a>
        <a href="{{ route('user.channels.edit', $channel) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            {{ __('Edit') }}
        </a>
    </div>
</div>
@endsection