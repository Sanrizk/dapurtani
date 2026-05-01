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
    Schema::create('harvests', function (Blueprint $table) {
      // Primary Key menggunakan nama custom 'harvestId'
      $table->id();

      // Definisikan kolom Foreign Key sebagai unsignedBigInteger
      $table->unsignedBigInteger('cultivate_id');

      // Tambahkan constraint Foreign Key yang merujuk ke tabel cultivate
      $table->foreign('cultivate_id')
        ->references('id')->on('cultivates')
        ->onDelete('cascade'); // Jika data kultivasi dihapus, data panen terkait ikut terhapus

      // Kolom integer untuk mencatat gelombang/periode panen (batch)
      $table->integer('batch');

      // Kolom datetime untuk mencatat waktu panen dilakukan
      $table->dateTime('datetime');

      // Kolom integer untuk kuantitas/jumlah hasil panen (qty)
      $table->integer('qty');

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
    Schema::dropIfExists('harvests');
  }
};