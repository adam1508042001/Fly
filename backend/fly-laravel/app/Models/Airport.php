<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;

     // Clé primaire personnalisée
     protected $primaryKey = 'id_airport';
    

    protected $fillable = [
        'name',
        'city',
    ];
}
