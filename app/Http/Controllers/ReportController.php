<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HarvestReport;
use App\Models\Harvest;
use App\Models\Consume;
use App\Models\Plant;
use Carbon\Carbon;

class ReportController extends Controller
{

  public function index()
  {
    $title = 'Laporan | Dapurtani';

    $plants = Plant::all();

    // Data Panen
    $panen = Harvest::with('cultivate.plant')
      ->get()
      ->map(function ($h) {
        $cultivate = $h->cultivate;
        $plant = $cultivate ? $cultivate->plant : null;

        return [
          'tipe' => 'panen',
          'tanggal' => Carbon::parse($h->datetime)->locale('id')->translatedFormat('d F Y'),
          'bulan' => (int) date('m', strtotime($h->datetime)),
          'tahun' => (int) date('Y', strtotime($h->datetime)),
          'nama' => $plant->plant_name,
          'jumlah' => $h->qty,
          'satuan' => $h->unit,
          'batch' => $h->batch ?? '-',
        ];
      });

    // Data Penggunaan — sesuaikan model & field dengan punyamu
    $penggunaan = Consume::with('harvest.cultivate.plant')
      ->get()
      ->map(function ($u) {
        $harvest = $u->harvest;
        $cultivate = $harvest->cultivate;
        $plant = $cultivate ? $cultivate->plant : null;

        return [
          'tipe' => 'penggunaan',
          'tanggal' => Carbon::parse($u->datetime)->locale('id')->translatedFormat('d F Y'),
          'bulan' => (int) date('m', strtotime($u->datetime)),
          'tahun' => (int) date('Y', strtotime($u->datetime)),
          'nama' => $plant->plant_name,
          'jumlah' => $u->qty,
          'satuan' => $u->unit,
          'batch' => $u->batch ?? '-',
        ];
      });

    $newdata = $panen->merge($penggunaan)->sortBy('tanggal')->values();

    return view('pages.report', compact('title', 'newdata', 'plants'));

  }

}
