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
    Schema::create('plots', function (Blueprint $table) {
      // Primary Key bisa menggunakan nama custom 'plotId'
      $table->id();

      // Kolom integer untuk urutan (misalnya urutan lahan/bedengan)
      $table->string('plots_name');

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
    Schema::dropIfExists('plots');
  }
};