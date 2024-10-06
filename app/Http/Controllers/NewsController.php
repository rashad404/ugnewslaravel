<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\DefaultSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\LanguageService;

class NewsController extends Controller
{
    protected $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    public function index(Request $request)
    {
        $query = News::query();

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('text', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('tags', 'LIKE', "%{$searchTerm}%");
            });
        }

        $news = $query->orderBy('id', 'desc')->paginate(15);

        return view('user.news.index', compact('news'));
    }

    public function create()
    {
        $user = auth()->user();
        $categories = Category::all();
        $channels = Channel::where('user_id', $user->id)->get();
        
        // Check if the user has any channels
        if ($channels->isEmpty()) {
            return redirect()->route('user.channels.create')
                ->with('message', 'Please create a channel first. <a href="'.route('user.channels.create').'" class="text-blue-500 underline">Create a channel</a>.');
        
        }

        $countries = Country::all();
        $cities = City::all();
        
        // Get default settings
        $defaultSetting = DefaultSetting::where('user_id', $user->id)->first();

        $defaultChannelId = $defaultSetting ? $defaultSetting->channel_id : null;

        return view('user.news.create', compact('categories', 'channels', 'countries', 'cities', 'defaultChannelId'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'title_extra' => 'nullable|max:255',
            'text' => 'required',
            'category_id' => 'required|exists:categories,id',
            'channel_id' => 'required|exists:channels,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'publish_time' => 'nullable|date',
            'status' => 'boolean',
            'tags' => 'nullable|array',
        ]);

        $data = $request->all();

        $data['user_id'] = auth()->id();
        $data['time'] = time();
        
        // Handle the image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $data['image'] = $imagePath;
        }

        if ($request->channel_id > 0) {
            $channelInfo = Channel::find($request->channel_id);
            $data['country_id'] = $channelInfo->country_id;
            $data['language_id'] = $channelInfo->language_id;
        }

        // Convert publish_time to Unix timestamp if provided
        if (!empty($request->publish_time)) {
            $data['publish_time'] = strtotime($request->publish_time); // Convert to timestamp
        }

        // Handle tags
        if (isset($data['tags'])) {
            $data['tags'] = implode(',', $data['tags']);
        }

        $data['status'] = 1;
        $data['slug'] = $channelInfo->name_url . '/' . $this->generateSafeSlug($request->title);

        News::create($data);

        return redirect()->route('user.news.index')->with('success', __('News item has been added successfully'));
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

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->store('news_images', 'public');

        return response()->json([
            'location' => Storage::url($path)
        ]);
    }

    public function show(News $news)
    {
        return view('user.news.show', compact('news'));
    }

    public function edit(News $news)
    {
        $categories = Category::all();
        $channels = Channel::where('user_id', auth()->id())->get();
        $countries = Country::all();
        $cities = City::all();
        
        return view('user.news.edit', compact('news', 'categories', 'channels', 'countries', 'cities'));
    }
    
    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|max:255',
            'title_extra' => 'nullable|max:255',
            'text' => 'required',
            'category_id' => 'required|exists:categories,id',
            'channel_id' => 'required|exists:channels,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'publish_time' => 'nullable|date',
            'city_id' => 'nullable|exists:cities,id',
            'tags' => 'nullable|array',
        ]);
    
        $data = $request->all();
    
        // Handle the image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $imagePath = $request->file('image')->store('images', 'public');
            $data['image'] = $imagePath;
        }
    
        if ($request->channel_id > 0) {
            $channelInfo = Channel::find($request->channel_id);
            $data['country_id'] = $channelInfo->country_id;
            $data['language_id'] = $channelInfo->language_id;
        }
    
        // Convert publish_time to Unix timestamp if provided
        if (!empty($request->publish_time)) {
            $data['publish_time'] = strtotime($request->publish_time);
        }
        // Handle tags
        if (isset($data['tags'])) {
            $data['tags'] = implode(',', $data['tags']);
        }
    
        $data['slug'] = $channelInfo->name_url . '/' . $this->generateSafeSlug($request->title);
        
        $news->update($data);
    
        return redirect()->route('user.news.index')->with('success', __('News item has been updated successfully'));
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('user.news.index')->with('success', 'News deleted successfully.');
    }

}