<?php

namespace Database\Factories;

use App\Models\Runway;
use App\Models\Airport;
use Illuminate\Database\Eloquent\Factories\Factory;

class RunwayFactory extends Factory
{
    protected $model = Runway::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'state' => $this->faker->randomElement(['active', 'inactive', 'under maintenance']), // Etat de la piste
            'size' => $this->faker->numberBetween(1000, 5000), // Taille de la piste (en mètres)
            'id_airport' => Airport::inRandomOrder()->first()->id_airport,  // Aéroport auquel la piste appartient
        ];
    }
}
