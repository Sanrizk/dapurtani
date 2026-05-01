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
    Schema::create('waters', function (Blueprint $table) {
      // Primary Key menggunakan nama custom 'waterId'
      $table->id();

      // Definisikan kolom Foreign Key sebagai unsignedBigInteger
      $table->unsignedBigInteger('cultivate_id');

      // Tambahkan constraint Foreign Key yang merujuk ke tabel cultivate
      $table->foreign('cultivate_id')
        ->references('id')->on('cultivates')
        ->onDelete('cascade');

      // Kolom datetime untuk mencatat waktu penyiraman
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
    Schema::dropIfExists('waters');
  }
};