<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Plane;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plane>
 */
class PlaneFactory extends Factory
{
    protected $model = Plane::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model' => $this->faker->word,  // Génère un nom de modèle aléatoire
            'size' => $this->faker->numberBetween(10, 100),  // Taille de l'avion aléatoire
            'max_place' => $this->faker->numberBetween(50, 300),  // Nombre de places maximum aléatoire
            'state' => $this->faker->randomElement(['available', 'maintenance', 'unavailable']),  // État aléatoire
            'id_runway' => $this->faker->numberBetween(1, 20),  // Piste aléatoire
        ];
    }
}
