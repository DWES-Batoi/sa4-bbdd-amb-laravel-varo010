<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partit>
 */
class PartitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $local = \App\Models\Equip::inRandomOrder()->first();
        $visitant = \App\Models\Equip::where('id', '!=', $local->id)->inRandomOrder()->first();

        return [
            'local_id' => $local->id,
            'visitant_id' => $visitant->id,
            'data_partit' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'gols_local' => $this->faker->numberBetween(0, 5),
            'gols_visitant' => $this->faker->numberBetween(0, 5),
        ];
    }
}
