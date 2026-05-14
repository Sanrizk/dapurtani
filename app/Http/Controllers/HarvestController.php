<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Harvest;
use App\Models\Cultivate;
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
        'gelombang' => 'Batch ' . $harvest->batch,
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

  public function store(Request $request, Cultivate $cultivate)
  {
    // Validasi input
    $request->validate([
      'datetime' => 'required|date_format:Y-m-d\TH:i',
      'qty' => 'required|integer|min:1',
    ]);

    $request['cultivate_id'] = $cultivate->id;
    $request['batch'] = $this->getBatch();

    // Simpan ke database
    Harvest::create($request->all());

    if ($request['harvestCheck']) {
      Cultivate::where('id', $cultivate->id)->update(['is_harvested' => true]);
    }

    return redirect()->route('cultivates')
      ->with('success', 'Data Panen berhasil ditambahkan.');
  }

  public function getBatch()
  {
    // format batch
    $prefix = Carbon::now()->format('ym');
    $lastData = Harvest::where('batch', 'LIKE', $prefix . '%')
      ->orderBy('batch', 'desc')
      ->first();
    $lastData = Harvest::latest('batch')->first();
    if ($lastData) {
      $lastSequence = (int) substr($lastData->batch, -3);
      $nextSequence = $lastSequence + 1;
    } else {
      $nextSequence = 1;
    }
    $formattedSequence = sprintf('%03d', $nextSequence);
    $newBatch = $prefix . $formattedSequence;

    return $newBatch;
  }

}
