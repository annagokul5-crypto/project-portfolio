<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\WhatsappPdfController;
use App\Http\Controllers\ProjectController;


//Route::get('/', function () {
//    return view('index');
//});
Route::get('/project/ecommerce', function () {
    return view('eview');
});

Route::get('/project/kitconnect', function () {
    return view('aview');
});

Route::get('/project/portfolio', function () {
    return view('sections.pview'); // if file is in views/sections
});

use Illuminate\Http\Request;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\WhatsAppBotController;
use App\Http\Controllers\PortfolioController;



// Test route to send WhatsApp message
Route::get('/test-whatsapp', [WhatsAppController::class, 'sendTest']);


// Admin routes (protected)
Route::middleware('auth')->group(function () {
    Route::get('/admin/submissions', [ContactController::class, 'viewSubmissions'])->name('submissions.view');
    Route::get('/admin/submissions/download-pdf', [ContactController::class, 'downloadPDF'])->name('submissions.download-pdf');
});
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');

Route::get('/whatsapp/submissions-test-pdf', [WhatsappPdfController::class, 'test']);

Route::get('/', [PortfolioController::class, 'page']);


//Route::get('/', [PortfolioController::class, 'index']);

Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
