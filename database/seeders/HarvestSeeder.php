<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Harvest;

class HarvestSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Harvest::factory()->count(10)->create();
  }
}
