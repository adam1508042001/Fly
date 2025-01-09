<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlaneController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\RunwayController;


Route::get('planes', [PlaneController::class, 'index']); // Récupérer toutes les avions
Route::get('planes/{id_plane}', [PlaneController::class, 'show']); // Récupérer un avion par son id_plane
Route::post('planes', [PlaneController::class, 'store']); // Créer un nouvel avion
Route::put('planes/{id_plane}', [PlaneController::class, 'update']); // Mettre à jour un avion
Route::delete('planes/{id_plane}', [PlaneController::class, 'destroy']); // Supprimer un avion
Route::patch('/planes/{id_plane}', [PlaneController::class, 'patch']);



Route::prefix('airports')->group(function() {
    // Obtenir tous les aéroports
    Route::get('/', [AirportController::class, 'index']);

    // Obtenir un aéroport par ID
    Route::get('{id_airport}', [AirportController::class, 'show']);

    // Créer un aéroport
    Route::post('/', [AirportController::class, 'store']);

    // Mettre à jour un aéroport par ID
    Route::put('{id_airport}', [AirportController::class, 'update']);

    // Supprimer un aéroport par ID
    Route::delete('{id_airport}', [AirportController::class, 'destroy']);

    // Mettre à jour partiellement un aéroport par ID
Route::patch('{id_airport}', [AirportController::class, 'patch']);

});




Route::prefix('runways')->group(function () {
    Route::get('/', [RunwayController::class, 'index']); // Liste tous les runways
    Route::post('/', [RunwayController::class, 'store']); // Enregistre un nouveau runway
    Route::get('/{id_runway}', [RunwayController::class, 'show']); // Affiche un runway spécifique
    Route::put('/{id_runway}', [RunwayController::class, 'update']); // Met à jour un runway
    Route::delete('/{id_runway}', [RunwayController::class, 'destroy']); // Supprime un runway
    Route::patch('/{id_runway}', [RunwayController::class, 'partialUpdate']); // Mise à jour partielle
});






Route::prefix('clients')->group(function () {
    Route::get('/', [ClientController::class, 'index']); // Liste tous les clients
    Route::post('/', [ClientController::class, 'store']); // Crée un nouveau client
    Route::get('/{id_client}', [ClientController::class, 'show']); // Affiche un client spécifique
    Route::put('/{id_client}', [ClientController::class, 'update']); // Met à jour un client
    Route::patch('/{id_client}', [ClientController::class, 'partialUpdate']); // Mise à jour partielle
    Route::delete('/{id_client}', [ClientController::class, 'destroy']); // Supprime un client
});




Route::prefix('bookings')->group(function () {
    Route::get('/', [BookingController::class, 'index']); // Liste toutes les réservations
    Route::post('/', [BookingController::class, 'store']); // Crée une nouvelle réservation
    Route::get('/{id_booking}', [BookingController::class, 'show']); // Affiche une réservation spécifique
    Route::put('/{id_booking}', [BookingController::class, 'update']); // Met à jour une réservation
    Route::patch('/{id_booking}', [BookingController::class, 'partialUpdate']); // Mise à jour partielle
    Route::delete('/{id_booking}', [BookingController::class, 'destroy']); // Supprime une réservation
});
