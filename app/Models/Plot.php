<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plot extends Model
{
	use HasFactory;

	// Mendefinisikan nama tabel
	protected $table = 'plots';

	// Mengganti default primary key
	// protected $primaryKey = 'plotId';

	// Kolom yang dapat diisi
	protected $fillable = [
		'plot_name',
	];

	/**
	 * Relasi One-to-Many ke model Cultivate
	 */
	public function cultivates()
	{
		// Parameter: (NamaModelRelasi, foreign_key_di_tabel_tujuan, local_key_di_tabel_ini)
		return $this->hasMany(Cultivate::class, 'plot_id');
	}
}
