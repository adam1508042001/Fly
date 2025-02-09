<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlaneController;
use App\Http\Controllers\Api\AirportController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\RunwayController;
use App\Http\Controllers\Api\MailController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\FlyController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('planes')->group(function () {
    Route::get('/', [PlaneController::class, 'index']);
    Route::post('/', [PlaneController::class, 'store']);
    Route::get('/{id_plane}', [PlaneController::class, 'show']);
    Route::put('/{id_plane}', [PlaneController::class, 'update']);
    Route::delete('/{id_plane}', [PlaneController::class, 'destroy']);
    Route::patch('/{id_plane}', [PlaneController::class, 'patch']);
});

Route::prefix('airports')->group(function() {
    Route::get('/', [AirportController::class, 'index']);
    Route::post('/', [AirportController::class, 'store']);
    Route::get('/{id_airport}', [AirportController::class, 'show']);
    Route::put('/{id_airport}', [AirportController::class, 'update']);
    Route::delete('/{id_airport}', [AirportController::class, 'destroy']);
    Route::patch('/{id_airport}', [AirportController::class, 'patch']);
});

Route::prefix('runways')->group(function () {
    Route::get('/', [RunwayController::class, 'index']);
    Route::post('/', [RunwayController::class, 'store']);
    Route::get('/{id_runway}', [RunwayController::class, 'show']);
    Route::put('/{id_runway}', [RunwayController::class, 'update']);
    Route::delete('/{id_runway}', [RunwayController::class, 'destroy']);
    Route::patch('/{id_runway}', [RunwayController::class, 'partialUpdate']);
});

Route::prefix('clients')->group(function () {
    Route::get('/', [ClientController::class, 'index']);
    Route::post('/', [ClientController::class, 'store']);
    Route::get('/{id_client}', [ClientController::class, 'show']);
    Route::put('/{id_client}', [ClientController::class, 'update']);
    Route::patch('/{id_client}', [ClientController::class, 'partialUpdate']);
    Route::delete('/{id_client}', [ClientController::class, 'destroy']);
});





Route::prefix('bookings')->group(function () {

    Route::get('/', [BookingController::class, 'index']); // Liste toutes les réservations
    Route::post('/', [BookingController::class, 'store']); // Crée une nouvelle réservation
    Route::get('/{id_booking}', [BookingController::class, 'show']); // Affiche une réservation spécifique
    Route::put('/{id_booking}', [BookingController::class, 'update']); // Met à jour une réservation
    Route::patch('/{id_booking}', [BookingController::class, 'partialUpdate']); // Mise à jour partielle
    Route::delete('/{id_booking}', [BookingController::class, 'destroy']); // Supprime une réservation
});




// Liste tous les vols
Route::get('/flies', [FlyController::class, 'index']);

// Crée un vol (planification)
Route::post('/flies', [FlyController::class, 'store']);

// Détails d'un vol spécifique
Route::get('/flies/{id}', [FlyController::class, 'show']);

// Met à jour un vol
Route::put('/flies/{id}', [FlyController::class, 'update']);

// Supprime un vol
Route::delete('/flies/{id}', [FlyController::class, 'destroy']);

Route::post('/flies/{id}/cancel', [FlyController::class, 'cancel']);
