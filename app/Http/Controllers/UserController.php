<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SmsService;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $countryList = SmsService::getCountryList();

        return view('user.profile', compact('user', 'countryList'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date|before_or_equal:' . now()->subYears(16)->format('Y-m-d'),
            'gender' => 'required|in:0,1,2',
            'country_code' => 'required|string',
            'phone' => 'required|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->fill($validatedData);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $request->file('profile_photo')->store('users', 'public');
        }

        $user->phone = $validatedData['country_code'] . $validatedData['phone'];
        $user->save();

        return redirect()->route('user.profile')->with('success', __('Your profile information has been updated successfully'));
    }
}