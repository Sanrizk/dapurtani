<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Water extends Model
{
  use HasFactory;

  // protected $primaryKey = 'waterId';
  protected $fillable = ['cultivate_id', 'datetime'];

  public function cultivate()
  {
    return $this->belongsTo(Water::class, 'cultivate_id');
  }

  
}
