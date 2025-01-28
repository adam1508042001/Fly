<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'place_reserved',
        'state',
        'suitcase_authorized',
        'weight_authorized',
        'id_fly',
        'id_client',
    ];

    protected static function boot()
{
    parent::boot();

    static::creating(function ($booking) {
        if (empty($booking->date_hour)) {
            $booking->date_hour = now();
        }
    });
}

public function fly()
{
    return $this->belongsTo(Fly::class, 'id_fly');
}

}




