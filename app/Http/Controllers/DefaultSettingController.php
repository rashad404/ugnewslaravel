<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Country;
use App\Models\DefaultSetting;
use App\Models\Language;
use Illuminate\Http\Request;

class DefaultSettingController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $defaultSetting = DefaultSetting::where('user_id', $user->id)->first();

        if (!$defaultSetting) {
            // Create a new default setting if it doesn't exist
            $channel = Channel::where('user_id', $user->id)->first();
            
            if (!$channel) {
                // Redirect to channel creation if user has no channels
                return redirect()->route('user.channels.create')
                    ->with('message', 'Please create a channel first before setting up default settings.');
            }

            $defaultSetting = DefaultSetting::create([
                'user_id' => $user->id,
                'channel_id' => $channel->id,
                'country_id' => $channel->country_id ?? null,
                'language_id' => $channel->language_id ?? null,
            ]);
        }

        $channels = Channel::where('user_id', $user->id)->get();
        $countries = Country::all();
        $languages = Language::all();

        return view('user.default-settings.index', compact('defaultSetting', 'channels', 'countries', 'languages'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'channel_id' => 'required|exists:channels,id',
            'country_id' => 'required|exists:countries,id',
            'language_id' => 'required|exists:languages,id',
        ]);

        $user = auth()->user();
        $defaultSetting = DefaultSetting::where('user_id', $user->id)->first();
        if (!$defaultSetting) {
            // Create a new default setting if it doesn't exist
            $channel = Channel::where('user_id', $user->id)->first();
            
            if (!$channel) {
                // Redirect to channel creation if user has no channels
                return redirect()->route('user.channels.create')
                    ->with('message', 'Please create a channel first before setting up default settings.');
            }

            $defaultSetting = DefaultSetting::create([
                'user_id' => $user->id,
                'channel_id' => $channel->id,
                'country_id' => $channel->country_id ?? null,
                'language_id' => $channel->language_id ?? null,
            ]);
        }

        $defaultSetting->update($request->all());

        return redirect()->route('user.default-settings.index')
            ->with('success', 'default settings updated successfully.');
    }
}