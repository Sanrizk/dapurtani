@extends('layouts.app')

@section('content')
  <div x-data="laporanDapurtani()" class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center no-print">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Laporan Hasil Panen</h1>
        <p class="text-sm text-gray-500">Filter dan unduh rekapitulasi data hasil panen tanaman.</p>
      </div>
    </div>

    <!-- Card Filter (Disembunyikan saat cetak) -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 no-print">
      <form @submit.prevent="tampilkanPreview()" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

          <!-- Filter Rentang Bulan (Mulai) -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan Mulai</label>
            <select x-model="filter.bulanMulai"
              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 border p-2.5 bg-gray-50">
              <option value="">-- Pilih Bulan --</option>
              <template x-for="(bulan, index) in daftarBulan" :key="index">
                <option :value="index + 1" x-text="bulan"></option>
              </template>
            </select>
          </div>

          <!-- Filter Rentang Bulan (Selesai) -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan Selesai</label>
            <select x-model="filter.bulanSelesai"
              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 border p-2.5 bg-gray-50">
              <option value="">-- Pilih Bulan --</option>
              <template x-for="(bulan, index) in daftarBulan" :key="index">
                <option :value="index + 1" x-text="bulan"></option>
              </template>
            </select>
          </div>

          <!-- Filter Tahun -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
            <select x-model="filter.tahun"
              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 border p-2.5 bg-gray-50">
              <option value="2026">2026</option>
              <option value="2025">2025</option>
              <option value="2024">2024</option>
            </select>
          </div>

          <!-- Filter Nama Pohon/Tanaman -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Tanaman</label>
            <select x-model="filter.tanaman"
              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 border p-2.5 bg-gray-50">
              <option value="semua">Semua Tanaman</option>
              <optgroup label="Sayuran">
                <option value="Bayam">Bayam</option>
                <option value="Kangkung">Kangkung</option>
                <option value="Sawi Hijau">Sawi Hijau</option>
                <option value="Tomat">Tomat</option>
              </optgroup>
              <optgroup label="Buah">
                <option value="Jeruk">Pohon Jeruk</option>
                <option value="Mangga">Pohon Mangga</option>
                <option value="Pisang">Pohon Pisang</option>
              </optgroup>
            </select>
          </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-gray-100">
          <button type="submit"
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-5 rounded-lg flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            Tampilkan Data
          </button>

          <!-- Tombol Export Excel -->
          <button type="button" @click="unduhExcel()" x-show="hasData"
            class="bg-emerald-700 hover:bg-emerald-800 text-white font-medium py-2 px-5 rounded-lg flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
              </path>
            </svg>
            Export Excel
          </button>
        </div>
      </form>
    </div>

    <!-- Area Preview Laporan -->
    <div x-show="hasData" x-transition
      class="bg-white p-6 sm:p-10 rounded-xl shadow-sm border border-gray-200 print-container">

      <div class="text-center mb-8 border-b-2 border-gray-800 pb-6">
        <h2 class="text-2xl font-bold uppercase tracking-wider text-gray-900">Rekapitulasi Hasil Panen</h2>
        <p class="text-gray-700 text-md mt-1">Dapurtani - Monitoring Agrikultur</p>
        <p class="text-sm text-gray-500 mt-1">
          Periode: <span x-text="teksPeriodeFilter()" class="font-medium"></span>
          | Tanaman: <span x-text="filter.tanaman === 'semua' ? 'Semua Jenis' : filter.tanaman"
            class="font-medium"></span>
        </p>
      </div>

      <!-- Tabel Data -->
      <div class="overflow-x-auto rounded-lg border border-gray-200 print:border-none print:overflow-visible">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-100 text-gray-700 print:bg-gray-200">
              <th class="border-b-2 border-gray-300 px-4 py-3 text-sm font-semibold text-center w-16">No</th>
              <th class="border-b-2 border-gray-300 px-4 py-3 text-sm font-semibold">Nama Tanaman</th>
              <th class="border-b-2 border-gray-300 px-4 py-3 text-sm font-semibold text-right">Jumlah Panen</th>
              <th class="border-b-2 border-gray-300 px-4 py-3 text-sm font-semibold">Satuan</th>
            </tr>
          </thead>
          <tbody>
            <template x-for="(item, index) in dataPreview" :key="index">
              <tr class="hover:bg-gray-50">
                <td class="border-b border-gray-200 px-4 py-3 text-sm text-center" x-text="index + 1"></td>
                <td class="border-b border-gray-200 px-4 py-3 text-sm font-medium text-gray-800" x-text="item.nama"></td>
                <td class="border-b border-gray-200 px-4 py-3 text-sm text-right font-medium text-emerald-600"
                  x-text="item.jumlah"></td>
                <td class="border-b border-gray-200 px-4 py-3 text-sm text-gray-600" x-text="item.satuan"></td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>

    <!-- State Kosong -->
    <div x-show="!hasData" class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-200 no-print">
      <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data ditampilkan</h3>
      <p class="mt-1 text-sm text-gray-500">Pilih rentang bulan, tahun, dan jenis tanaman di atas lalu klik Tampilkan
        Data.</p>
    </div>

  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('laporanDapurtani', () => ({
        daftarBulan: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        filter: {
          bulanMulai: '5',
          bulanSelesai: '8',
          tahun: '2026',
          tanaman: 'semua'
        },
        dataPreview: [],
        hasData: false,

        teksPeriodeFilter() {
          if (!this.filter.bulanMulai || !this.filter.bulanSelesai) return '-';
          const mulai = this.daftarBulan[this.filter.bulanMulai - 1];
          const selesai = this.daftarBulan[this.filter.bulanSelesai - 1];
          return `${mulai} ${this.filter.tahun} - ${selesai} ${this.filter.tahun}`;
        },

        tampilkanPreview() {
          if (parseInt(this.filter.bulanMulai) > parseInt(this.filter.bulanSelesai)) {
            alert('Bulan mulai tidak boleh lebih besar dari bulan selesai.');
            return;
          }

          this.dataPreview = [
            { nama: 'Tomat', jumlah: 450, satuan: 'Kg' },
            { nama: 'Bayam', jumlah: 120, satuan: 'Ikat' },
            { nama: 'Sawi Hijau', jumlah: 300, satuan: 'Ikat' },
            { nama: 'Pohon Mangga', jumlah: 85, satuan: 'Buah' },
          ];

          if (this.filter.tanaman !== 'semua') {
            this.dataPreview = this.dataPreview.filter(item => item.nama === this.filter.tanaman);
          }

          this.hasData = this.dataPreview.length > 0;

          if (!this.hasData) {
            alert('Tidak ada data hasil panen untuk filter tersebut.');
          }
        },

        unduhExcel() {
          const params = new URLSearchParams({
            bulan_mulai: this.filter.bulanMulai,
            bulan_selesai: this.filter.bulanSelesai,
            tahun: this.filter.tahun,
            tanaman: this.filter.tanaman
          });

          window.location.href = `/reports/excel?${params.toString()}`;
        }
      }))
    })
  </script>
@endpush