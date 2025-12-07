<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\HeroController;
use App\Http\Controllers\PredictionController;

Route::get('/', function () {
    $accounts = \App\Models\Account::all();
    return view('home', compact('accounts'));
})->name('home');

Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
Route::get('/accounts/create', [AccountController::class, 'create'])->name('accounts.create');
Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');

// MLBB Heroes Routes
Route::get('/heroes', [HeroController::class, 'index'])->name('heroes.index');
Route::get('/heroes/search', [HeroController::class, 'search'])->name('heroes.search');
Route::get('/heroes/{id}', [HeroController::class, 'show'])->name('heroes.show');

// Price Prediction Routes
Route::get('/prediction', [PredictionController::class, 'index'])->name('prediction.index');
Route::post('/prediction/predict', [PredictionController::class, 'predict'])->name('prediction.predict');
Route::get('/prediction/options', [PredictionController::class, 'getRankOptions'])->name('prediction.options');
