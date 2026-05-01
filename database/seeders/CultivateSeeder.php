<?php

namespace Database\Seeders;

use App\Models\Cultivate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CultivateSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Cultivate::factory()->count(10)->create();
  }
}
