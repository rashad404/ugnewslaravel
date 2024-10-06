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
            'last_name' => 'nullable|string|max:255',
            'birthday' => 'required|date|before_or_equal:' . now()->subYears(16)->format('Y-m-d'),
            'gender' => 'required|in:0,1,2',
            'country_code' => 'required|string',
            'phone' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Manually update fields instead of using fill()
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->birthday = $validatedData['birthday'];
        $user->gender = $validatedData['gender'];
        $user->country_code = $validatedData['country_code'];
        $user->phone = $validatedData['country_code'] . $validatedData['phone'];

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $user->image = $request->file('image')->store('users', 'public');
        }

        $user->save();

        return redirect()->route('user.profile')->with('success', __('Your profile information has been updated successfully'));
    }
}