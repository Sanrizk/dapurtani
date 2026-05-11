<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Harvest;
use Carbon\Carbon;

class HarvestController extends Controller
{

  public function index()
  {
    // 1. Ambil data Harvest beserta relasi bertingkatnya
    $harvests = Harvest::with([
      'cultivate.plant', // Mengambil relasi cultivate, lanjut ke plant
      'cultivate.plot'   // Mengambil relasi cultivate, lanjut ke plot
    ])->get();

    // 2. Ubah format datanya menggunakan map()
    $formattedHarvests = $harvests->map(function ($harvest) {
      // Mempermudah pemanggilan dengan memasukkan ke variabel
      $cultivate = $harvest->cultivate;
      $plant = $cultivate ? $cultivate->plant : null;
      $plot = $cultivate ? $cultivate->plot : null;

      return [
        'id' => $harvest->id,
        'gelombang' => 'Gelombang ' . $harvest->batch,
        'tanggal_panen' => Carbon::parse($harvest->datetime)->translatedFormat('d F Y'),
        'icon' => '🍅', // Bisa Anda buat dinamis nanti berdasarkan nama tanaman
        'nama_tanaman' => $plant->plant_name ?? '-',
        'lokasi' => $plot->plots_name ?? '-',

        // Karena di tabel harvest hanya ada 'qty', sementara sisa_stok kita asumsikan 
        // sama dengan qty saat baru dipanen (kecuali Anda punya tabel penjualan/penggunaan)
        'sisa_stok' => $harvest->qty,
        'total_panen' => $harvest->qty,

        'satuan' => $plant->unit ?? '-'
      ];
    });

    $title = "Daftar Panen | Dapurtani";

    return view('pages.harvests.harvests2', compact('formattedHarvests', 'title'));
  }


}
