<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Category;
use App\Models\Country;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ChannelController extends Controller
{
    public function index(Request $request)
    {
        $query = Channel::where('user_id', auth()->id());

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('text', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('tags', 'LIKE', "%{$searchTerm}%");
            });
        }

        $channels = $query->orderBy('position', 'asc')->paginate(15);

        return view('user.channels.index', compact('channels'));
    }

    public function create()
    {
        $categories = Category::all();
        $countries = Country::all();
        $languages = Language::where('status', 1)->get();
        
        return view('user.channels.create', compact('categories', 'countries', 'languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:100',
            'text' => 'nullable|min:10|max:10000',
            'category_id' => 'required|exists:categories,id',
            'country_id' => 'required|exists:countries,id',
            'language_id' => 'required|exists:languages,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['name_code'] = Str::lower(preg_replace("/[ *()\-_.,]/", "", $data['name']));
        $data['name_url'] = Str::slug($data['name']);
        $data['time'] = time();
        
        $data['status'] = $request->has('status') ? 1 : 0;

        $channel = Channel::create($data);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('channels', $filename, 'public');
            $channel->image = $path;
            $channel->save();
        }

        return redirect()->route('user.channels.index')->with('success', 'Channel created successfully.');
    }

    public function show(Channel $channel)
    {
        return view('user.channels.show', compact('channel'));
    }

    public function edit(Channel $channel)
    {
        $categories = Category::all();
        $countries = Country::all();
        $languages = Language::where('status', 1)->get();
        
        return view('user.channels.edit', compact('channel', 'categories', 'countries', 'languages'));
    }

    public function update(Request $request, Channel $channel)
    {
        $request->validate([
            'name' => 'required|min:3|max:100',
            'text' => 'nullable|min:10|max:10000',
            'category_id' => 'required|exists:categories,id',
            'country_id' => 'required|exists:countries,id',
            'language_id' => 'required|exists:languages,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['name_code'] = Str::lower(preg_replace("/[ *()\-_.,]/", "", $data['name']));
        $data['name_url'] = Str::slug($data['name']);
        $data['status'] = $request->has('status') ? 1 : 0;

        $channel->update($data);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($channel->image) {
                Storage::disk('public')->delete($channel->image);
            }

            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('channels', $filename, 'public');
            $channel->image = $path;
            $channel->save();
        }

        return redirect()->route('user.channels.index')->with('success', 'Channel updated successfully.');
    }

    public function destroy(Channel $channel)
    {
        if ($channel->image) {
            Storage::disk('public')->delete($channel->image);
        }
        $channel->delete();
        return redirect()->route('user.channels.index')->with('success', 'Channel deleted successfully.');
    }
}