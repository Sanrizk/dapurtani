@extends('layouts.app')

@section('content')
  <style>
    @media print {
      body * {
        visibility: hidden;
      }

      .print-container,
      .print-container * {
        visibility: visible;
      }

      .print-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 0;
        box-shadow: none !important;
        border: none !important;
      }

      .print-container .print\:hidden {
        display: none !important;
      }

      .print-container {
        background-color: white !important;
        color: black !important;
      }

      .print-container table {
        background-color: white !important;
      }

      .print-container thead tr {
        background-color: #f3f4f6 !important;
        color: #374151 !important;
      }

      .print-container th {
        color: #111827 !important;
        border-color: #1f2937 !important;
      }

      .print-container td {
        color: #374151 !important;
        border-color: #9ca3af !important;
        background-color: white !important;
      }

      .print-container h2 {
        color: #111827 !important;
      }

      .print-container p {
        color: #374151 !important;
      }
    }
  </style>

  <div x-data="laporanDapurtani()" class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center no-print">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Hasil Panen</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Filter dan pratinjau</p>
      </div>
    </div>

    <!-- Card Filter -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 no-print">
      <form @submit.prevent="tampilkanPreview()" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
          <!-- Tipe Data -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Data</label>
            <select x-model="filter.tipe"
              class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 border p-2.5 bg-gray-50 dark:bg-gray-700 dark:text-white">
              <option value="semua">Semua</option>
              <option value="panen">Panen</option>
              <option value="penggunaan">Penggunaan</option>
            </select>
          </div>

          <!-- Bulan Mulai -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan Mulai</label>
            <select x-model="filter.bulanMulai"
              class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 border p-2.5 bg-gray-50 dark:bg-gray-700 dark:text-white">
              <option value="">-- Pilih Bulan --</option>
              <template x-for="(bulan, index) in daftarBulan" :key="index">
                <option :value="index + 1" x-text="bulan"></option>
              </template>
            </select>
          </div>

          <!-- Bulan Selesai -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan Selesai</label>
            <select x-model="filter.bulanSelesai"
              class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 border p-2.5 bg-gray-50 dark:bg-gray-700 dark:text-white">
              <option value="">-- Pilih Bulan --</option>
              <template x-for="(bulan, index) in daftarBulan" :key="index">
                <option :value="index + 1" x-text="bulan"></option>
              </template>
            </select>
          </div>

          <!-- Tahun -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun</label>
            <select x-model="filter.tahun"
              class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 border p-2.5 bg-gray-50 dark:bg-gray-700 dark:text-white">
              <option value="2026">2026</option>
              <option value="2025">2025</option>
              <option value="2024">2024</option>
            </select>
          </div>

          <!-- Jenis Tanaman -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Tanaman</label>
            <select x-model="filter.tanaman"
              class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 border p-2.5 bg-gray-50 dark:bg-gray-700 dark:text-white">
              <option value="semua">Semua Tanaman</option>
              @foreach ($plants as $plant)
                <option value="{{ $plant->plant_name }}">{{ $plant->plant_name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
          <button type="submit"
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-5 rounded-lg flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            Tampilkan Data
          </button>

          <button type="button" @click="cetakLaporan()" x-show="hasData" x-transition
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-5 rounded-lg flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
              </path>
            </svg>
            Cetak Laporan
          </button>
        </div>
      </form>
    </div>

    <!-- Area Preview Laporan -->
    <div x-show="hasData" x-transition
      class="bg-white dark:bg-gray-800 p-6 sm:p-10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 print-container">

      <!-- Kop Cetak -->
      <div class="text-center mb-8 border-b-2 border-gray-800 dark:border-gray-500 pb-6">
        <h2 class="text-2xl font-bold uppercase tracking-wider text-gray-900 dark:text-white">Rekapitulasi Hasil Panen
        </h2>
        <p class="text-gray-700 dark:text-gray-300 text-md mt-1">Dapurtani</p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
          Periode: <span x-text="teksPeriodeFilter()" class="font-medium"></span>
          | Tanaman: <span x-text="filter.tanaman === 'semua' ? 'Semua Jenis' : filter.tanaman"
            class="font-medium"></span>
        </p>
      </div>

      <!-- Tabel Data -->
      <div
        class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 print:border-none print:overflow-visible">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 print:bg-gray-200">
              <th
                class="border-b-2 border-gray-300 dark:border-gray-600 px-4 py-3 text-sm font-semibold print:border-gray-800 text-center">
                No.</th>
              <th
                class="border-b-2 border-gray-300 dark:border-gray-600 px-4 py-3 text-sm font-semibold print:border-gray-800">
                Tanggal</th>
              <th
                class="border-b-2 border-gray-300 dark:border-gray-600 px-4 py-3 text-sm font-semibold print:border-gray-800">
                Tipe</th>
              <th
                class="border-b-2 border-gray-300 dark:border-gray-600 px-4 py-3 text-sm font-semibold print:border-gray-800">
                Nama Tanaman</th>
              <th
                class="border-b-2 border-gray-300 dark:border-gray-600 px-4 py-3 text-sm font-semibold print:border-gray-800 text-right">
                Jumlah</th>
              <th
                class="border-b-2 border-gray-300 dark:border-gray-600 px-4 py-3 text-sm font-semibold print:border-gray-800">
                Batch</th>
            </tr>
          </thead>
          <tbody>
            <template x-for="(item, index) in dataPreview" :key="index">
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td
                  class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 text-sm text-center text-gray-700 dark:text-gray-300 print:border-gray-400"
                  x-text="index + 1"></td>
                <td
                  class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 print:border-gray-400"
                  x-text="item.tanggal"></td>
                <td
                  class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 print:border-gray-400"
                  x-text="item.tipe"><span x-text="item.tipe" :class="item.tipe === 'panen'
                  ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300'
                  : 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300'"
                    class="px-2 py-0.5 rounded-full text-xs font-semibold capitalize">
                  </span></td>
                <td
                  class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 text-sm font-medium text-gray-800 dark:text-white print:border-gray-400"
                  x-text="item.nama"></td>
                <td
                  class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 text-sm text-right font-medium text-gray-700 dark:text-gray-300 print:border-gray-400"
                  x-text="item.jumlah + ' ' + item.satuan"></td>
                <td
                  class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 text-sm text-gray-600 dark:text-gray-400 print:border-gray-400"
                  x-text="item.batch"></td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>

      <div class="mt-12 flex justify-end hidden print-only text-gray-800">
        <div class="text-center w-56">
          <p class="text-sm">Mengetahui,</p>
          <p class="text-sm mt-1">Admin Dapurtani</p>
          <div class="h-24"></div>
          <p class="font-bold underline">Ikhsan Rizki</p>
        </div>
      </div>

    </div>

    <!-- State Kosong -->
    <div x-show="!hasData"
      class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 no-print">
      <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24"
        stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada data ditampilkan</h3>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pilih rentang bulan, tahun, dan jenis tanaman di atas lalu
        klik Tampilkan Data.</p>
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
          tanaman: 'semua',
          tipe: 'semua'
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

          const semuaData = @json($newdata);

          this.dataPreview = semuaData.filter(item => {
            if (this.filter.tipe !== 'semua' && item.tipe !== this.filter.tipe) return false;
            if (this.filter.tanaman !== 'semua' && item.nama !== this.filter.tanaman) return false;
            if (item.bulan && item.tahun) {
              if (item.bulan < this.filter.bulanMulai) return false;
              if (item.bulan > this.filter.bulanSelesai) return false;
              if (item.tahun != this.filter.tahun) return false;
            }
            return true;
          });

          this.hasData = this.dataPreview.length > 0;
          if (!this.hasData) alert('Tidak ada data untuk filter tersebut.');
        },

        cetakLaporan() {
          window.print();
        }
      }))
    })
  </script>
@endpush