<?php

namespace Database\Seeders;

use App\Models\Fly;
use Illuminate\Database\Seeder;

class FlysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crée 10 vols factices
        Fly::factory(10)->create();
    }
}
