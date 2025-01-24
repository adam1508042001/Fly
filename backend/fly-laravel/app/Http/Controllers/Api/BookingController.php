<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::all();
        return response()->json($bookings, 200);
    }

    public function store(Request $request)
    {
        // Vérifier si l'utilisateur est authentifié
        $user = Auth::user();
        Log::info('Authenticated user:', ['user' => $user]);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'date_hour' => 'required|date_format:Y-m-d H:i:s',
            'place_reserved' => 'required|integer',
            'state' => 'required|string|max:50',
            'suitcase_authorized' => 'required|boolean',
            'weight_authorized' => 'required|numeric',
            'id_fly' => 'required|exists:flys,id_fly',
        ]);

        // Ajouter l'ID du client à la réservation
        $validated['id_client'] = $user->id;
        Log::info('Validated data with id_client:', $validated);

        try {
            $booking = Booking::create($validated);
            return response()->json($booking, 201);
        } catch (\Exception $e) {
            Log::error('Error creating booking:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    // Affiche une réservation spécifique
    public function show($id_booking)
    {
        $booking = Booking::find($id_booking);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return response()->json($booking, 200);
    }

    // Met à jour une réservation
    public function update(Request $request, $id_booking)
    {
        $booking = Booking::find($id_booking);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $validated = $request->validate([
            'date_hour' => 'required|date_format:Y-m-d H:i:s',
            'place_reserved' => 'required|integer',
            'state' => 'required|string|max:50',
            'suitcase_authorized' => 'required|boolean',
            'weight_authorized' => 'required|numeric',
            'id_fly' => 'required|exists:flys,id_fly',
            'id_client' => 'required|exists:clients,id_client',
        ]);

        $booking->update($validated);
        return response()->json($booking, 200);
    }

    // Mise à jour partielle (PATCH)
    public function partialUpdate(Request $request, $id_booking)
    {
        $booking = Booking::find($id_booking);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Valider uniquement les champs fournis dans la requête
        $validated = $request->validate([
            'date_hour' => 'sometimes|required|date_format:Y-m-d H:i:s',
            'place_reserved' => 'sometimes|required|integer',
            'state' => 'sometimes|required|string|max:50',
            'suitcase_authorized' => 'sometimes|required|boolean',
            'weight_authorized' => 'sometimes|required|numeric',
            'id_fly' => 'sometimes|required|exists:flys,id_fly',
            'id_client' => 'sometimes|required|exists:clients,id_client',
        ]);

        $booking->update($validated);
        return response()->json($booking, 200);
    }

    // Supprime une réservation
    public function destroy($id_booking)
    {
        $booking = Booking::find($id_booking);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $booking->delete();
        return response()->json(['message' => 'Booking deleted successfully'], 200);
    }
}
