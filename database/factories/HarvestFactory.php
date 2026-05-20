<?php

namespace Database\Factories;

use App\Models\Harvest;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cultivate;

/**
 * @extends Factory<Harvest>
 */
class HarvestFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'cultivate_id' => fake()->numberBetween(1, 10),
      'batch' => fake()->numberBetween(2605001, 2605100),
      'datetime' => fake()->dateTimeBetween('-12 month', 'now'),
      'qty' => fake()->numberBetween(50, 500),
    ];
  }
}
