<?php

namespace Database\Seeders;

use App\Models\Plane;
use Illuminate\Database\Seeder;

class PlanesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Génère 10 enregistrements dans la table `planes`
        Plane::factory()->count(20)->create();
    }
}
