<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('plants', function (Blueprint $table) {
      // Primary Key bisa menggunakan nama custom 'plantId'
      $table->id();

      // Kolom varchar untuk nama tanaman dan satuan
      $table->string('plant_name');
      $table->string('unit');

      // Kolom integer untuk waktu panen (misalnya dalam hitungan hari/minggu)
      $table->integer('harvest_time');

      // Standar Laravel: created_at dan updated_at
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('plants');
  }
};