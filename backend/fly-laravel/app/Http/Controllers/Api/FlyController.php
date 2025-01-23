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

    // Mettre à jour le statut de l'avion en "used"
    if ($plane) {
        $plane->state = 'used'; // Par exemple : 'used' pour signaler que l'avion est en service
        $plane->save();
    }

    // Mettre à jour le statut des pistes associées
    if ($runwayLanding) {
        $runwayLanding->state = 'used'; // Par exemple : 'used' pour signaler qu'elle est utilisée
        $runwayLanding->save();
    }

    if ($runwayFlyOff) {
        $runwayFlyOff->state = 'used';
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
    $fly = Fly::with(['plane', 'airportLanding', 'airportFlyOff'])->find($id);

    if (!$fly) {
        return response()->json(['message' => 'Flight not found'], 404);
    }

    // Vérification des restrictions
    if ($fly->plane && $fly->plane->state === 'flying') {
        return response()->json(['message' => 'Cannot modify a flight when the associated plane is already in flight.'], 400);
    }

    $timeToFlyOff = strtotime($fly->date_hour_fly_off) - time();
    $timeToFlyOffMinutes = $timeToFlyOff / 60;

    if ($timeToFlyOffMinutes <= 1) {
        if ($request->has('date_hour_fly_off') || $request->has('date_hour_landing') ||
            $request->has('id_plane') || $request->has('id_airport_fly_off') || $request->has('id_airport_landing')) {
            return response()->json(['message' => 'Cannot modify flight information less than 1 minutes before departure.'], 400);
        }
    }

    // Validation des données entrantes
    $this->validateFlightData($request);

    // Vérifier si l'avion ou les pistes sont disponibles
    if ($request->has('id_plane')) {
        $plane = Plane::find($request->id_plane);
        if ($plane && $plane->state === 'flying') {
            return response()->json(['message' => 'The selected plane is currently in flight.'], 400);
        }
    }

    if ($request->has('id_airport_fly_off')) {
        $runwayFlyOff = Runway::where('id_airport', $request->id_airport_fly_off)->first();
        if ($runwayFlyOff && $runwayFlyOff->state === 'used') {
            return response()->json(['message' => 'The runway at the departure airport is already in use.'], 400);
        }
    }

    if ($request->has('id_airport_landing')) {
        $runwayLanding = Runway::where('id_airport', $request->id_airport_landing)->first();
        if ($runwayLanding && $runwayLanding->state === 'used') {
            return response()->json(['message' => 'The runway at the landing airport is already in use.'], 400);
        }
    }

    // Mise à jour du vol
    $fly->update($request->all());

    // Mise à jour des entités liées
    $this->updateRelatedEntities($fly);

    return response()->json($fly, 200);
}

/**
 * Validation des données d'entrée.
 */
private function validateFlightData($request)
{
    $request->validate([
        'date_hour_landing' => 'date|after:date_hour_fly_off',
        'date_hour_fly_off' => 'date|before:date_hour_landing',
        'state' => 'string',
        'id_plane' => 'exists:planes,id_plane',
        'id_airport_landing' => 'exists:airports,id_airport',
        'id_airport_fly_off' => 'exists:airports,id_airport',
    ]);
}

/**
 * Met à jour les entités liées au vol.
 */
private function updateRelatedEntities(Fly $fly)
{
    // Mise à jour de l'état de l'avion
    if ($fly->plane) {
        $fly->plane->state = ($fly->state === 'Cancelled') ? 'landed' : 'used';
        $fly->plane->save();
    }

    // Mise à jour des pistes des aéroports
    $runwayFlyOff = Runway::where('id_airport', $fly->id_airport_fly_off)->first();
    $runwayLanding = Runway::where('id_airport', $fly->id_airport_landing)->first();

    if ($fly->state === 'Cancelled') {
        if ($runwayFlyOff) {
            $runwayFlyOff->state = 'not-used';
            $runwayFlyOff->save();
        }
        if ($runwayLanding) {
            $runwayLanding->state = 'not-used';
            $runwayLanding->save();
        }
    } elseif ($fly->state === 'scheduled') {
        if ($runwayFlyOff) {
            $runwayFlyOff->state = 'used';
            $runwayFlyOff->save();
        }
        if ($runwayLanding) {
            $runwayLanding->state = 'used';
            $runwayLanding->save();
        }
    }
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
        $runwayLanding->state = 'not-used';
        $runwayLanding->save();
    }

    if ($runwayFlyOff) {
        $runwayFlyOff->state = 'not-used';
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
