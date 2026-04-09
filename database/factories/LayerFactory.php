<?php

namespace Database\Factories;

use App\Models\Layup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Layer>
 */
class LayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'layup_id' => Layup::all()->random()->id,
            'layer_order' => fake()->randomNumber(1,true),
            'thickness' => fake()->randomFloat(2,0,10),
            'width' => fake()->randomFloat(2,0,10),
            'angle' => fake()->randomElement([0,90]),
        ];
    }
}
