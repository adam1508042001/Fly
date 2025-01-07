<?php

namespace Database\Factories;

use App\Models\Fly;
use App\Models\Plane;
use App\Models\Airport;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlyFactory extends Factory
{
    protected $model = Fly::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date_hour_landing' => $this->faker->dateTimeBetween('+1 hour', '+5 hours'),   // Date et heure d'atterrissage
            'date_hour_fly_off' => $this->faker->dateTimeBetween('-5 hours', '-1 hour'),    // Date et heure de départ
            'state' => $this->faker->randomElement(['scheduled', 'completed', 'delayed']),   // Etat du vol
            'id_plane' => Plane::inRandomOrder()->first()->id_plane,  // Récupère un avion au hasard
            'id_airport_landing' => Airport::inRandomOrder()->first()->id_airport,  // Aéroport d'atterrissage au hasard
            'id_airport_fly_off' => Airport::inRandomOrder()->first()->id_airport,  // Aéroport de départ au hasard
        ];
    }
}
