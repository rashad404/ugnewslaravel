<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\ApiKey;
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
            'thumb' => 'string|max:255',
            'cat' => 'required|integer',
            'channel' => 'required|integer',
            'country' => 'required|integer',
            'city' => 'required|integer',
            'language' => 'required|integer',
            'publish_time' => 'required|integer',
            'source' => 'string|max:100',
            'api_key' => 'required|string'
        ]);

        if ($validator->fails()) {
            Log::error('Webhook validation failed: ' . $validator->errors());
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // Verify the API key
        $partnerId = $this->verifyApiKey($request->api_key);
        if (!$partnerId) {
            Log::warning('Invalid API key used in webhook');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Create a new news article from the webhook data
            $news = new News();
            $news->title = $request->title;
            $news->title_extra = $request->title_extra ?? '';
            $news->text = $request->text;
            $news->tags = $request->tags;
            $news->image = $request->image ?? 'defaults/news.jpg';
            $news->thumb = $request->thumb ?? 'defaults/news.jpg';
            $news->position = 0; // Default value
            $news->cat = $request->cat;
            $news->channel = $request->channel;
            $news->country = $request->country;
            $news->city = $request->city;
            $news->language = $request->language;
            $news->status = 1; // Assuming 1 means published
            $news->time = time();
            $news->publish_time = $request->publish_time;
            $news->view = 1; // Default value
            $news->likes = 0;
            $news->dislikes = 0;
            $news->partner_id = $partnerId;
            $news->slug = Str::slug($request->title);
            $news->source = $request->source ?? '';
            $news->uniqueness = $this->generateUniqueness();

            $news->save();

            Log::info('New article created via webhook: ' . $news->id);
            return response()->json(['message' => 'Article created successfully', 'id' => $news->id], 201);
        } catch (\Exception $e) {
            Log::error('Error processing webhook: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
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
}