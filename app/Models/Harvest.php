<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harvest extends Model
{
  use HasFactory;

  protected $fillable = ['cultivate_id', 'batch', 'datetime', 'qty'];

  // - Relasi: belongsTo ke Cultivate (cultivateId).
  // - Relasi: hasMany ke Consume (harvestId).

  public function cultivate()
  {
    return $this->belongsTo(Cultivate::class);
  }

  public function consumes()
  {
    return $this->hasMany(Consume::class, 'harvest_id');
  }

}
