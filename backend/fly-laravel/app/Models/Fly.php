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

    // Définir les relations avec d'autres modèles si nécessaire
    public function plane()
    {
        return $this->belongsTo(Plane::class, 'id_plane');
    }

    public function airportLanding()
    {
        return $this->belongsTo(Airport::class, 'id_airport_landing');
    }

    public function airportFlyOff()
    {
        return $this->belongsTo(Airport::class, 'id_airport_fly_off');
    }
}
