<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});
Route::get('/project/ecommerce', function () {
    return view('eview');
});

Route::get('/project/kitconnect', function () {
    return view('aview');
});

use Illuminate\Http\Request;

Route::post('/contact', function (Request $request) {
    // Here you can handle the contact form, e.g., send email, store to database, etc.
    // For now, just redirect back with a success message
    return back()->with('success', 'Message sent!');
})->name('contact.submit');

use App\Http\Controllers\WhatsAppController;

// Test route to send WhatsApp message
Route::get('/test-whatsapp', [WhatsAppController::class, 'sendTest']);

// Webhook to receive WhatsApp messages
Route::post('/whatsapp/webhook', [WhatsAppController::class, 'receiveMessage']);
