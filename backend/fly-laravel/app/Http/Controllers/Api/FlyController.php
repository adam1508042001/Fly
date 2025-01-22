<?php

namespace App\Http\Controllers\Api;

use App\Models\Fly;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plane;
use App\Models\Airport;
use App\Models\Runway;

class FlyController extends Controller
{
    /**
     * Liste tous les vols.
     */
    public function index()
    {
        // Charger les vols avec les relations
        $flys = Fly::with(['plane', 'airportLanding', 'airportFlyOff'])->get();
        return response()->json($flys);
    }

    /**
     * Crée un vol (planification).
     */
   
/**
 * Crée un vol (planification).
 */
public function store(Request $request)
{
    // Validation des données entrantes
    $request->validate([
        'date_hour_landing' => 'required|date|after:date_hour_fly_off',
        'date_hour_fly_off' => 'required|date|before:date_hour_landing',
        'state' => 'required|string',
        'id_plane' => 'required|exists:planes,id_plane',
        'id_airport_landing' => 'required|exists:airports,id_airport',
        'id_airport_fly_off' => 'required|exists:airports,id_airport',
    ]);

    // Récupérer les modèles nécessaires
    $plane = Plane::find($request->id_plane);
    $airportLanding = Airport::find($request->id_airport_landing);
    $airportFlyOff = Airport::find($request->id_airport_fly_off);

    // Vérifier que l'avion n'a pas atteint sa capacité maximale
    $existingBookingsCount = $plane->flies()->withCount('bookings')->get()->sum('bookings_count');
    if ($existingBookingsCount >= $plane->max_place) {
        return response()->json(['message' => 'The plane has reached its maximum capacity'], 400);
    }

    // Récupérer les pistes des aéroports de décollage et d'atterrissage
    $runwayLanding = Runway::where('id_airport', $airportLanding->id_airport)->first();
    $runwayFlyOff = Runway::where('id_airport', $airportFlyOff->id_airport)->first();

    // Vérifier que la taille de l'avion est suffisante pour la piste de décollage
    if ($runwayLanding && $runwayLanding->size < $plane->size) {
        return response()->json(['message' => 'Runway at landing airport is too short for the plane'], 400);
    }

    if ($runwayFlyOff && $runwayFlyOff->size < $plane->size) {
        return response()->json(['message' => 'Runway at fly-off airport is too short for the plane'], 400);
    }

    // Création du vol
    $fly = Fly::create([
        'date_hour_landing' => $request->date_hour_landing,
        'date_hour_fly_off' => $request->date_hour_fly_off,
        'state' => $request->state,
        'id_plane' => $request->id_plane,
        'id_airport_landing' => $request->id_airport_landing,
        'id_airport_fly_off' => $request->id_airport_fly_off,
    ]);

    // Mettre à jour le statut de l'avion en "active"
    if ($plane) {
        $plane->state = 'active'; // Par exemple : 'active' pour signaler que l'avion est en service
        $plane->save();
    }

    // Mettre à jour le statut des pistes associées
    if ($runwayLanding) {
        $runwayLanding->state = 'inactive'; // Par exemple : 'inactive' pour signaler qu'elle est utilisée
        $runwayLanding->save();
    }

    if ($runwayFlyOff) {
        $runwayFlyOff->state = 'inactive';
        $runwayFlyOff->save();
    }

    // Retourner la réponse
    return response()->json($fly->load(['plane', 'airportLanding', 'airportFlyOff']), 201); // Inclure les relations dans la réponse
}

    /**
     * Détails d'un vol spécifique.
     */
    public function show($id)
    {
        $fly = Fly::with(['plane', 'airportLanding', 'airportFlyOff'])->where('id_fly', $id)->first();
    
        if (!$fly) {
            return response()->json(['message' => 'Flight not found'], 404);
        }
    
        return response()->json($fly);
    }

    /**
     * Met à jour un vol.
     */
    public function update(Request $request, $id)
    {
        $fly = Fly::find($id);

        if (!$fly) {
            return response()->json(['message' => 'Flight not found'], 404);
        }

        $request->validate([
            'date_hour_landing' => 'date|after:date_hour_fly_off',
            'date_hour_fly_off' => 'date|before:date_hour_landing',
            'state' => 'string',
            'id_plane' => 'exists:planes,id_plane',
            'id_airport_landing' => 'exists:airports,id_airport',
            'id_airport_fly_off' => 'exists:airports,id_airport',
        ]);

        $fly->update($request->all());
        return response()->json($fly);
    }



    /**
 * Annule un vol.
 */
public function cancel($id)
{
    // Récupérer le vol
    $fly = Fly::with('plane')->find($id); // Inclure l'avion associé

    if (!$fly) {
        return response()->json(['message' => 'Flight not found'], 404);
    }

    // Mise à jour de l'état du vol
    $fly->state = 'Cancelled';
    $fly->save();

    // Vérification et mise à jour de l'avion
    if ($fly->plane) {
        $plane = $fly->plane; // Récupération de l'avion lié
        $plane->state = 'landed'; // Mettre à jour le statut
        $plane->save();
    } else {
        return response()->json(['message' => 'No plane associated with this flight'], 404);
    }



    
    // Récupération des pistes et des aéroports
    $runwayLanding = Runway::where('id_airport', $fly->id_airport_landing)->first();
    $runwayFlyOff = Runway::where('id_airport', $fly->id_airport_fly_off)->first();

    //debogage

    if (!$runwayLanding) {
        return response()->json(['message' => 'Runway for landing airport not found'], 404);
    }

    if (!$runwayFlyOff) {
        return response()->json(['message' => 'Runway for fly-off airport not found'], 404);
    }



    if ($runwayLanding) {
        $runwayLanding->state = 'active';
        $runwayLanding->save();
    }

    if ($runwayFlyOff) {
        $runwayFlyOff->state = 'active';
        $runwayFlyOff->save();
    }

    return response()->json($fly->load('plane')); // Inclure l'avion dans la réponse
}




    /**
     * Supprime un vol.
     */
    public function destroy($id)
    {
        $fly = Fly::find($id);

        if (!$fly) {
            return response()->json(['message' => 'Flight not found'], 404);
        }

        $fly->delete();
        return response()->json(['message' => 'Flight deleted successfully']);
    }




    
}
