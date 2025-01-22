<?php

namespace Database\Seeders;

use App\Models\Runway;
use Illuminate\Database\Seeder;

class RunwaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crée 10 pistes factices
        Runway::factory(10)->create();
    }
}
