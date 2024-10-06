<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    public function index()
    {
        $apiKeys = ApiKey::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(15);
        return view('user.api-keys.index', compact('apiKeys'));
    }

    public function create()
    {
        return view('user.api-keys.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'is_active' => 'boolean',
        ]);

        $apiKey = new ApiKey();
        $apiKey->user_id = auth()->id();
        $apiKey->name = $request->name;
        $apiKey->api_key = Str::random(32);
        $apiKey->is_active = $request->is_active ?? true;
        $apiKey->save();

        return redirect()->route('user.api-keys.index')->with('success', 'API key created successfully');
    }

    public function show(ApiKey $apiKey)
    {
        return view('user.api-keys.show', compact('apiKey'));
    }

    public function edit(ApiKey $apiKey)
    {
        return view('user.api-keys.edit', compact('apiKey'));
    }

    public function update(Request $request, ApiKey $apiKey)
    {
        $request->validate([
            'name' => 'required|max:255',
            'is_active' => 'boolean',
        ]);

        $apiKey->name = $request->name;
        $apiKey->is_active = $request->is_active ?? true;
        $apiKey->save();

        return redirect()->route('user.api-keys.index')->with('success', 'API key updated successfully');
    }

    public function destroy(ApiKey $apiKey)
    {
        $apiKey->delete();
        return redirect()->route('user.api-keys.index')->with('success', 'API key deleted successfully');
    }
}
