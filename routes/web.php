<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NewsController;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
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
    });

});
