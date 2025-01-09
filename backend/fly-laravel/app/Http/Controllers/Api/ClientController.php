<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Liste tous les clients
    public function index()
    {
        $clients = Client::all();
        return response()->json($clients, 200);
    }

    // Crée un nouveau client
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:clients,email',
            'status' => 'required|string|max:50',
        ]);

        $client = Client::create($validated);
        return response()->json($client, 201); // 201 Created
    }

    // Affiche un client spécifique
    public function show($id_client)
    {
        $client = Client::find($id_client);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        return response()->json($client, 200);
    }

    // Met à jour un client
    public function update(Request $request, $id_client)
    {
        $client = Client::find($id_client);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:clients,email,' . $client->id_client . ',id_client',
            'status' => 'required|string|max:50',
        ]);

        $client->update($validated);
        return response()->json($client, 200);
    }

    // Mise à jour partielle (PATCH)
    public function partialUpdate(Request $request, $id_client)
    {
        $client = Client::find($id_client);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        // Valider uniquement les champs présents dans la requête
        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'date_of_birth' => 'sometimes|required|date',
            'email' => 'sometimes|required|email|unique:clients,email,' . $client->id_client . ',id_client',
            'status' => 'sometimes|required|string|max:50',
        ]);

        $client->update($validated);
        return response()->json($client, 200);
    }

    // Supprime un client
    public function destroy($id_client)
    {
        $client = Client::find($id_client);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $client->delete();
        return response()->json(['message' => 'Client deleted successfully'], 200);
    }
}
