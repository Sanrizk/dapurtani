<?php

namespace App\Http\Controllers;

use App\Models\Cultivate;
use App\Models\Plot;
use Illuminate\Http\Request;


class PlotController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $plots = Plot::all();
    $cultivates = Cultivate::all();

    // 1. Ekstrak semua 'plot_id' dari data cultivate menjadi bentuk array.
    // Jika data $cultivates kosong, ini otomatis akan menghasilkan array kosong [].
    $cultivatedPlotIds = $cultivates->pluck('plot_id')->toArray();

    // 2. Map data plots
    $plots = $plots->map(function ($plot) use ($cultivatedPlotIds) {

      // Cek apakah ID plot saat ini ada di dalam array $cultivatedPlotIds
      if (in_array($plot->id, $cultivatedPlotIds)) {
        $plot->status = 'terisi';
      } else {
        // Jika tidak ada kecocokan, atau jika array $cultivatedPlotIds kosong, 
        // maka status otomatis menjadi 'kosong'.
        $plot->status = 'kosong';
      }

      return $plot;
    });

    $title = 'Bedengan | Dapurtani';

    return view('pages.plots.plots2', compact('plots', 'title'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // Validasi input
    $request->validate([
      'plot_name' => 'required|string|max:255',

    ]);

    // Simpan ke database
    Plot::create($request->all());

    return redirect()->route('plots')
      ->with('action', 'tambah')
      ->with('success', 'Data ' . $request['plot_name'] . ' berhasil ditambahkan.');
  }

  /**
   * Display the specified resource.
   */
  public function show(Plot $plot)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Plot $plot)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Plot $plot)
  {
    // Validasi input
    $request->validate([
      'plot_name' => 'required|string|max:255'
    ]);

    // Update database
    $plot->update($request->all());

    return redirect()->route('plots')
      ->with('action', 'ubah')
      ->with('success', 'Data Bedengan berhasil diperbarui.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Plot $plot)
  {
    $plot->delete();

    return redirect()->route('plots')
      ->with('action', 'hapus')
      ->with('success', 'Data ' . $plot->plot_name . ' berhasil dihapus.');
  }
}
