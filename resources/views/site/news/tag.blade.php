@extends('layouts.app')

@section('content')
    <div class="my-8">
        <div class="relative">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center">
                <h2 class="px-3 bg-gray-100 text-2xl font-extrabold text-gray-900 sm:text-3xl">
                    {{ __($cat_name) . __(' News') }}
                </h2>
            </div>
        </div>

        <div>
            @include('site.news.include')
        </div>
    </div>
@endsection