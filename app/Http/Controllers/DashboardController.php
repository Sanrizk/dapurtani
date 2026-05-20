<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Cultivate;
use App\Models\Harvest;

class DashboardController extends Controller
{
  public function index()
  {
    $title = 'Dashboard | Dapurtani';

    // Siapkan waktu: Bulan Kemarin dan 2 Bulan Lalu
    $lastMonth = Carbon::now()->subMonth();
    $twoMonthsAgo = Carbon::now()->subMonths(2);

    // Data Bulan Kemarin (Utama)
    $queryCul = Cultivate::whereMonth('datetime', $lastMonth->month)
      ->whereYear('datetime', $lastMonth->year);

    $countRowCul = $queryCul->count();
    $sumQtyCul = $queryCul->sum('qty');

    // Data 2 Bulan Lalu (Untuk perbandingan persentase)
    $prevCountRowCul = Cultivate::whereMonth('datetime', $twoMonthsAgo->month)
      ->whereYear('datetime', $twoMonthsAgo->year)
      ->count();

    // Hitung Persentase Kenaikan/Penurunan
    $diffCul = $countRowCul - $prevCountRowCul;
    if ($prevCountRowCul > 0) {
      $persenCul = ($diffCul / $prevCountRowCul) * 100;
    } else {
      $persenCul = $countRowCul > 0 ? 100 : 0;
    }

    $dataCultivate = [
      'qty' => $sumQtyCul,
      'count' => $countRowCul,
      'total' => $sumQtyCul * $countRowCul,
      'persen' => number_format(abs($persenCul), 2) . '%',
      'naik' => $diffCul >= 0,
    ];


    // Data Bulan Kemarin (Utama)
    $harvestQuery = Harvest::whereMonth('datetime', $lastMonth->month)
      ->whereYear('datetime', $lastMonth->year);

    $countRowHar = $harvestQuery->count();
    $sumQtyHar = $harvestQuery->sum('qty');

    // Data 2 Bulan Lalu (Untuk perbandingan persentase)
    $prevCountRowHar = Harvest::whereMonth('datetime', $twoMonthsAgo->month)
      ->whereYear('datetime', $twoMonthsAgo->year)
      ->count();

    // Hitung Persentase Kenaikan/Penurunan
    $diffHar = $countRowHar - $prevCountRowHar;
    if ($prevCountRowHar > 0) {
      $persenHar = ($diffHar / $prevCountRowHar) * 100;
    } else {
      $persenHar = $countRowHar > 0 ? 100 : 0;
    }

    $dataHarvest = [
      'qty' => $sumQtyHar,
      'count' => $countRowHar,
      'total' => $sumQtyHar * $countRowHar,
      'persen' => number_format(abs($persenHar), 2) . '%',
      'naik' => $diffHar >= 0,
    ];



    $monthlyCultivateTemplate = [];
    $monthlyHarvestTemplate = [];
    $categories = [];

    // 1. Buat template 12 bulan ke belakang
    // Jika Anda ingin urutannya DIBALIK (Bulan ini di paling kiri, bulan lama di kanan), 
    // ubah urutan loop-nya menjadi: for ($i = 0; $i <= 11; $i++)
    for ($i = 11; $i >= 0; $i--) {
      $date = Carbon::now()->subMonths($i);

      // Buat key unik (Contoh: "2025-06", "2026-05") agar tahun tidak tertukar
      $key = $date->format('Y-m');

      $monthlyCultivateTemplate[$key] = 0;
      $monthlyHarvestTemplate[$key] = 0;

      // Label untuk sumbu X di grafik (Contoh: "Jun 25", "Mei 26")
      // translatedFormat() akan mengikuti bahasa aplikasi (bisa bahasa Indonesia jika diatur)
      $categories[] = $date->translatedFormat('M y');
    }

    // Tentukan batas waktu (Awal bulan dari 11 bulan yang lalu s/d Akhir bulan ini)
    $startDate = Carbon::now()->subMonths(11)->startOfMonth();
    $endDate = Carbon::now()->endOfMonth();

    // 2. Ambil Data Penanaman (Cultivate) dalam rentang 12 bulan tersebut
    $cultivateData = Cultivate::whereBetween('datetime', [$startDate, $endDate])
      ->selectRaw('YEAR(datetime) as year, MONTH(datetime) as month, SUM(qty) as total_qty')
      ->groupBy('year', 'month')
      ->get();

    // Petakan data ke dalam template agar posisinya pas
    foreach ($cultivateData as $row) {
      // Gabungkan tahun dan bulan agar sesuai dengan key (Contoh: 2026-05)
      $monthStr = str_pad($row->month, 2, '0', STR_PAD_LEFT); // Ubah 5 jadi "05"
      $key = $row->year . '-' . $monthStr;

      if (isset($monthlyCultivateTemplate[$key])) {
        $monthlyCultivateTemplate[$key] = $row->total_qty;
      }
    }

    // 3. Ambil Data Panen (Harvest) dalam rentang 12 bulan tersebut
    $harvestData = Harvest::whereBetween('datetime', [$startDate, $endDate])
      ->selectRaw('YEAR(datetime) as year, MONTH(datetime) as month, SUM(qty) as total_qty')
      ->groupBy('year', 'month')
      ->get();

    // Petakan data ke dalam template
    foreach ($harvestData as $row) {
      $monthStr = str_pad($row->month, 2, '0', STR_PAD_LEFT);
      $key = $row->year . '-' . $monthStr;

      if (isset($monthlyHarvestTemplate[$key])) {
        $monthlyHarvestTemplate[$key] = $row->total_qty;
      }
    }

    // 4. Siapkan format akhir untuk dikirim ke View/Javascript
    $chartData = [
      'cultivate' => array_values($monthlyCultivateTemplate),
      'harvest' => array_values($monthlyHarvestTemplate),
      'categories' => $categories // Kategori bulan sekarang dinamis 12 bulan terakhir
    ];

    return view('pages.dashboard.ecommerce2', compact('title', 'dataCultivate', 'dataHarvest', 'chartData'));
  }
}
