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
    <h1 class="text-4xl font-bold mb-6">Webhook API Documentation</h1>

    <p class="mb-4">
        This API allows external systems to send news articles directly to our platform via a webhook.
    </p>

    <h2 class="text-2xl font-semibold mb-4">Webhook Endpoint</h2>
    <p class="mb-4"><strong>URL:</strong> <code>https://new.ug.news/webhook</code></p>
    <p class="mb-4"><strong>Method:</strong> <code>POST</code></p>
    <p class="mb-4"><strong>Content-Type:</strong> <code>application/json</code></p>

    <h2 class="text-2xl font-semibold mb-4">Request Payload</h2>
    <pre class="bg-gray-100 p-4 rounded mb-4">
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
    </pre>

    <h2 class="text-2xl font-semibold mb-4">Response</h2>
    <p><strong>Success Response (201):</strong></p>
    <pre class="bg-gray-100 p-4 rounded mb-4">
{
    "message": "Article created successfully",
    "id": "12345"
}
    </pre>

    <p><strong>Error Responses:</strong></p>
    <ul class="list-disc pl-6 mb-4">
        <li>400 - Invalid payload</li>
        <li>401 - Unauthorized (invalid API key)</li>
        <li>500 - Internal server error</li>
    </ul>

    <h2 class="text-2xl font-semibold mb-4">Example Request</h2>
    <pre class="bg-gray-100 p-4 rounded mb-4">
curl -X POST https://new.ug.news/webhook \
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
    </pre>

    <h2 class="text-2xl font-semibold mb-4">Notes</h2>
    <ul class="list-disc pl-6">
        <li>The <code>api_key</code> must be valid for the request to succeed.</li>
        <li>If no image is provided, a default image will be used.</li>
    </ul>
</div>
@endsection
