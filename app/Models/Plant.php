<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
  use HasFactory;

  // Mendefinisikan nama tabel secara eksplisit (opsional tapi disarankan)
  protected $table = 'plants';

  // Mengganti default primary key 'id' menjadi 'plantId'
  // protected $primaryKey = 'plant_id';

  // Mendefinisikan kolom mana saja yang boleh diisi secara massal (mass assignment)
  protected $fillable = [
    'plant_name',
    'harvest_time',
    'unit',
  ];

  /**
   * Relasi One-to-Many ke model Cultivate
   */
  public function cultivates()
  {
    // Parameter: (NamaModelRelasi, foreign_key_di_tabel_tujuan, local_key_di_tabel_ini)
    return $this->hasMany(Cultivate::class, 'plant_id');
  }
}
