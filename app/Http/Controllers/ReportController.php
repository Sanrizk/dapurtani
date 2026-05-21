<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HarvestReport;
use App\Models\Harvest;
use App\Models\Plant;
use Carbon\Carbon;

class ReportController extends Controller
{

  public function index()
  {
    $title = 'Laporan | Dapurtani';
    $harvests = Harvest::with('cultivate.plant')->orderBy('datetime', 'desc')->get();

    $data = $harvests->map(function ($harvest, $index) {
      $cultivate = $harvest->cultivate;
      $plant = $cultivate ? $cultivate->plant : null;

      return [
        'no' => $index + 1,
        'tanggal' => Carbon::parse($harvest->datetime)->locale('id')->translatedFormat('d F Y'),
        'nama' => $plant->plant_name ?? '-',
        'jumlah' => $harvest->qty,
        'satuan' => $plant->unit,
        'bulan' => (int) Carbon::parse($harvest->datetime)->format('m'),
        'tahun' => (int) Carbon::parse($harvest->datetime)->format('Y'),
        'batch' => 'Batch ' . $harvest->batch,
      ];
    });

    $plants = Plant::all();
    return view('pages.report', compact('title', 'data', 'plants'));

  }

}
