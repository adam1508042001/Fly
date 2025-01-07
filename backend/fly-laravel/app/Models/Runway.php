<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Runway extends Model
{
    use HasFactory;

    protected $fillable = [
        'state',
        'size',
        'id_airport',
    ];

    // Relation avec l'aéroport
    public function airport()
    {
        return $this->belongsTo(Airport::class, 'id_airport');
    }
}
