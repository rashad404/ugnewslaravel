<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Menu;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        View::composer('*', function ($view) {
            
            if (!isset($view->metaTitle)) $view->with('metaTitle', '');
            if (!isset($view->metaKeywords)) $view->with('metaKeywords', '');
            if (!isset($view->metaDescription)) $view->with('metaDescription', '');
            if (!isset($view->metaImg)) $view->with('metaImg', '');

            $menus = Menu::all();

            $countryId = Cookie::get('country', config('app.default_country'));
            $countryCode = \App\Models\Country::where('id', $countryId)->first()->code;
            $locale = App::getLocale();

            $view->with('countryId', $countryId);
            $view->with('countryCode', $countryCode);
            $view->with('menus', $menus);
            $view->with('locale', $locale);
        });

    }
}
