<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cultivate;
use App\Models\Water;

class WaterController extends Controller
{
  public function store(Request $request, Cultivate $cultivate)
  {
    // Validasi input
    $request->validate([
      'datetime' => 'required|date_format:Y-m-d\TH:i',
    ]);

    $request['cultivate_id'] = $cultivate->id;

    // Simpan ke database
    Water::create($request->all());

    return redirect()->route('cultivates')
      ->with('success', 'Data Menyiram berhasil ditambahkan.');
  }

  public function destroy(Water $water)
  {
    $water->delete();

    return redirect()->route('cultivates')
      ->with('success', 'Data Menyiram berhasil dihapus.');
  }
}
