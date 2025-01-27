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


Route::middleware('auth:sanctum')->group(function () {
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
        Route::get('/', [BookingController::class, 'index']);
        Route::post('/', [BookingController::class, 'store']);
        Route::get('/{id_booking}', [BookingController::class, 'show']);
        Route::put('/{id_booking}', [BookingController::class, 'update']);
        Route::patch('/{id_booking}', [BookingController::class, 'partialUpdate']);
        Route::delete('/{id_booking}', [BookingController::class, 'destroy']);
    });

    Route::prefix('flies')->group(function () {
        Route::get('/', [FlyController::class, 'index']);
        Route::post('/', [FlyController::class, 'store']);
        Route::get('/{id}', [FlyController::class, 'show']);
        Route::put('/{id}', [FlyController::class, 'update']);
        Route::delete('/{id}', [FlyController::class, 'destroy']);
        Route::post('/{id}/cancel', [FlyController::class, 'cancel']);
    });
});