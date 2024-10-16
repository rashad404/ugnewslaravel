<?php

namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    public function setCountry($countryId)
    {
        // Validate if the countryId exists in the database
        $country = Country::find($countryId);

        if (!$country) {
            return redirect()->back()->with('error', 'Invalid country selection.');
        }

        // Set the country in a cookie, with a default expiration time (e.g., 30 days)
        $minutes = 60 * 24 * 30; // 30 days
        Cookie::queue(Cookie::make('country', $countryId, $minutes));

        // Optionally, return a success message or redirect to the previous page
        return redirect()->back()->with('success', 'Country has been updated to ' . $country->name);
    }
}