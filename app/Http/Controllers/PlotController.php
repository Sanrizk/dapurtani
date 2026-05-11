<?php

namespace App\Http\Controllers;

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
    //
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
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Plot $plot)
  {
    //
  }
}
