<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,  // Prénom du client
            'last_name' => $this->faker->lastName,    // Nom de famille du client
            'date_of_birth' => $this->faker->date,    // Date de naissance du client
            'email' => $this->faker->unique()->safeEmail,  // Email unique
            'status' => $this->faker->randomElement(['active', 'inactive', 'pending']), // Statut du client
        ];
    }
}
