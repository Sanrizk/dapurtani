<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('plant_id');
            $table->foreign('plant_id')
                ->references('id')->on('plants')
                ->onDelete('cascade'); // Jika data di tabel plants dihapus, data cultivate terkait ikut terhapus

            $table->integer('total_harvest')->default(0);
            $table->integer('total_consume')->default(0);
            $table->timestamps();

            // UNIQUE digunakan agar kita bisa melakukan "ON DUPLICATE KEY UPDATE" di Trigger
            $table->unique('plant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
