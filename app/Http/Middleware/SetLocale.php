<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Retrieve the locale from the session, default to app's locale
        $locale = Session::get('locale', config('app.locale'));
        
        // Set the application locale
        App::setLocale($locale);

        return $next($request);
    }
}
