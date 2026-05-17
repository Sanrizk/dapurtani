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
    Schema::create('consumes', function (Blueprint $table) {
      // Primary Key menggunakan nama custom 'consumeId'
      $table->id();

      // Definisikan kolom Foreign Key sebagai unsignedBigInteger
      $table->unsignedBigInteger('harvest_id');

      // Tambahkan constraint Foreign Key yang merujuk ke tabel harvest
      $table->foreign('harvest_id')
        ->references('id')->on('harvests')
        ->onDelete('cascade'); // Jika data panen dihapus, riwayat konsumsi terkait ikut terhapus

      $table->string('batch');

      // Kolom datetime untuk mencatat waktu konsumsi
      $table->dateTime('datetime');

      // Kolom integer untuk kuantitas/jumlah yang dikonsumsi (qty)
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
    Schema::dropIfExists('consumes');
  }
};