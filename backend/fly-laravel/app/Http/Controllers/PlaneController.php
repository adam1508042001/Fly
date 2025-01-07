<?php

namespace App\Http\Controllers;

use App\Models\Plane;
use Illuminate\Http\Request;

class PlaneController extends Controller
{
    // Get all planes
    public function index()
    {
        $planes = Plane::all();
        return response()->json($planes);
    }

    
      // Récupérer un avion  spécifique par son id 
      public function show($id_plane)
      {
          $plane = Plane::find($id_plane);
          if ($plane) {
              return response()->json($plane, status:200);
          } else {
              return response()->json(['message' => 'Plane not found'], 404);
          }
      }



    // Create a new plane
    public function store(Request $request)
    {
        // Validation des données reçues
        $validatedData = $request->validate([
            'model' => 'required|string|max:255',
            'size' => 'required|integer',
            'max_place' => 'required|integer',
            'state' => 'required|string|max:255',
        ]);
    
        // Création du plane
        $plane = Plane::create([
            'model' => $validatedData['model'],
            'size' => $validatedData['size'],
            'max_place' => $validatedData['max_place'],
            'state' => $validatedData['state'],
        ]);
    
        return response()->json([
            'message' => 'Plane created successfully',
            'plane' => $plane
        ], 201); // 201 signifie "créé"
    }




    // Update an existing plane
    public function update(Request $request, $id_plane)
{
    // Récupération du plane avec find()
    $plane = Plane::find($id_plane);

    if (!$plane) {
        return response()->json(['message' => 'Plane not found'], 404);
    }

    // Validation des données reçues
    $validatedData = $request->validate([
        'model' => 'string|max:255',
        'size' => 'integer',
        'max_place' => 'integer',
        'state' => 'string|max:255',
    ]);

    // Mise à jour des champs du plane
    $plane->update($validatedData);

    return response()->json([
        'message' => 'goaaaaaaaaaaaaaaaaaal, vous avez resussssssseeeeeeeeeeeeeeeyyyyyyyyyyyyyyyyyyyyyyyyy',
        'plane' => $plane
    ], 200);
}




//delete a plane

public function destroy($id_plane)
{
    // Récupération du plane avec find()
    $plane = Plane::find($id_plane);

    if (!$plane) {
        return response()->json(['message' => 'Plane not found'], 404);
    }

    // Suppression du plane
    $plane->delete();

    return response()->json(['message' => 'staghfourallah avion a crush, tu las bien suprimeeeeeeeeeeeey'], 200);
}




public function patch(Request $request, $id_plane)
{
    // Récupération du plane avec find()
    $plane = Plane::find($id_plane);

    if (!$plane) {
        return response()->json(['message' => 'Plane not found'], 404);
    }

    // Validation des données reçues (ne valide que les champs envoyés)
    $validatedData = $request->validate([
        'model' => 'sometimes|string|max:255',
        'size' => 'sometimes|integer',
        'max_place' => 'sometimes|integer',
        'state' => 'sometimes|string|max:255',
    ]);

    // Mise à jour des champs du plane (mise à jour partielle)
    $plane->update($validatedData);

    return response()->json([
        'message' => 'Plane field(s) updated successfully',
        'plane' => $plane
    ], 200);
}


}

