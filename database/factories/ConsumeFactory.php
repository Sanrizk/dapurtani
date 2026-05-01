<?php

namespace Database\Factories;

use App\Models\Consume;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Harvest;

/**
 * @extends Factory<Consume>
 */
class ConsumeFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'harvest_id' => fake()->numberBetween(1,10),
      'datetime' => fake()->dateTimeBetween('now', '+1 week'),
      'qty' => fake()->numberBetween(1, 40), // Pastikan logic seeder nanti qty consume tidak melebihi qty harvest
    ];
  }
}
