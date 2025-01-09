<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Runway extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_runway';

    protected $fillable = [
        'state',
        'size',
        'id_airport',
    ];
}
