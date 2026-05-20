<?php

namespace Database\Factories;

use App\Models\Cultivate;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Plant;
use App\Models\Plot;

/**
 * @extends Factory<Cultivate>
 */
class CultivateFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'plant_id' => fake()->numberBetween(1,10),
      'qty' => fake()->numberBetween(1,100),
      'plot_id' => fake()->numberBetween(1,10),
      'is_harvested' => fake()->randomElement([0,1]),
      'datetime' => fake()->dateTimeBetween('-12 month', 'now'),
    ];
  }
}
