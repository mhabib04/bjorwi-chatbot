<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotManController;

// Route untuk halaman chatbot
Route::get('/', function () {
    return view('chatbot');
});

// Route untuk BotMan webhook
Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);