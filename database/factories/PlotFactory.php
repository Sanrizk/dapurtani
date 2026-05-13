<?php

namespace Database\Factories;

use App\Models\Plot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plot>
 */
class PlotFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      // 'order' => fake()->unique()->numberBetween(1, 100),
      'plot_name' => fake()->randomElement(['Blok A1', 'Blok A2', 'Blok B1', 'Blok B2', 'Blok C1', 'Blok C2']),
    ];
  }
}
