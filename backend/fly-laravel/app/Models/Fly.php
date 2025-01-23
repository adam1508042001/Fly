<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fly extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_hour_landing',
        'date_hour_fly_off',
        'state',
        'id_plane',
        'id_airport_landing',
        'id_airport_fly_off',
    ];

    protected $table = 'flys';

     // Définir le nom de la clé primaire
     protected $primaryKey = 'id_fly';

    // Définir les relations avec d'autres modèles si nécessaire
    // Relation avec Plane (avion)
    public function plane()
{
    return $this->belongsTo(Plane::class, 'id_plane', 'id_plane');
}


    // Relation avec Airport (aéroport de décollage)
    public function airportFlyOff()
    {
        return $this->belongsTo(Airport::class, 'id_airport_fly_off');
    }

    // Relation avec Airport (aéroport d'atterrissage)
    public function airportLanding()
    {
        return $this->belongsTo(Airport::class, 'id_airport_landing');
    }

    // Relation avec Client (les clients qui prennent ce vol)
    public function clients()
    {
        return $this->belongsToMany(Client::class, 'take', 'id_fly', 'id_client');
    }

    // Relation avec Booking (les réservations associées à ce vol)
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_fly');
    }


}
