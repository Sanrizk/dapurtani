<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consume extends Model
{
  use HasFactory;

  protected $fillable = ['harvest_id', 'datetime', 'qty'];

  public function harvest() {
    return $this->belongsTo(Harvest::class, 'harvest_id');
  }
}
