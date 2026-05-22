<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route Login
Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::get('/register', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/', function () {
    return Inertia::render('Home', [
        'message' => 'Selamat datang!'
    ]);
});

Route::get('/shop', function () {
    return Inertia::render('Shop/Index', [
        'message' => 'Selamat datang!'
    ]);
});

Route::get('/shop/{slug}', function ($slug) {
    return Inertia::render('Shop/Show', [
        'slug' => $slug,
    ]);
});

Route::get('/cart', function () {
    return Inertia::render('Cart/Index');
});

Route::middleware('auth')->group(function () {
    Route::get('/transaksi', function () {
        return Inertia::render('Transaction/Index');
    });

    Route::get('/profile', function () {
        return Inertia::render('Profile/Show');
    });
});
