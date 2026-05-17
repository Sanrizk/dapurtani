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
        // TRIGGER 1: Setelah data Harvest (Panen) dimasukkan
        DB::unprepared('
            CREATE TRIGGER after_harvests_insert
            AFTER INSERT ON harvests
            FOR EACH ROW
            BEGIN
                DECLARE v_plant_id BIGINT;

                -- Cari plant_id dari relasi cultivate_id
                SELECT plant_id INTO v_plant_id 
                FROM cultivates 
                WHERE id = NEW.cultivate_id;

                -- Update atau Insert ke tabel stocks
                INSERT INTO stocks (plant_id, total_harvest, total_consume, created_at, updated_at)
                VALUES (v_plant_id, NEW.qty, 0, NOW(), NOW())
                ON DUPLICATE KEY UPDATE 
                    total_harvest = total_harvest + NEW.qty,
                    updated_at = NOW();
            END
        ');

        // TRIGGER 2: Setelah data Consumes (Penggunaan) dimasukkan
        DB::unprepared('
            CREATE TRIGGER after_consumes_insert
            AFTER INSERT ON consumes
            FOR EACH ROW
            BEGIN
                DECLARE v_plant_id BIGINT;

                -- Lacak plant_id melalui join harvests dan cultivates
                SELECT c.plant_id INTO v_plant_id 
                FROM harvests h 
                JOIN cultivates c ON h.cultivate_id = c.id 
                WHERE h.id = NEW.harvest_id;

                -- Update tabel stocks dengan qty yang dikonsumsi
                INSERT INTO stocks (plant_id, total_harvest, total_consume, created_at, updated_at)
                VALUES (v_plant_id, 0, NEW.qty, NOW(), NOW())
                ON DUPLICATE KEY UPDATE 
                    total_consume = total_consume + NEW.qty,
                    updated_at = NOW();
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_triggers');
    }
};
