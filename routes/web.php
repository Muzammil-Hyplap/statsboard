<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'getDashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/countries', [DashboardController::class, 'getCountries'])->middleware(['auth', 'verified'])->name('countries');
});

Route::get('/state', function () {
    return view('state');
})->middleware(['auth', 'verified'])->name('state');

Route::get('/country', [CountryController::class, 'index'])->middleware(['auth', 'verified'])->name('country');
Route::get('/fetch-state-data', [CountryController::class, 'fetchStateData'])
    ->middleware(['auth', 'verified'])
    ->name('fetchStateData');

Route::get('/fetch-city-data', [CountryController::class, 'fetchCityData'])
    ->middleware(['auth', 'verified'])
    ->name('fetchCityData');

    Route::get('/fetch-gender-data', [CountryController::class, 'fetchGenderData'])
    ->middleware(['auth', 'verified'])
    ->name('fetchGenderData');

require __DIR__ . '/auth.php';
