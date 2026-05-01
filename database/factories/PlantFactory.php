<?php

namespace Database\Factories;

use App\Models\Plant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plant>
 */
class PlantFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'plant_name' => fake()->randomElement(['Padi', 'Jagung', 'Kedelai', 'Cabai', 'Tomat']),
      'harvest_time' => fake()->numberBetween(30, 120), // Asumsi dalam hari
      'unit' => fake()->randomElement(['kg', 'ikat', 'kuintal', 'ton']),
    ];
  }
}
