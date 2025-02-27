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
});

Route::get('/country', function () {
    return view('country');
})->middleware(['auth', 'verified'])->name('country');

Route::get('/state', function () {
    return view('state');
})->middleware(['auth', 'verified'])->name('state');

Route::get('/country-dashboard', [CountryController::class, 'countryDashboard'])->name('country.dashboard');
Route::get('/users-by-state', [CountryController::class, 'getUsersByState']);

require __DIR__.'/auth.php';
