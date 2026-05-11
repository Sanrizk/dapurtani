<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cultivate;
use Carbon\Carbon;

class CultivateController extends Controller
{
  public function index()
  {
    // Memanggil data Cultivate sekaligus menyertakan semua relasinya
    $cultivates = Cultivate::with([
      'plant',       // Mengambil data Plant terkait (Many-to-One)
      'plot',        // Mengambil data Plot terkait (Many-to-One)
      'waters',      // Mengambil data Waters terkait (One-to-Many)
      'fertilizes',  // Mengambil data Fertilizes terkait (One-to-Many)
      'harvests'     // Mengambil data Harvests terkait (One-to-Many)
    ])->get();

    $formattedCultivates = $cultivates->map(function ($cultivate) {
      // Parsing tanggal tanam dari kolom 'datetime'
      $datePlant = Carbon::parse($cultivate->datetime);

      // Waktu panen (harvest_time) menggunakan satuan hari dari relasi Plant
      $harvestTimeDays = $cultivate->plant->harvest_time ?? 0;

      // Menghitung tanggal estimasi panen
      $dateAction = $datePlant->copy()->addDays($harvestTimeDays);

      $now = Carbon::now();

      // Kalkulasi Umur Tanaman (selisih hari ini dengan tanggal tanam)
      $ageInDays = $datePlant->diffInDays($now);

      // Ambil status is_harvested langsung dari kolom table database Anda
      // (Cast ke boolean karena di DB bertipe tinyint 0/1)
      $isHarvested = (bool) $cultivate->is_harvested;

      // Logika penyesuaian jika tanaman SUDAH dipanen vs BELUM
      if ($isHarvested) {
        $remainingLabel = 'Sudah Dipanen';
        $progress = 100; // Jika sudah panen, progress dipaksa penuh 100%
      } else {
        // Jika belum panen, hitung sisa hari
        $remainingDays = $now->diffInDays($dateAction, false);
        $remainingLabel = $remainingDays > 0 ? $remainingDays . ' Hari Lagi' : 'Siap Panen!';

        // Hitung progress berjalan
        if ($harvestTimeDays > 0) {
          $progress = ($ageInDays / $harvestTimeDays) * 100;
          // Pastikan progress tidak lebih dari 100 atau kurang dari 0
          $progress = min(100, max(0, $progress));
        } else {
          $progress = 0;
        }
      }

      return [
        'id' => $cultivate->id,
        'name' => $cultivate->plant->plant_name ?? '-',
        'icon' => '🍅',
        // Menggunakan plots_name sesuai skema tabel plots Anda
        'location' => 'Bedengan ' . ($cultivate->plot->plots_name ?? '-'),
        'date_plant' => $datePlant->translatedFormat('d F Y'),
        'date_action' => $dateAction->translatedFormat('d F Y'),
        'age_label' => 'Hari ke-' . round($ageInDays),
        'remaining_label' => $remainingLabel,
        'progress' => round($progress),
        'is_harvested' => $isHarvested
      ];
    });

    $title = "Penanaman | Dapurtani";

    return view('pages.cultivates.cultivates2', compact('formattedCultivates', 'title'));
  }
}
