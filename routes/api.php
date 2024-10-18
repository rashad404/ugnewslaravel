<?php

use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/valyuta/{date}',[ApiController::class,"fetchAndConvertData"]);

Route::post('/webhook/news', [WebhookController::class, 'handleWebhook']);
Route::put('/webhook/news/{id}', [WebhookController::class, 'updateNews']);
Route::delete('/webhook/news/{id}', [WebhookController::class, 'deleteNews']);
Route::get('/webhook/categories', [WebhookController::class, 'getCategories']);
Route::get('/webhook/cities', [WebhookController::class, 'getCities']);
Route::get('/webhook/news/{id}', [WebhookController::class, 'getNews']);


Route::get('/countries', [CountryController::class, 'index'])->name('api.countries');
Route::get('/search', [SearchController::class, 'search'])->name('api.search');
