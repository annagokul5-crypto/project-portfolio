<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppBotController;
use App\Models\Setting;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\WhatsAppController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Telegram Bot Routes
Route::post('/telegram/webhook', [App\Http\Controllers\TelegramController::class, 'webhook']);
Route::get('/telegram/set-webhook', [App\Http\Controllers\TelegramController::class, 'setWebhook']);
Route::get('/telegram/send-test', [App\Http\Controllers\TelegramController::class, 'sendTestMessage']);

// WhatsApp Bot Routes
Route::post('/whatsapp/webhook', [WhatsAppBotController::class, 'webhook']);
Route::get('/whatsapp/test', [WhatsAppBotController::class, 'sendTest']);

Route::get('/portfolio', [PortfolioController::class, 'index']);


Route::get('/footer-year', function () {
    $year = Setting::where('key', 'footer_year')->value('value') ?? date('Y');
    return response()->json(['year' => $year]);
});

Route::post('/twilio/webhook', [WhatsAppBotController::class, 'webhook']);
