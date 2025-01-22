<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Fly;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date_hour' => $this->faker->dateTimeThisYear, // Date et heure de la réservation
            'place_reserved' => $this->faker->numberBetween(1, 5), // Nombre de places réservées
            'state' => $this->faker->randomElement(['confirmed', 'pending', 'cancelled']), // Etat de la réservation
            'suitcase_authorized' => $this->faker->boolean, // Si la valise est autorisée ou non
            'weight_authorized' => $this->faker->randomFloat(2, 5, 30), // Poids autorisé pour la valise (de 5 à 30 kg)
            'id_fly' => Fly::factory(), // Référence à un vol existant (id_fly)
            'id_client' => Client::factory(), // Référence à un client existant (id_client)
        ];
    }
}
