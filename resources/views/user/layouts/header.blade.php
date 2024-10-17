<!-- Top Navigation -->
<nav class="mb-6" id="topNavigation">
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <!-- Breadcrumbs -->
            <div class="ml-12 md:ml-0">
                @include('user.components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
            </div>
        </div>
        <!-- User dropdown -->
        <div class="flex items-center">
            @include('user.layouts.dropdown')
        </div>
    </div>
</nav>

{{-- Errors --}}
@if (session('message'))
<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
    <p class="font-bold">{{ __('Warning') }}</p>
    <p>{!! session('message') !!}</p>
</div>
@endif
@if ($errors->any())
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
    <p class="font-bold">{{ __('Whoops! Something went wrong.') }}</p>
    <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
