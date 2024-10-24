@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <article class="bg-white shadow-lg rounded-lg overflow-hidden">
            <header class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <img class="w-12 h-12 rounded-full object-cover" src="{{ asset('storage/' . $channel_info->image) }}" alt="{{ $channel_info->name }}" />
                    <div class="flex-1">
                        <a href="{{ route('channel.show', $channel_info->name_url) }}" class="text-lg font-semibold text-gray-900 hover:underline">{{ $channel_info->name }}</a>
                        <p class="text-sm text-gray-500">{{ date("d.m.Y H:i", $item->publish_time) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-700">
                            <span id="subscriberCount">{{ number_format($channel_info->subscribers) }}</span> {{ __('subscribers') }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ number_format($item->view) }}
                            <svg class="inline-block w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                        </p>
                    </div>
                </div>
            </header>
            
            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ html_entity_decode($item->title) }} <span class="text-red-600">{{ $item->title_extra }}</span></h1>
                
                @if ($item->image)
                    <img class="w-full h-auto mb-6 rounded-lg shadow-md" src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" />
                @endif
                
                <div class="prose max-w-none text-gray-700">
                    {!! html_entity_decode($item->text) !!}
                </div>
            </div>
            
            @if ($item->tags)
                <div class="px-6 py-4 border-t border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Tags') }}:</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach (explode(',', $item->tags) as $tag)
                            <a href="{{ route('news.tag', $tag) }}" class="px-3 py-1 bg-gray-200 text-sm font-medium text-gray-700 rounded-full hover:bg-gray-300 transition">{{ $tag }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <footer class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                <div>
                    <button 
                        data-subscription-button
                        data-channel-id="{{ $channel_info->id }}" 
                        class="{{ Auth::check() ? '' : 'umodal_toggle' }} subscribe {{ $subscribe_check ? 'subscribed' : '' }} px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center"
                    >
                        @if ($subscribe_check)
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.143 17.082a24.248 24.248 0 0 0 3.844.148m-3.844-.148a23.856 23.856 0 0 1-5.455-1.31 8.964 8.964 0 0 0 2.3-5.542m3.155 6.852a3 3 0 0 0 5.667 1.97m1.965-2.277L21 21m-4.225-4.225a23.81 23.81 0 0 0 3.536-1.003A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6.53 6.53m10.245 10.245L6.53 6.53M3 3l3.53 3.53" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                        @endif
                        <span>{{ $subscribe_check ? __('Subscribed') : __('Subscribe') }}</span>
                    </button>
                </div>
                <div class="flex space-x-2">

                    <button 
    data-reaction-button="like"
    data-news-id="{{ $item->id }}" 
    class="{{ Auth::check() ? '' : 'umodal_toggle' }} like {{ $like_check ? 'liked' : '' }} px-3 py-1 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-md transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center"
>
    <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
    </svg>
    <span data-reaction-count="like">{{ $item->likes }}</span>
</button>

<button 
    data-reaction-button="dislike"
    data-news-id="{{ $item->id }}" 
    class="{{ Auth::check() ? '' : 'umodal_toggle' }} dislike {{ $dislike_check ? 'disliked' : '' }} px-3 py-1 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-md transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center"
>
    <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z" />
    </svg>
    <span data-reaction-count="dislike">{{ $item->dislikes }}</span>
</button>
                </div>
            </footer>
        </article>
        
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Share') }}:</h2>
            <div class="flex flex-wrap gap-2">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center" target="_blank" rel="noopener">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                    Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($item->title) }}" class="px-4 py-2 bg-blue-400 text-white rounded-md hover:bg-blue-500 transition focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 flex items-center" target="_blank" rel="noopener">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg>
                    Twitter
                </a>
                <a href="https://wa.me/?text={{ urlencode($item->title . ' ' . url()->current()) }}" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center" target="_blank" rel="noopener">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.464 0 4.784.96 6.524 2.703a9.182 9.182 0 012.704 6.525c0 5.45-4.437 9.884-9.885 9.884"></path>
                    </svg>
                    WhatsApp
                </a>
                <a href="mailto:?subject={{ urlencode($item->title) }}&body={{ url()->current() }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    Email
                </a>
            </div>
        </div>
    </div>
    
    <div>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <h2 class="text-xl font-semibold p-4 bg-gray-50 border-b border-gray-200">{{ __('Similar News') }}</h2>
            <div class="divide-y divide-gray-200">
                @foreach ($similar_news as $index => $news)
                    @if ($index == 1 && isset($ad))
                        <!-- Ad Item -->
                        <div class="p-4 bg-blue-50">
                            <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">{{ __('Ad') }}</span>
                            <a href="{{ route('ad.click', $ad->id) }}" target="_blank" class="mt-2 flex items-center group">
                                <img src="{{ asset('storage/' . $ad->image) }}" alt="" class="w-16 h-16 object-cover rounded-md mr-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition">{{ Str::limit($ad->title, 30) }}</h3>
                                    <p class="text-sm text-gray-600">{{ Str::limit($ad->text, 50) }}</p>
                                </div>
                            </a>
                        </div>
                    @endif
                    <!-- News Item -->
                    <a href="{{ route('news.show', ['', $news->slug]) }}" class="block p-4 hover:bg-gray-50 transition duration-150 ease-in-out">
                        <div class="flex items-center">
                            @if ($news->image)
                                <img src="{{ asset('storage/' . $news->image) }}" alt="" class="w-20 h-20 object-cover rounded-md mr-4">
                            @endif
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 hover:text-blue-600 transition">{{ Str::limit(html_entity_decode($news->title), 50) }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $news->channel->name }}</p>
                                <div class="flex items-center text-xs text-gray-500 mt-2">
                                    <span class="mr-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                        {{ number_format($news->view) }} {{ __('views') }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        {{ date("d/m H:i", $news->publish_time) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Login Modal -->

<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full m-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900">{{ __('Login') }}</h2>
            <button class="text-gray-400 hover:text-gray-500 focus:outline-none" onclick="closeLoginModal()">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <hr class="mb-4" />
        <div id="loginModalContent">
            @include('site.partials.modals.login', ['redirectUrl' => $item->slug])
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
    function openLoginModal() {
        document.getElementById('loginModal').classList.remove('hidden');
        document.getElementById('loginModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeLoginModal() {
        document.getElementById('loginModal').classList.add('hidden');
        document.getElementById('loginModal').classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Close modal when clicking outside
    document.getElementById('loginModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLoginModal();
        }
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('loginModal').classList.contains('hidden')) {
            closeLoginModal();
        }
    });

    document.querySelectorAll('.umodal_toggle').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            openLoginModal();
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* Default state */
    [data-reaction-button] {
        transition: all 0.2s ease;
    }
    
    /* Like button states */
    .like.liked {
        background-color: #ecfdf5 !important; /* Light green background */
        color: #059669 !important; /* Green text */
        border: 1px solid #059669;
    }
    
    .like:not(.liked):hover {
        background-color: #f3f4f6;
        color: #059669;
    }
    
    /* Dislike button states */
    .dislike.disliked {
        background-color: #fef2f2 !important; /* Light red background */
        color: #dc2626 !important; /* Red text */
        border: 1px solid #dc2626;
    }
    
    .dislike:not(.disliked):hover {
        background-color: #f3f4f6;
        color: #dc2626;
    }
    
    /* Animation for click */
    .liked, .disliked {
        transform: scale(1.05);
    }
    
    /* Count style */
    [data-reaction-count] {
        font-weight: 500;
        min-width: 1rem;
        text-align: center;
    }
    </style>
@endpush