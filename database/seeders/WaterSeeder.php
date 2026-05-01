<?php

namespace Database\Seeders;

use App\Models\Water;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WaterSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Water::factory()->count(10)->create();
  }
}
