@extends('user.layouts.app')

@section('content')
@php
    $breadcrumbs = [
    ['title' => 'Dashboard', 'url' => route('user.dashboard')],
    ['title' => 'API Keys', 'url' => route('user.api-keys.index')],
    ['title' => 'Webhook Docs', 'url' => '#'],
];
@endphp

<div class="container mx-auto my-8">
    <h1 class="text-3xl font-bold mb-6">Webhook API Documentation</h1>

    <div class="mb-6 flex justify-end">
        <a href="{{ route('user.api-keys.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            {{ __('Create API Key') }}
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <h2 class="text-2xl font-semibold mb-4">Webhook Endpoint</h2>
        <p class="mb-4"><strong>URL:</strong> <code>https://new.ug.news/api/webhook/news</code></p>
        <p class="mb-4"><strong>Method:</strong> <code>POST</code></p>
        <p class="mb-4"><strong>Content-Type:</strong> <code>application/json</code></p>

        <h2 class="text-2xl font-semibold mb-4">Request Payload</h2>
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
    "city": "integer, required",
    "publish_time": "integer (Unix timestamp), optional",
    "api_key": "string, required"
}
            </code></pre>
        </div>

        <h2 class="text-2xl font-semibold mb-4">Response</h2>
        <p><strong>Success Response (201):</strong></p>
        <div class="bg-gray-100 p-4 rounded mb-4 shadow-inner">
            <pre><code class="language-json">
{
    "message": "Article created successfully",
    "id": "12345"
}
            </code></pre>
        </div>

        <p><strong>Error Responses:</strong></p>
        <ul class="list-disc pl-6 mb-4">
            <li><code>400</code> - Invalid payload</li>
            <li><code>401</code> - Unauthorized (invalid API key)</li>
            <li><code>500</code> - Internal server error</li>
        </ul>

        <h2 class="text-2xl font-semibold mb-4">Example Request</h2>
        <div class="bg-gray-100 p-4 rounded mb-4 shadow-inner">
            <pre><code class="language-bash">
curl -X POST https://new.ug.news/api/webhook \
-H "Content-Type: application/json" \
-d '{
  "title": "Breaking News",
  "title_extra": "More details",
  "text": "This is the content of the news article.",
  "tags": "breaking, world",
  "category": 1,
  "channel": 2,
  "city": 1,
  "publish_time": 1696602000,
  "api_key": "your-api-key"
}'
            </code></pre>
        </div>

        <h2 class="text-2xl font-semibold mb-4">Notes</h2>
        <ul class="list-disc pl-6">
            <li>The <code>api_key</code> must be valid for the request to succeed.</li>
            <li>If no image is provided, a default image will be used.</li>
        </ul>
    </div>
</div>
@endsection
