<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jugador>
 */
class JugadorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => $this->faker->firstName(),
            'cognoms' => $this->faker->lastName(),
            'dorsal' => $this->faker->numberBetween(1, 99),
            'equip_id' => \App\Models\Equip::inRandomOrder()->first()->id ?? \App\Models\Equip::factory(),
        ];
    }
}
