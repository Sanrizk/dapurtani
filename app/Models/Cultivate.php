<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Cultivate extends Model
{
  use HasFactory;

  // Mendefinisikan nama tabel secara eksplisit karena kita menggunakan 'cultivate' 
  // alih-alih bentuk jamak standar Laravel ('cultivates')
  protected $table = 'cultivates';

  // Mengganti default primary key
  // protected $primaryKey = 'cultivateId';

  // Kolom yang dapat diisi secara mass assignment
  protected $fillable = [
    'plant_id',
    'plot_id',
    'is_harvested',
    'datetime',
    'qty',
  ];

  protected $casts = [
    'is_harvested' => 'boolean'
  ];

  /**
   * Relasi Many-to-One (Belongs To) ke model Plant
   */
  public function plant()
  {
    // Parameter: (ModelTujuan, foreign_key_di_tabel_ini, owner_key_di_tabel_tujuan)
    return $this->belongsTo(Plant::class, 'plant_id');
  }

  /**
   * Relasi Many-to-One (Belongs To) ke model Plot
   */
  public function plot()
  {
    return $this->belongsTo(Plot::class, 'plot_id');
  }

  /**
   * Relasi One-to-Many ke model Water
   */
  public function waters()
  {
    // Parameter: (ModelTujuan, foreign_key_di_tabel_tujuan, local_key_di_tabel_ini)
    return $this->hasMany(Water::class, 'cultivate_id');
  }

  /**
   * Relasi One-to-Many ke model Fertilize
   */
  public function fertilizes()
  {
    return $this->hasMany(Fertilize::class, 'cultivate_id');
  }

  /**
   * Relasi One-to-Many ke model Harvest
   */
  public function harvests()
  {
    return $this->hasMany(Harvest::class, 'cultivate_id');
  }
}
