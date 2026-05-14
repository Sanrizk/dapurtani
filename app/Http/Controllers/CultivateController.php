<?php

namespace App\Http\Controllers;

use App\Models\Harvest;
use Illuminate\Http\Request;
use App\Models\Cultivate;
use App\Models\Plant;
use App\Models\Plot;
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
        $remainingDays = round($now->diffInDays($dateAction, false));
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
        'plant_id' => $cultivate->plant_id,
        'plot_id' => $cultivate->plot_id,
        'datetime' => $cultivate->datetime,
        // Menggunakan plots_name sesuai skema tabel plots Anda
        'location' => 'Bedengan ' . ($cultivate->plot->plot_name ?? '-'),
        'date_plant' => $datePlant->translatedFormat('d F Y'),
        'date_action' => $dateAction->translatedFormat('d F Y'),
        'age_label' => 'Hari ke-' . round($ageInDays),
        'remaining_label' => $remainingLabel,
        'progress' => round($progress),
        'is_harvested' => $isHarvested,

        'harvests' => $cultivate->harvests,
      ];
    });

    $title = "Penanaman | Dapurtani";

    $plants = Plant::all();

    $plots = Plot::all();

    $harvests = $cultivates->flatMap->harvests->groupBy('cultivate_id')->map(function ($harvests) {
      return $harvests->map(function ($harvest) {
        return [
          'batch' => $harvest->batch,
          'text' => Carbon::parse($harvest->datetime)->locale('id')->translatedFormat('j F Y \(\J\a\m G\)'),
          'qty' => $harvest->qty,
        ];
      })->values();
    });

    $waters = $cultivates->flatMap->waters->groupBy('cultivate_id')->map(function ($waters) {
      return $waters->map(function ($water) {
        return [
          'batch' => $water->batch,
          'text' => Carbon::parse($water->datetime)->locale('id')->translatedFormat('j F Y \(\J\a\m G\)'),
          'qty' => $water->qty,
        ];
      })->values();
    });

    $fertilizes = $cultivates->flatMap->fertilizes->groupBy('cultivate_id')->map(function ($fertilizes) {
      return $fertilizes->map(function ($fertilize) {
        return [
          'batch' => $fertilize->batch,
          'text' => Carbon::parse($fertilize->datetime)->locale('id')->translatedFormat('j F Y \(\J\a\m G\)'),
          'qty' => $fertilize->qty,
        ];
      })->values();
    });

    // 1. Gabungkan semua relasi menjadi satu Collection datar (Flat Collection)
    $allLogs = collect()
      ->concat($cultivates->flatMap->harvests)
      ->concat($cultivates->flatMap->waters)
      ->concat($cultivates->flatMap->fertilizes);

    // 2. Kelompokkan, urutkan, dan format datanya
    $mergedLogs = $allLogs->groupBy('cultivate_id')->map(function ($logs) {

      return $logs->sortByDesc('datetime')->map(function ($item) {

        // Cek nama Model dari item ini
        $modelName = class_basename($item);

        // Tentukan label string berdasarkan nama Modelnya
        // (Pastikan nama 'Harvest', 'Water', 'Fertilize' sesuai dengan nama file Model kamu)
        $type = match ($modelName) {
          'Harvest' => 'harvest',
          'Water' => 'water',       // Ubah jadi 'Waters' jika nama modelmu Waters
          'Fertilize' => 'fertilize',   // Ubah jadi 'Fertilizes' jika nama modelmu Fertilizes
          default => 'unknown'
        };

        return [
          'type' => $type, // <--- Tambahan jenis data ada di sini
          'batch' => $item->batch,
          'text' => Carbon::parse($item->datetime)->locale('id')->translatedFormat('j F Y \(\J\a\m G\)'),
          'qty' => $item->qty,
        ];
      })->values();
    });
    // dd($allLogs);

    // Hasil akhirnya ada di variabel $mergedLogs

    // $harvests = $cultivates->flatMap->harvests;
    // $harvests = $cultivates->pluck('harvests')->collapse();

    // dd($harvests->where('cultivate_id', ($cultivates[3]->id)));

    return view('pages.cultivates.cultivates2', compact('formattedCultivates', 'title', 'plants', 'plots', 'cultivates', 'harvests', 'waters', 'fertilizes', 'mergedLogs'));
  }

  public function store(Request $request)
  {
    // Validasi input
    $request->validate([
      'plant_id' => 'required',
      'plot_id' => 'required',
      'datetime' => 'required',
    ]);

    $request['is_harvested'] = 0;

    // Simpan ke database
    Cultivate::create($request->all());


    return redirect()->route('cultivates')
      ->with('success', 'Data Menanam berhasil ditambahkan.');
  }

  public function update(Request $request, Cultivate $cultivate)
  {
    // Validasi input
    $request->validate([
      'plant_id' => 'required',
      'plot_id' => 'required',
      'datetime' => 'required',
    ]);

    // Update database
    $cultivate->update($request->all());

    return redirect()->route('cultivates')
      ->with('success', 'Data Menanam berhasil diperbarui.');
  }

  public function destroy(Cultivate $cultivate)
  {
    $cultivate->delete();

    return redirect()->route('cultivates')
      ->with('success', 'Data Menanam berhasil dihapus.');
  }
}
