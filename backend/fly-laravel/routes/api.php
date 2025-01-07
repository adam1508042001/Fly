<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlaneController;
use App\Http\Controllers\AirportController;


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

