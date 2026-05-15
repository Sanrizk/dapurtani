<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Harvest;
use App\Models\Consume;

class ConsumeController extends Controller
{
  public function store(Request $request, Harvest $harvest)
  {
    // Validasi input
    $request->validate([
      'datetime' => 'required|date_format:Y-m-d\TH:i',
      'qty' => 'required|integer|min:1',
    ]);

    $request['harvest_id'] = $harvest->id;

    // Simpan ke database
    Consume::create($request->all());

    return redirect()->route('harvests')
      ->with('success', 'Data Menyiram berhasil ditambahkan.');
  }

  public function destroy(Consume $consume)
  {
    $consume->delete();

    return redirect()->route('cultivates')
      ->with('success', 'Data Menyiram berhasil dihapus.');
  }
}
