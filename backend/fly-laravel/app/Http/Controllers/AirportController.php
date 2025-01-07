<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    // Afficher tous les aéroports
    public function index()
    {
        $airports = Airport::all();
        return response()->json($airports, 200);
    }

    // Afficher un aéroport par son ID
    public function show($id_airport)
    {
        $airport = Airport::find($id_airport);
        
        if ($airport) {
            return response()->json($airport, 200);
        } else {
            return response()->json(['message' => 'Airport not found'], 404);
        }
    }

    // Créer un nouvel aéroport
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        $airport = Airport::create([
            'name' => $validated['name'],
            'city' => $validated['city'],
        ]);

        return response()->json($airport, 201);
    }

    // Mettre à jour un aéroport par son ID
    public function update(Request $request, $id_airport)
    {
        $airport = Airport::find($id_airport);

        if ($airport) {
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
            ]);

            // Mettre à jour seulement les champs spécifiés
            $airport->update(array_filter($validated)); // array_filter pour ne pas inclure les champs nulls

            return response()->json($airport, 200);
        } else {
            return response()->json(['message' => 'Airport not found'], 404);
        }
    }

    // Supprimer un aéroport par son ID
    public function destroy($id_airport)
    {
        $airport = Airport::find($id_airport);

        if ($airport) {
            $airport->delete();
            return response()->json(['message' => 'Airport deleted'], 200);
        } else {
            return response()->json(['message' => 'Airport not found'], 404);
        }
    }


    public function patch(Request $request, $id_airport)
{
    $airport = Airport::find($id_airport);

    if ($airport) {
        // Validation pour les champs que tu souhaites permettre de mettre à jour
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        // Mettre à jour les champs qui sont fournis dans la requête
        $airport->update(array_filter($validated)); // array_filter pour ignorer les champs nuls

        return response()->json($airport, 200);
    } else {
        return response()->json(['message' => 'Airport not found'], 404);
    }
}

}
