<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        // Get country list and other required data
        $countryList = ['1' => 'USA', '44' => 'UK', '91' => 'India']; // Example country list
        return view('auth.register', compact('countryList'));
    }

    public function register(Request $request)
    {
        // Validate the form data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'gender' => 'required|in:0,1,2',
            'country_code' => 'required',
            'phone' => 'required',
            'birth_month' => 'required',
            'birth_day' => 'required',
            'birth_year' => 'required',
            'password' => 'required|string|min:8',
        ]);

        // Create and save the user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'gender' => $request->gender,
            'country_code' => $request->country_code,
            'phone' => $request->phone,
            'birthday' => "{$request->birth_year}-{$request->birth_month}-{$request->birth_day}",
            'password' => Hash::make($request->password),
        ]);

        // Automatically log in the user
        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Account created successfully. Please log in.');
    }
}
