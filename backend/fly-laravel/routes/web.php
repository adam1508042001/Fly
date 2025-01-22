<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Route pour la page de connexion
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Route pour la page d'inscription
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Route pour traiter la connexion
Route::post('/login', [AuthController::class, 'login']);

// Route pour traiter l'inscription
Route::post('/register', [AuthController::class, 'register']);
