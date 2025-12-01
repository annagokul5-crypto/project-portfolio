<?php

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

// Telegram Bot Routes
Route::post('/telegram/webhook', [App\Http\Controllers\TelegramController::class, 'webhook']);
Route::get('/telegram/set-webhook', [App\Http\Controllers\TelegramController::class, 'setWebhook']);
Route::get('/telegram/send-test', [App\Http\Controllers\TelegramController::class, 'sendTestMessage']);
