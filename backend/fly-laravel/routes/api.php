<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlaneController;

Route::get('planes', [PlaneController::class, 'index']); // Récupérer toutes les avions
Route::get('planes/{id_plane}', [PlaneController::class, 'show']); // Récupérer un avion par son id_plane
Route::post('planes', [PlaneController::class, 'store']); // Créer un nouvel avion
Route::put('planes/{id_plane}', [PlaneController::class, 'update']); // Mettre à jour un avion
Route::delete('planes/{id_plane}', [PlaneController::class, 'destroy']); // Supprimer un avion
Route::patch('/planes/{id_plane}', [PlaneController::class, 'patch']);