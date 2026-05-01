<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fertilize extends Model
{
  use HasFactory;

  protected $fillable = ['cultivate_id', 'datetime'];

  public function cultivate()
  {
    return $this->belongsTo(Cultivate::class, 'cultivate_id');
  }
}
