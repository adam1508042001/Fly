<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Réservation</title>
</head>
<body>
    <h1>Détails de la Réservation</h1>

    <p><strong>Réservation ID:</strong> {{ $booking->id_booking }}</p>
    
    <h2>Informations du Client:</h2>
    <p><strong>Prénom:</strong> {{ $booking->client->first_name }}</p>
    <p><strong>Nom:</strong> {{ $booking->client->last_name }}</p>
    <p><strong>Date de naissance:</strong> 
        {{ \Carbon\Carbon::parse($booking->client->date_of_birth)->format('d/m/Y') }}
    </p>
    <p><strong>Email:</strong> {{ $booking->client->email }}</p>

    <strong><h2>Informations du Vol :</h2></strong>
    <p><strong>Date et heure de départ:</strong> 
        {{ \Carbon\Carbon::parse($booking->fly->date_hour_fly_off)->format('d/m/Y H:i') }}
    </p>
    <p><strong>Date et heure d'arrivée:</strong> 
        {{ \Carbon\Carbon::parse($booking->fly->date_hour_landing)->format('d/m/Y H:i') }}
    </p>

    <h2>Informations de la Réservation</h2>
    <p><strong>Places réservées:</strong> {{ $booking->place_reserved }}</p>
    <p><strong>État:</strong> {{ $booking->state }}</p>
    <p><strong>Poids autorisé:</strong> {{ $booking->weight_authorized ?? 'Non spécifié' }}</p>
    <p><strong>Date et Heure de la réservation:</strong> 
        {{ \Carbon\Carbon::parse($booking->date_hour)->format('d/m/Y H:i') }}
    </p>
</body>
</html>
