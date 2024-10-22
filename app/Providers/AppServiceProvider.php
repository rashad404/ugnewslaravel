<?php

namespace App\Providers;

use App\Services\LanguageService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LanguageService::class, function ($app) {
            return new LanguageService();
        });
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Paginator::defaultView('pagination::tailwind');
        
        Paginator::defaultSimpleView('pagination::semantic-ui');

    }
}
