<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Harvest;
use App\Models\Stock;
use App\Models\Cultivate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HarvestController extends Controller
{

  public function index()
  {
    // 1. Ambil data Harvest beserta relasi bertingkatnya
    $harvests = Harvest::with([
      'cultivate.plant', // Mengambil relasi cultivate, lanjut ke plant
      'cultivate.plot'   // Mengambil relasi cultivate, lanjut ke plot
    ])->orderBy('datetime', 'desc')->get();

    // 2. Ubah format datanya menggunakan map()
    $formattedHarvests = $harvests->map(function ($harvest) {
      // Mempermudah pemanggilan dengan memasukkan ke variabel
      $cultivate = $harvest->cultivate;
      $plant = $cultivate ? $cultivate->plant : null;
      $plot = $cultivate ? $cultivate->plot : null;

      $totalConsumed = $harvest->consumes->filter(function ($consume) use ($harvest) {
        return $consume->batch === $harvest->batch;
      })->sum('qty');

      return [
        'id' => $harvest->id,
        'batch' => 'Batch ' . $harvest->batch,
        'harvest_date' => Carbon::parse($harvest->datetime)->locale('id')->translatedFormat('d F Y'),
        'icon' => '🍅', // Bisa Anda buat dinamis nanti berdasarkan nama tanaman
        'plant_name' => $plant->plant_name ?? '-',
        'plot' => $plot->plot_name ?? '-',

        // Karena di tabel harvest hanya ada 'qty', sementara sisa_stok kita asumsikan 
        // sama dengan qty saat baru dipanen (kecuali Anda punya tabel penjualan/penggunaan)
        'sisa_stok' => $harvest->qty - $totalConsumed,
        'total_panen' => $harvest->qty,

        'unit' => $plant->unit ?? '-'
      ];
    });

    $harvestsStok = $formattedHarvests->groupBy('plant_name')->map(function ($groupedItems, $plantName) {
      // Ambil data pertama dari grup ini untuk mengambil icon dan satuan yang sama
      $firstItem = $groupedItems->first();

      return [
        'icon' => $firstItem['icon'],
        'plant_name' => $plantName,

        // 2. Jumlahkan total sisa stok dan total panen dari semua data di grup ini
        'sisa_stok' => $groupedItems->sum('sisa_stok'),
        'total_panen' => $groupedItems->sum('total_panen'),

        'unit' => $firstItem['unit'],
      ];
    })->values();

    // log atau riwayat consumes
    $consumes = $harvests->mapWithKeys(function ($harvest) {

      // MENYARING DATA (2 Kondisi):
      // 1. harvest_id otomatis cocok karena kita memanggil bawaan relasi ($harvest->consumes)
      // 2. Filter data consume: cocokkan batch di consume dengan batch di harvest
      $validConsumes = $harvest->consumes->filter(function ($consume) use ($harvest) {
        return $consume->batch === $harvest->batch;
      });

      // FORMATTING DATA:
      // Format hanya data consume yang lolos filter di atas
      $mappedConsumes = $validConsumes->map(function ($consume) {
        return [
          'id' => $consume->id,
          'batch' => $consume->batch,
          'text' => Carbon::parse($consume->datetime)->locale('id')->translatedFormat('j F Y \(\J\a\m G\)'),
          'qty' => $consume->qty,
          'type' => 'consumes'
        ];
      })->values(); // values() untuk mereset index array agar dimulai dari 0 lagi

      // RETURN RESULT:
      // Output akhirnya dikelompokkan berdasarkan ID dari harvest 
      // Sama persis seperti output dari groupBy('harvest_id') sebelumnya
      return [$harvest->id => $mappedConsumes];
    });

    $title = "Daftar Panen | Dapurtani";

    // 1. Ambil data Stock beserta relasinya ke Plant
    $stocks = Stock::with('plant')->get();

    // 2. Ubah format datanya menggunakan map()
    $formattedStocks = $stocks->map(function ($stock) {
      // Mempermudah pemanggilan relasi
      $plant = $stock->plant;

      return [
        'id' => $stock->id,
        'icon' => '🌱', // Bisa disesuaikan dinamis nanti
        'plant_name' => $plant->plant_name ?? '-',

        // Mengambil dari kolom fisik
        'total_panen' => $stock->total_harvest,
        'total_keluar' => $stock->total_consume,

        // Menghitung sisa stok langsung di dalam map 
        // (Jika sudah pakai Accessor, bisa juga panggil $stock->sisa_stok)
        'sisa_stok' => $stock->total_harvest - $stock->total_consume,

        // Jika di tabel plants ada kolom unit (satuan), bisa dipanggil juga
        'unit' => $plant->unit ?? 'kg'
      ];
    });

    return view('pages.harvests.harvests2', compact('formattedHarvests', 'title', 'harvestsStok', 'consumes', 'formattedStocks'));
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

    return redirect()->route('harvests')
      ->with('success', 'Data Panen berhasil ditambahkan.');
  }

  public function destroy(Harvest $harvest)
  {
    $harvest->delete();

    return redirect()->route('harvests')
      ->with('success', 'Data Panen berhasil dihapus.');
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
