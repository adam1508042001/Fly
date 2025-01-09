<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Runway;
use Illuminate\Http\Request;

class RunwayController extends Controller
{
    // Liste tous les runways
    public function index()
    {
        $runways = Runway::all();
        return response()->json($runways, 200);
    }

    // Enregistre un nouveau runway
    public function store(Request $request)
    {
        $validated = $request->validate([
            'state' => 'required|string',
            'size' => 'required|integer',
            'id_airport' => 'required|exists:airports,id_airport',
        ]);

        $runway = Runway::create($validated);
        return response()->json($runway, 201); // 201 Created
    }

    // Affiche un runway spécifique
    public function show($id_runway)
    {
        $runway = Runway::find($id_runway);

        if (!$runway) {
            return response()->json(['message' => 'Runway not found'], 404);
        }

        return response()->json($runway, 200);
    }

    // Met à jour un runway
    public function update(Request $request, $id_runway)
    {
        $runway = Runway::find($id_runway);

        if (!$runway) {
            return response()->json(['message' => 'Runway not found'], 404);
        }

        $validated = $request->validate([
            'state' => 'sometimes|required|string',
            'size' => 'sometimes|required|integer',
            'id_airport' => 'sometimes|required|exists:airports,id_airport',
        ]);

        $runway->update($validated);
        return response()->json($runway, 200);
    }

    // Supprime un runway
    public function destroy($id_runway)
    {
        $runway = Runway::find($id_runway);

        if (!$runway) {
            return response()->json(['message' => 'Runway not found'], 404);
        }

        $runway->delete();
        return response()->json(['message' => 'Runway deleted successfully'], 200);
    }



    public function partialUpdate(Request $request, $id_runway)
{
    $runway = Runway::find($id_runway);

    if (!$runway) {
        return response()->json(['message' => 'Runway not found'], 404);
    }

    // Valider uniquement les champs fournis dans la requête
    $validated = $request->validate([
        'state' => 'sometimes|required|string',
        'size' => 'sometimes|required|integer',
        'id_airport' => 'sometimes|required|exists:airports,id_airport',
    ]);

    // Met à jour uniquement les champs fournis
    $runway->update($validated);

    return response()->json($runway, 200); // Retourne le runway mis à jour
}



}

