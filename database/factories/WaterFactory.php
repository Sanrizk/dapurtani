<?php

namespace Database\Factories;

use App\Models\Water;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cultivate;

/**
 * @extends Factory<Water>
 */
class WaterFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'cultivate_id' => fake()->numberBetween(1,10),
      'datetime' => fake()->dateTimeThisMonth(),
    ];
  }
}
