<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\ApiKey;
use App\Models\Channel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Validate the incoming webhook payload
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'title_extra' => 'string|max:255',
            'text' => 'required|string',
            'tags' => 'required|string|max:255',
            'image' => 'string|max:255',
            'category' => 'required|integer',
            'channel' => 'required|integer',
            'city' => 'nullable|integer',
            'publish_time' => 'nullable|integer',
            'api_key' => 'required|string'
        ]);

        if ($validator->fails()) {
            Log::error('Webhook validation failed: ' . $validator->errors());
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // Verify the API key
        $userId = $this->verifyApiKey($request->api_key);
        if (!$userId) {
            Log::warning('Invalid API key used in webhook');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $channelInfo = Channel::find($request->channel);

        try {
            // Create a new news article from the webhook data
            $news = new News();
            $news->title = $request->title;
            $news->title_extra = $request->title_extra ?? '';
            $news->text = $request->text;
            $news->tags = $request->tags;
            $news->image = $request->image ?? 'defaults/news.jpg';
            $news->position = 0; // Default value
            $news->category_id = $request->category;
            $news->channel_id = $request->channel;
            $news->city_id = $request->city;
            $news->status = 1; // Assuming 1 means published
            $news->time = time();
            $news->publish_time = $request->publish_time ?? time();
            $news->view = 1; // Default value
            $news->likes = 0;
            $news->dislikes = 0;
            $news->user_id = $userId;
            $news->uniqueness = $this->generateUniqueness();
            $news->country_id = $channelInfo->country_id;
            $news->language_id = $channelInfo->language_id;
            $news->slug = $channelInfo->name_url . '/' . $this->generateSafeSlug($request->title);

            $news->save();

            Log::info('New article created via webhook: ' . $news->id);
            return response()->json(['message' => 'Article created successfully', 'id' => $news->id], 201);
        } catch (\Exception $e) {
            Log::error('Error processing webhook: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage().'Internal server error'], 500);
        }
    }

    private function verifyApiKey($apiKey)
    {
        $apiKeyRecord = ApiKey::where('api_key', $apiKey)
                              ->where('is_active', true)
                              ->first();

        if ($apiKeyRecord) {
            return $apiKeyRecord->user_id;
        }

        return false;
    }

    private function generateUniqueness()
    {
        return Str::random(15);
    }

    protected function generateSafeSlug($title)
    {
        $slug = Str::slug($title);
        $count = 2;
        while (News::where('slug', $slug)->exists()) {
            $slug = Str::slug($title) . '-' . $count;
            $count++;
        }
        return $slug;
    }
}