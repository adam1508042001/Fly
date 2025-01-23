<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plane extends Model
{
    use HasFactory;


    // Clé primaire personnalisée
    protected $primaryKey = 'id_plane';
    
    protected $fillable = [
        'model',
        'size',
        'max_place',
        'state',
    ];


     // Relation avec Fly (vols opérés par cet avion)
     public function flies()
     {
         return $this->hasMany(Fly::class, 'id_plane');
     }
}
