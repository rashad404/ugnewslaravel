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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Validate the incoming webhook payload
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'title_extra' => 'nullable|string|max:255',
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
            
            // Handle the image URL download and save to storage
            if ($request->image) {
                $imageUrl = $request->image;
                $imageContents = Http::get($imageUrl)->body(); // Download the image
                $imageName = basename($imageUrl); // Extract the image name from URL
                $imagePath = 'images/' . $imageName; // Define where to store the image
                
                Storage::disk('public')->put($imagePath, $imageContents); // Save to storage
                $news->image = $imagePath; // Store the local path in the database
            } else {
                $news->image = 'defaults/news.jpg'; // Default image
            }

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
            return response()->json(['error' => $e->getMessage() . 'Internal server error'], 500);
        }
    }


    public function updateNews(Request $request, $id)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'title_extra' => 'nullable|string|max:255',
            'text' => 'nullable|string',
            'tags' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'category' => 'nullable|integer',
            'channel' => 'nullable|integer',
            'city' => 'nullable|integer',
            'publish_time' => 'nullable|integer',
            'api_key' => 'required|string'
        ]);

        if ($validator->fails()) {
            Log::error('Update validation failed: ' . $validator->errors());
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // Verify API key
        $userId = $this->verifyApiKey($request->api_key);
        if (!$userId) {
            Log::warning('Invalid API key used in update');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Find the news by ID
        $news = News::find($id);
        if (!$news) {
            return response()->json(['error' => 'News not found'], 404);
        }

        // Update fields if they are provided
        $news->title = $request->title ?? $news->title;
        $news->title_extra = $request->title_extra ?? $news->title_extra;
        $news->text = $request->text ?? $news->text;
        $news->tags = $request->tags ?? $news->tags;
        
        // Handle image update if provided
        if ($request->image) {
            $imageUrl = $request->image;
            $imageContents = Http::get($imageUrl)->body();
            $imageName = basename($imageUrl);
            $imagePath = 'images/' . $imageName;
            Storage::disk('public')->put($imagePath, $imageContents);
            $news->image = $imagePath;
        }

        $news->category_id = $request->category ?? $news->category_id;
        $news->channel_id = $request->channel ?? $news->channel_id;
        $news->city_id = $request->city ?? $news->city_id;
        $news->publish_time = $request->publish_time ?? $news->publish_time;

        $news->save();

        Log::info('News article updated: ' . $news->id);
        return response()->json(['message' => 'Article updated successfully', 'id' => $news->id], 200);
    }


    public function deleteNews(Request $request, $id)
    {
        // Validate API key
        $userId = $this->verifyApiKey($request->api_key);
        if (!$userId) {
            Log::warning('Invalid API key used in delete');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Find the news by ID
        $news = News::find($id);
        if (!$news) {
            return response()->json(['error' => 'News not found'], 404);
        }

        // Delete the news
        $news->delete();

        Log::info('News article deleted: ' . $news->id);
        return response()->json(['message' => 'Article deleted successfully'], 200);
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