<?php

namespace App\Exports;

use App\Models\Harvest;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HarvestReport implements FromQuery, WithHeadings, WithMapping
{
  protected $filters;
  private $rowNumber = 0; // Untuk membuat kolom 'No' otomatis

  public function __construct(array $filters)
  {
    $this->filters = $filters;
  }

  public function query()
  {
    // Eager load relasi untuk menghindari N+1 query problem
    $query = Harvest::with('cultivate.plant');

    // Filter berdasarkan nama tanaman menggunakan whereHas yang menembus 2 relasi
    if ($this->filters['tanaman'] !== 'semua') {
      $query->whereHas('cultivate.plant', function ($q) {
        $q->where('plant_name', $this->filters['tanaman']);
      });
    }

    // Filter waktu (Asumsi field tanggal panen di tabel harvest bernama 'tanggal')
    if (!empty($this->filters['bulan_mulai']) && !empty($this->filters['bulan_selesai'])) {
      $query->whereYear('datetime', $this->filters['tahun'])
        ->whereMonth('datetime', '>=', $this->filters['bulan_mulai'])
        ->whereMonth('datetime', '<=', $this->filters['bulan_selesai']);
    }

    dd($query);

    return $query;
  }

  public function headings(): array
  {
    return [
      'No',
      'Nama Tanaman',
      'Jumlah Panen',
      'Satuan'
    ];
  }

  public function map($harvest): array
  {
    $this->rowNumber++;

    return [
      $this->rowNumber,
      $harvest->cultivate->plant->nama_tanaman ?? '-', // Menarik dari relasi
      $harvest->jumlah, // Sesuaikan ini dengan nama field jumlah panen di tabel harvest Anda
      $harvest->cultivate->plant->satuan ?? '-',       // Menarik dari relasi
    ];
  }
}