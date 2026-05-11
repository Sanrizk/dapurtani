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
    Schema::create('cultivates', function (Blueprint $table) {
      // Primary Key custom 'cultivateId'
      $table->id();

      // 1. Definisikan kolom Foreign Key sebagai unsignedBigInteger
      $table->unsignedBigInteger('plant_id');
      $table->unsignedBigInteger('plot_id');
      $table->boolean('is_harvested');

      // 2. Tambahkan constraint Foreign Key
      $table->foreign('plant_id')
        ->references('id')->on('plants')
        ->onDelete('cascade'); // Jika data di tabel plants dihapus, data cultivate terkait ikut terhapus

      $table->foreign('plot_id')
        ->references('id')->on('plots')
        ->onDelete('cascade');

      // Kolom waktu kultivasi
      $table->dateTime('datetime');

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
    Schema::dropIfExists('cultivates');
  }
};