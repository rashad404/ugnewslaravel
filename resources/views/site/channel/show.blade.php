@extends('layouts.app')

@section('content')
<div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="relative h-48 sm:h-64 md:h-80">
        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-end p-6">
            <h1 class="text-3xl font-bold text-white mb-2">{{ $item->name }}</h1>
            <p class="text-sm text-gray-300 mb-2">{{ url('/' . strtolower($item->name_url)) }}</p>
            <div class="flex items-center justify-between">
                <span class="text-white">
                    <span id="subscriberCount">{{ number_format($item->subscribers) }}</span> {{__('subscribers')}}<br/>
                    {{ number_format($item->view) }} {{ __('views') }}
                </span>
                <button 
                id="subscribe_button" 
                    data-subscription-button
                    data-channel-id="{{ $item->id }}" 
                    class="{{ $userId > 0 ? '' : 'umodal_toggle' }} subscribe {{ $subscribe_check ? 'subscribed' : '' }} px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center"
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
                    <span data-subscriber-count>{{ $subscribe_check ? __('Subscribed') : __('Subscribe') }}</span>

                </button>
            </div>
        </div>
    </div>
</div>

<div class="mt-8">
    @include('site.news.include')
</div>

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
            @include('site.partials.modals.login', ['redirectUrl' => $item->name_url])
        </div>
    </div>
</div>

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

document.querySelectorAll('.umodal_toggle').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        openLoginModal();
    });
});

</script>
@endsection
