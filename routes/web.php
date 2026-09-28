<?php

use App\Http\Controllers\AiBroadcastController;
use App\Http\Controllers\AiCaptionGeneratorController;
use App\Http\Controllers\AiDailyReporterController;
use App\Http\Controllers\AiDataParserController;
use App\Http\Controllers\AiEmailController;
use App\Http\Controllers\AiEmailResponseController;
use App\Http\Controllers\AiMeetingMinutesController;
use App\Http\Controllers\AiPdfSummarizerController;
use App\Http\Controllers\AiSuratGeneratorController;
use App\Http\Controllers\AiTaskBreakdownController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SpamCheckerController;
use Illuminate\Support\Facades\Route;

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

// 5. Halaman AI Email Response
Route::get('/ai-email-response', function () {
    return view('ai-email-response');
})->name('ai-email-response');

Route::post('/ai-email-response/generate', [AiEmailResponseController::class, 'generate'])->name('ai-email-response.generate');

// 6. Halaman AI PDF Summarizer
Route::get('/ai-pdf-summarizer', function () {
    return view('ai-pdf-summarizer');
})->name('ai-pdf-summarizer');

Route::post('/ai-pdf-summarizer/generate', [AiPdfSummarizerController::class, 'generate'])->name('ai-pdf-summarizer.generate');

// 7. Halaman AI Broadcast
Route::get('/ai-broadcast', function () {
    return view('ai-broadcast');
})->name('ai-broadcast');

Route::post('/ai-broadcast/generate', [AiBroadcastController::class, 'generate'])->name('ai-broadcast.generate');

// 8. Halaman AI Meeting Minutes
Route::get('/ai-meeting-minutes', function () {
    return view('ai-meeting-minutes');
})->name('ai-meeting-minutes');

Route::post('/ai-meeting-minutes/generate', [AiMeetingMinutesController::class, 'generate'])->name('ai-meeting-minutes.generate');

// 9. Halaman AI Daily Reporter
Route::get('/ai-daily-reporter', function () {
    return view('ai-daily-reporter');
})->name('ai-daily-reporter');

Route::post('/ai-daily-reporter/generate', [AiDailyReporterController::class, 'generate'])->name('ai-daily-reporter.generate');

// 10. Halaman AI Caption Generator
Route::get('/ai-caption-generator', function () {
    return view('ai-caption-generator');
})->name('ai-caption-generator');

Route::post('/ai-caption-generator/generate', [AiCaptionGeneratorController::class, 'generate'])->name('ai-caption-generator.generate');

// 11. Halaman AI Surat Generator
Route::get('/ai-surat-generator', function () {
    return view('ai-surat-generator');
})->name('ai-surat-generator');

Route::post('/ai-surat-generator/generate', [AiSuratGeneratorController::class, 'generate'])->name('ai-surat-generator.generate');

// 12. Halaman AI Data Parser
Route::get('/ai-data-parser', function () {
    return view('ai-data-parser');
})->name('ai-data-parser');

Route::post('/ai-data-parser/generate', [AiDataParserController::class, 'generate'])->name('ai-data-parser.generate');

// 13. Halaman AI Task Breakdown
Route::get('/ai-task-breakdown', function () {
    return view('ai-task-breakdown');
})->name('ai-task-breakdown');

Route::post('/ai-task-breakdown/generate', [AiTaskBreakdownController::class, 'generate'])->name('ai-task-breakdown.generate');
