<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cultivate;
use App\Models\Fertilize;

class FertilizeController extends Controller
{
  public function store(Request $request, Cultivate $cultivate)
  {
    // Validasi input
    $request->validate([
      'datetime' => 'required|date_format:Y-m-d\TH:i',
    ]);

    $request['cultivate_id'] = $cultivate->id;

    // Simpan ke database
    Fertilize::create($request->all());

    return redirect()->route('cultivates')
      ->with('success', 'Data Menyiram berhasil ditambahkan.');

  }
}
