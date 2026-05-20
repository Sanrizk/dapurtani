<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HarvestReport;

class ReportController extends Controller
{
  public function index(Request $request)
  {
    $filters = [
      'bulan_mulai' => $request->query('bulan_mulai'),
      'bulan_selesai' => $request->query('bulan_selesai'),
      'tahun' => $request->query('tahun'),
      'tanaman' => $request->query('tanaman'),
    ];

    return Excel::download(new HarvestReport($filters), 'laporan-tanaman-dapurtani.xlsx');
  }
}
