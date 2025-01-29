<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Booking;

class BookingController extends Controller
{
    // Liste toutes les réservations
    public function index()
    {
        $bookings = Booking::all();
        return response()->json($bookings, 200);
    }

    public function store(Request $request)
    {
        // Validation des données de la réservation
        $validated = $request->validate([
            'place_reserved' => 'required|integer',
            'state' => 'required|string|max:50',
            'suitcase_authorized' => 'nullable|boolean',
            'weight_authorized' => 'nullable|numeric',
            'id_fly' => 'required|exists:flys,id_fly',
            'id_client' => 'required|exists:clients,id_client',
            'email' => 'required|email',
        ]);
    
        // Ajouter la date et l'heure actuelles
        $validated['date_hour'] = now();
    
        // Créer la réservation
        $booking = Booking::create($validated);
    
        // Charger les relations pour avoir plus d'infos sur le client et le vol
        $booking->load(['client', 'fly']); // Assurez-vous que ces relations existent dans votre modèle Booking
    
        // Générer le PDF à partir des données de réservation
        $pdf = PDF::loadView('emails.booking_email', ['booking' => $booking]);
    
        // Retourner le PDF généré
        return $pdf->download('reservation.pdf');
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

    // Vérifie si le vol a déjà décollé
    $fly = $booking->fly; // Assure-toi que la relation 'fly' est définie dans ton modèle Booking
    if ($fly->state === 'departed') {
        return response()->json(['message' => 'Cannot modify booking. The flight has already departed.'], 400);
    }

    // Validation des données
    $validated = $request->validate([
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
