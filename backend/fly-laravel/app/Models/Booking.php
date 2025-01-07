<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_hour',
        'place_reserved',
        'state',
        'suitcase_authorized',
        'weight_authorized',
        'id_fly',
        'id_client',
    ];

    // Relation avec le vol
    public function fly()
    {
        return $this->belongsTo(Fly::class, 'id_fly');
    }

    // Relation avec le client
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }
}
