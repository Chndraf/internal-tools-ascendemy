<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SpamCheckerController;
use App\Http\Controllers\AiEmailController;

// 1. Direktori Tools sekarang menjadi Halaman Utama (Home)
Route::get('/', function () {
    return view('tools');
})->name('home');

// 2. Halaman Email Extractor
Route::get('/email-extractor', [HomeController::class, 'index'])->name('email-extractor');

// 3. Halaman Spam Checker
Route::get('/spam-checker', function () {
    return view('spam-checker');
})->name('spam-checker');

Route::post('/spam-checker/analyze', [SpamCheckerController::class, 'analyze'])->name('spam-checker.analyze');

// 4. Halaman AI Email Generator
Route::get('/ai-email-writer', function () {
    return view('ai-email-writer');
})->name('ai-email-writer');

Route::post('/ai-email-writer/generate', [AiEmailController::class, 'generate'])->name('ai-email-writer.generate');