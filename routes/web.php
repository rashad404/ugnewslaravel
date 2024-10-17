<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Site\AjaxController;
use App\Http\Controllers\User\AdController;
use App\Http\Controllers\User\ApiKeyController;
use App\Http\Controllers\User\ChannelController;
use App\Http\Controllers\User\DefaultSettingController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\DocumentationController;
use App\Http\Controllers\User\NewsController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Site\ChannelController as SiteChannelController;
use App\Http\Controllers\Site\NewsController as SiteNewsController;
use App\Http\Controllers\Site\AdController as SiteAdController;
use App\Http\Controllers\Site\CurrencyController;
use App\Http\Controllers\Site\NamazTimeController;
use App\Http\Controllers\Site\RatingController;
use App\Http\Controllers\Site\SettingController;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\Site\WeatherController;

Route::get('/set/country/{countryId}', [SettingController::class, 'setCountry'])->name('set.country');
Route::get('/channel/create', [SiteChannelController::class, 'create'])->name('channel.create');


Route::get('/rating/channels', [RatingController::class, 'channels'])->name('rating.channels');
Route::get('/rating/news', [RatingController::class, 'news'])->name('rating.news');
Route::get('/cat/{slug}', [SiteController::class, 'cat'])->name('cat');


Route::get('/', [SiteController::class, 'index'])->name('site.index');
Route::get('/contact', [SiteController::class, 'contact'])->name('site.contact');
Route::post('/contact', [SiteController::class, 'submitContact'])->name('site.contact.submit');


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register.post');

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard12', [DashboardController::class, 'index'])->name('user.channels.create');
    Route::get('/dashboard3', [DashboardController::class, 'index'])->name('ads.index');
    Route::get('/dashboard5', [DashboardController::class, 'index'])->name('profile.show');
    Route::get('/dashboard6', [DashboardController::class, 'index'])->name('settings');
    Route::get('/dashboard7', [DashboardController::class, 'index'])->name('settings.defaults');
    Route::get('/dashboard8', [DashboardController::class, 'index'])->name('settings.index');

    // Group for user-specific news routes
    Route::prefix('user')->as('user.')->group(function() {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('news')->as('news.')->group(function () {
            Route::get('/', [NewsController::class, 'index'])->name('index');
            Route::get('create', [NewsController::class, 'create'])->name('create');
            Route::post('/', [NewsController::class, 'store'])->name('store');
            Route::get('{news}', [NewsController::class, 'show'])->name('show');
            Route::get('{news}/edit', [NewsController::class, 'edit'])->name('edit');
            Route::put('{news}', [NewsController::class, 'update'])->name('update');
            Route::delete('{news}', [NewsController::class, 'destroy'])->name('destroy');
            Route::post('upload-image', [NewsController::class, 'uploadImage'])->name('upload-image');
        });

        Route::prefix('channels')->as('channels.')->group(function () {
            Route::get('/', [ChannelController::class, 'index'])->name('index');
            Route::get('create', [ChannelController::class, 'create'])->name('create');
            Route::post('/', [ChannelController::class, 'store'])->name('store');
            Route::get('{channel}', [ChannelController::class, 'show'])->name('show');
            Route::get('{channel}/edit', [ChannelController::class, 'edit'])->name('edit');
            Route::put('{channel}', [ChannelController::class, 'update'])->name('update');
            Route::delete('{channel}', [ChannelController::class, 'destroy'])->name('destroy');
            Route::post('upload-image', [ChannelController::class, 'uploadImage'])->name('upload-image');
        });
        Route::resource('ads', AdController::class);
        Route::resource('api-keys', ApiKeyController::class);

        Route::get('/default-settings/index', [DefaultSettingController::class, 'edit'])->name('default-settings.index');
        Route::put('/default-settings', [DefaultSettingController::class, 'update'])->name('default-settings.update');
   
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
        Route::get('/documentations/webhooks/news', [DocumentationController::class, 'webhookNews'])->name('documentations.webhooks.news');

    });

    
});
Route::get('/valyuta', [CurrencyController::class, 'index'])->name('currency.index');
Route::get('/namaz-vaxti', [NamazTimeController::class, 'index'])->name('namaz-time.index');

Route::get('/hava-haqqinda', [WeatherController::class, 'index'])->name('weather.index');
Route::get('/hava-haqqinda/{slug}', [WeatherController::class, 'city'])->name('weather.city');

Route::post('/ajax/subscribe/{id}', [AjaxController::class, 'subscribe'])->name('ajax.subscribe');
Route::post('/ajax/un_subscribe/{id}', [AjaxController::class, 'unSubscribe'])->name('ajax.unsubscribe');
Route::post('/ajax/like/{id}', [AjaxController::class, 'like'])->name('ajax.like');
Route::post('/ajax/remove_like/{id}', [AjaxController::class, 'removeLike'])->name('ajax.remove_like');
Route::post('/ajax/dislike/{id}', [AjaxController::class, 'dislike'])->name('ajax.dislike');
Route::post('/ajax/remove_dislike/{id}', [AjaxController::class, 'removeDislike'])->name('ajax.remove_dislike');


Route::get('/ad/click/{id}', [SiteAdController::class, 'click'])->name('ad.click');
Route::get('/tags/{tag}', [SiteNewsController::class, 'tag'])->name('news.tag');

Route::get('/{channel}/{slug}', [SiteNewsController::class, 'show'])->name('news.show');


Route::get('/{url}', [SiteChannelController::class, 'inner'])->name('channel.show');
