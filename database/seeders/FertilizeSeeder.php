<?php

namespace Database\Seeders;

use App\Models\Fertilize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FertilizeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Fertilize::factory()->count(10)->create();
  }
}
