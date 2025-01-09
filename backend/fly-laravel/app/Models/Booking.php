<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'date_hour',
        'place_reserved',
        'state',
        'suitcase_authorized',
        'weight_authorized',
        'id_fly',
        'id_client',
    ];
}
