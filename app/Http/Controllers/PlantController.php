<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $plants = Plant::all();
    $title = 'Pohon | Dapurtani';
    return view('pages.plants.plants', compact('plants', 'title'));
  }
  public function index2()
  {
    $plants = Plant::all();
    $title = 'Pohon | Dapurtani';
    return view('pages.plants.plants2', compact('plants', 'title'));
  }

  /**
   * Show the form for creating a new resource.
   */
  // public function create()
  // {
  //   //
  // }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // Validasi input
    $request->validate([
      'plant_name' => 'required|string|max:255',
      'unit' => 'required|string|max:50',
      'harvest_time' => 'required|integer|min:1',
    ]);

    // Simpan ke database
    Plant::create($request->all());

    return redirect()->route('plants')
      ->with('success', 'Data tanaman berhasil ditambahkan.');

  }

  /**
   * Display the specified resource.
   */
  // public function show(Plant $plant)
  // {
  //   //
  // }

  /**
   * Show the form for editing the specified resource.
   */
  // public function edit(Plant $plant)
  // {
  //   //
  // }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Plant $plant)
  {
    // Validasi input
    $request->validate([
      'plant_name' => 'required|string|max:255',
      'unit' => 'required|string|max:50',
      'harvest_time' => 'required|integer|min:1',
    ]);

    // Update database
    $plant->update($request->all());

    return redirect()->route('plants')
      ->with('success', 'Data tanaman berhasil diperbarui.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Plant $plant)
  {
    $plant->delete();

    return redirect()->route('plants')
      ->with('success', 'Data tanaman berhasil dihapus.');
  }
}
