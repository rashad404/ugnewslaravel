@extends('user.layouts.app')

@section('content')
@php
    $breadcrumbs = [
        ['title' => __('Dashboard'), 'url' => route('user.dashboard')],
        ['title' => __('API Keys'), 'url' => route('user.api-keys.index')],
        ['title' => __('Webhook Docs'), 'url' => '#'],
    ];
@endphp

<div class="container mx-auto my-8">
    <h1 class="text-3xl font-bold mb-6">{{ __('Webhook API Documentation') }}</h1>

    <div class="mb-6 flex justify-end">
        <a href="{{ route('user.api-keys.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            {{ __('Create API Key') }}
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <!-- Webhook Endpoint -->
        <h2 class="text-2xl font-semibold mb-4">{{ __('Webhook Endpoint') }}</h2>
        <p class="mb-4"><strong>{{ __('URL:') }}</strong> <code>https://ug.news/api/webhook/news</code></p>
        <p class="mb-4"><strong>{{ __('Method:') }}</strong> <code>POST</code></p>
        <p class="mb-4"><strong>{{ __('Content-Type:') }}</strong> <code>application/json</code></p>

        <!-- Request Payload -->
        <h2 class="text-2xl font-semibold mb-4">{{ __('Request Payload') }}</h2>
        <div class="bg-gray-100 p-4 rounded mb-4 shadow-inner">
            <pre><code class="language-json">
{
    "title": "string, required",
    "title_extra": "string, optional",
    "text": "string, required",
    "tags": "string, required",
    "image": "string (URL), optional",
    "category": "integer, required",
    "channel": "integer, required",
    "city": "integer, optional",
    "publish_time": "integer (Unix timestamp), optional",
    "api_key": "string, required"
}
            </code></pre>
        </div>

        <!-- Success Response -->
        <h2 class="text-2xl font-semibold mb-4">{{ __('Response') }}</h2>
        <p><strong>{{ __('Success Response') }}  (201):</strong></p>
        <div class="bg-gray-100 p-4 rounded mb-4 shadow-inner">
            <pre><code class="language-json">
{
    "message": "{{ __('Article created successfully') }}",
    "id": "12345"
}
            </code></pre>
        </div>

        <!-- Error Responses -->
        <p><strong>{{ __('Error Responses:') }}</strong></p>
        <ul class="list-disc pl-6 mb-4">
            <li><code>400</code> - {{ __('Invalid payload') }}</li>
            <li><code>401</code> - {{ __('Unauthorized (invalid API key)') }}</li>
            <li><code>500</code> - {{ __('Internal server error') }}</li>
        </ul>

        <!-- Example Request -->
        <h2 class="text-2xl font-semibold mb-4">{{ __('Example Request') }}</h2>
        <div class="bg-gray-100 p-4 rounded mb-4 shadow-inner">
            <pre><code class="language-bash">
curl -X POST https://ug.news/api/webhook/news \
-H "Content-Type: application/json" \
-d '{
  "title": "{{ __('Breaking News') }}",
  "title_extra": "{{ __('More details') }}",
  "text": "{{ __('This is the content of the news article.') }}",
  "tags": "{{ __('breaking, world') }}",
  "category": 1,
  "channel": 2,
  "city": 1,
  "publish_time": 1696602000,
  "api_key": "your-api-key"
}'
            </code></pre>
        </div>

        <!-- Notes -->
        <h2 class="text-2xl font-semibold mb-4">{{ __('Notes') }}</h2>
        <ul class="list-disc pl-6">
            <li>{{ __('The api_key must be valid for the request to succeed.') }}</li>
            <li>{{ __('If no image is provided, a default image will be used.') }}</li>
        </ul>
    </div>

    <!-- Update News API -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 mt-8">
        <h2 class="text-2xl font-semibold mb-4">{{ __('Update News API') }}</h2>
        <p class="mb-4"><strong>{{ __('URL:') }}</strong> <code>https://ug.news/api/webhook/news/{id}</code></p>
        <p class="mb-4"><strong>{{ __('Method:') }}</strong> <code>PUT</code></p>

        <h3 class="text-xl font-semibold mb-4">{{ __('Request Payload') }}</h3>
        <div class="bg-gray-100 p-4 rounded mb-4 shadow-inner">
            <pre><code class="language-json">
{
    "title": "string, optional",
    "title_extra": "string, optional",
    "text": "string, optional",
    "tags": "string, optional",
    "image": "string (URL), optional",
    "category": "integer, optional",
    "channel": "integer, optional",
    "city": "integer, optional",
    "publish_time": "integer (Unix timestamp), optional",
    "api_key": "string, required"
}
            </code></pre>
        </div>

        <h3 class="text-xl font-semibold mb-4">{{ __('Success Response') }} (200):</h3>
        <div class="bg-gray-100 p-4 rounded mb-4 shadow-inner">
            <pre><code class="language-json">
{
    "message": "{{ __('Article updated successfully') }}",
    "id": "12345"
}
            </code></pre>
        </div>

        <p><strong>{{ __('Error Responses:') }}</strong></p>
        <ul class="list-disc pl-6 mb-4">
            <li><code>400</code> - {{ __('Invalid payload') }}</li>
            <li><code>401</code> - {{ __('Unauthorized (invalid API key)') }}</li>
            <li><code>404</code> - {{ __('News not found') }}</li>
            <li><code>500</code> - {{ __('Internal server error') }}</li>
        </ul>

        <h3 class="text-xl font-semibold mb-4">{{ __('Example Request') }}</h3>
        <div class="bg-gray-100 p-4 rounded mb-4 shadow-inner">
            <pre><code class="language-bash">
curl -X PUT https://ug.news/api/webhook/news/12345 \
-H "Content-Type: application/json" \
-d '{
  "title": "{{ __('Updated News Title') }}",
  "text": "{{ __('This is the updated content of the news article.') }}",
  "api_key": "your-api-key"
}'
            </code></pre>
        </div>
    </div>

    <!-- Delete News API -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 mt-8">
        <h2 class="text-2xl font-semibold mb-4">{{ __('Delete News API') }}</h2>
        <p class="mb-4"><strong>{{ __('URL:') }}</strong> <code>https://ug.news/api/webhook/news/{id}</code></p>
        <p class="mb-4"><strong>{{ __('Method:') }}</strong> <code>DELETE</code></p>

        <h3 class="text-xl font-semibold mb-4">{{ __('Success Response') }} (200):</h3>
        <div class="bg-gray-100 p-4 rounded mb-4 shadow-inner">
            <pre><code class="language-json">
{
    "message": "{{ __('Article deleted successfully') }}"
}
            </code></pre>
        </div>

        <p><strong>{{ __('Error Responses:') }}</strong></p>
        <ul class="list-disc pl-6 mb-4">
            <li><code>401</code> - {{ __('Unauthorized (invalid API key)') }}</li>
            <li><code>404</code> - {{ __('News not found') }}</li>
            <li><code>500</code> - {{ __('Internal server error') }}</li>
        </ul>

        <h3 class="text-xl font-semibold mb-4">{{ __('Example Request') }}</h3>
        <div class="bg-gray-100 p-4 rounded mb-4 shadow-inner">
            <pre><code class="language-bash">
curl -X DELETE https://ug.news/api/webhook/news/12345 \
-H "Content-Type: application/json" \
-d '{
  "api_key": "your-api-key"
}'
            </code></pre>
        </div>
    </div>
</div>
@endsection
