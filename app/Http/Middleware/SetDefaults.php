<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class SetDefaults
{
    public function handle($request, Closure $next)
    {
        // Check if the country cookie is set
        $countryId = Cookie::get('country');

        if (!$countryId) {
            // If no country cookie, set default country (ID 16) and language (az)
            $defaultCountryId = config('app.default_country');
            $defaultLanguage = config('app.locale');

            // Set default country in the cookie (for 30 days)
            $minutes = 60 * 24 * 30; // 30 days
            Cookie::queue(Cookie::make('country', $defaultCountryId, $minutes));

            // Set default language in the session
            Session::put('locale', $defaultLanguage);
        } else {
            // If country is set, determine the language based on the country ID
            $language = ($countryId == 16) ? 'az' : 'en';
            Session::put('locale', $language);
        }

        // Set the application locale based on session
        app()->setLocale(Session::get('locale', 'az'));

        return $next($request);
    }
}
