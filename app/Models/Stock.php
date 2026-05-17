<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $guarded = [];

    // Menambahkan field dinamis 'current_stock' saat model dipanggil
    protected $appends = ['current_stock'];

    public function getCurrentStockAttribute()
    {
        return $this->total_harvest - $this->total_consume;
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
}
