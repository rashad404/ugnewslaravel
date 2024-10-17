<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(15);
        return view('user.ads.index', compact('ads'));
    }

    public function create()
    {
        return view('user.ads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:30',
            'text' => 'nullable|max:50',
            'link' => 'required|url|max:250',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['time'] = time();
        $data['view'] = 1;
        $data['click'] = 0;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ads', 'public');
            $data['image'] = $imagePath;
            
            // Generate thumbnail
            // You might want to use an image manipulation library like Intervention Image for this
            $data['thumb'] = $imagePath; // For now, we're using the same image
        }

        Ad::create($data);

        return redirect()->route('user.ads.index')->with('success', 'Ad created successfully');
    }

    public function show(Ad $ad)
    {
        return view('user.ads.show', compact('ad'));
    }

    public function edit(Ad $ad)
    {
        return view('user.ads.edit', compact('ad'));
    }

    public function update(Request $request, Ad $ad)
    {
        $request->validate([
            'title' => 'required|max:20',
            'text' => 'nullable|max:50',
            'link' => 'required|url|max:250',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($ad->image) {
                Storage::disk('public')->delete($ad->image);
            }
            
            $imagePath = $request->file('image')->store('ads', 'public');
            $data['image'] = $imagePath;
            
            // Update thumbnail
            $data['thumb'] = $imagePath; // For now, we're using the same image
        }

        $ad->update($data);

        return redirect()->route('user.ads.index')->with('success', 'Ad updated successfully');
    }

    public function destroy(Ad $ad)
    {
        if ($ad->image) {
            Storage::disk('public')->delete($ad->image);
        }
        
        $ad->delete();

        return redirect()->route('user.ads.index')->with('success', 'Ad deleted successfully');
    }
}