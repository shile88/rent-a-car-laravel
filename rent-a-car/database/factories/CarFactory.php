<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $carTypes = ['BMW', 'AUDI', 'TOYOTA', 'MERCEDES', 'VW', 'HONDA', 'FERRARI', 'CITROEN', 'RENAULT', 'ZASTAVA'];

        return [
            'type' => $carTypes[array_rand($carTypes)],
            'model' => fake()->colorName(),
            'year' => fake()->numberBetween(2000, date('Y')),
            'price_per_day' => fake()->numberBetween(10, 100),
            'photo_path' => fake()->imageUrl(),
            'document_path' =>fake()->imageUrl(),
            'photo_name' => fake()->firstName(),
            'document_name' => fake()->firstName()
        ];
    }
}
