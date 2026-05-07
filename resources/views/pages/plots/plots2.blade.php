@extends('layouts.app')

@section('content')
  <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">

    <!-- 1. Header & Tombol Tambah -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Denah Bedengan</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola area tanam, kapasitas, dan status lahan.</p>
      </div>

      <button @click="$dispatch('open-modal-modal-bedengan', { mode: 'add', action: '/plots' })"
        class="rounded bg-brand-600 px-4 py-2 font-medium text-white hover:bg-brand-700 transition shadow-sm">
        + Tambah Bedengan
      </button>
    </div>

    <!-- 2. Grid Cards untuk Bedengan -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

      <!-- Card Bedengan 1 (Status: Terisi) -->
      <div
        class="relative flex flex-col rounded-sm border border-stroke bg-white dark:bg-brand-green-bg p-5 shadow-default transition hover:-translate-y-1 hover:shadow-lg dark:border-strokedark dark:bg-boxdark">
        <!-- Menu Dropdown -->
        <div x-data="{ open: false }" class="absolute right-3 top-3 z-10">
          <button @click="open = !open" @click.outside="open = false"
            class="text-gray-900 hover:text-gray-900 dark:hover:text-gray-500 pb-1 px-2 text-xl font-bold border rounded-full bg-white dark:bg-gray-900 dark:text-gray-200 border-gray-dark">⋮</button>

          <div x-show="open" x-transition
            class="absolute right-0 mt-1 w-32 rounded border border-stroke bg-white shadow-lg dark:border-strokedark dark:bg-boxdark z-20"
            style="display: none;">
            <button @click="$dispatch('open-modal-modal-bedengan', { 
                                            mode: 'edit', 
                                            action: '/plots/1', 
                                            data: { nama: 'Blok A1', ukuran: '1.5 x 10m', kapasitas: 50, status: 'terisi' } 
                                        }); open = false"
              class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white">
              ✏️ Edit
            </button>
            <button
              class="block w-full text-left px-4 py-2 text-sm text-error-500 hover:bg-error-50 dark:hover:bg-error-900/20">
              🗑️ Hapus
            </button>
          </div>
        </div>


        <!-- Visual Tanah Bedengan (Menggunakan Arbitrary Values Tailwind) -->
        <div
          class="mb-4 flex h-32 w-full items-center justify-center rounded-md border-4 border-dashed border-[#2E5E16] bg-[#1A3C0F] relative overflow-hidden">
          <!-- Ilustrasi tanaman tumbuh -->
          <div class="flex gap-3 text-3xl">
            <span>🌱</span><span>🌱</span><span>🌱</span>
          </div>
          <!-- Badge Kapasitas di dalam tanah -->
          <div
            class="absolute bottom-2 left-2 rounded bg-black/60 px-2 py-0.5 text-xs font-medium text-white backdrop-blur-sm">
            Kapasitas: 50
          </div>
        </div>

        <!-- Info Lahan -->
        <div class="flex-1">
          <h4 class="text-lg font-bold text-gray-900 dark:text-white">Blok A1</h4>
          <p class="text-sm text-gray-500 dark:text-gray-400">Ukuran: 1.5 x 10 Meter</p>
        </div>

        <!-- Status Footer -->
        <div class="mt-4 border-t border-stroke pt-4 dark:border-strokedark">
          <span
            class="inline-block rounded bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-700 border border-brand-200 dark:border-brand-800 dark:bg-brand-900/30 dark:text-brand-400">
            🟢 Sedang Ditanami
          </span>
        </div>
      </div>

      <!-- Card Bedengan 2 (Status: Kosong) -->
      <div
        class="relative flex flex-col rounded-sm border border-stroke bg-white dark:bg-brand-green-bg p-5 shadow-default transition hover:-translate-y-1 hover:shadow-lg dark:border-strokedark dark:bg-boxdark">
        <!-- Dropdown (Sama seperti di atas, disingkat) -->
        <div x-data="{ open: false }" class="absolute right-3 top-3 z-10">
          <button @click="open = !open" @click.outside="open = false"
            class="text-gray-900 hover:text-gray-900 dark:hover:text-gray-500 pb-1 px-2 text-xl font-bold border rounded-full bg-white dark:bg-gray-900 dark:text-gray-200 border-gray-dark">⋮</button>

          <div x-show="open" x-transition
            class="absolute right-0 mt-1 w-32 rounded border border-stroke bg-white shadow-lg dark:border-strokedark dark:bg-boxdark z-20"
            style="display: none;">
            <button @click="$dispatch('open-modal-modal-bedengan', { 
                                            mode: 'edit', 
                                            action: '/plots/1', 
                                            data: { nama: 'Blok A1', ukuran: '1.5 x 10m', kapasitas: 50, status: 'terisi' } 
                                        }); open = false"
              class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white">
              ✏️ Edit
            </button>
            <button
              class="block w-full text-left px-4 py-2 text-sm text-error-500 hover:bg-error-50 dark:hover:bg-error-900/20">
              🗑️ Hapus
            </button>
          </div>
        </div>

        <!-- Visual Tanah Kosong -->
        <div
          class="mb-4 flex h-32 w-full items-center justify-center rounded-md border-4 border-dashed border-[#2E5E16] bg-[#1A3C0F] relative opacity-70">
          <span class="text-sm text-white/70 font-medium">Lahan Kosong</span>
          <div class="absolute bottom-2 left-2 rounded bg-black/60 px-2 py-0.5 text-xs font-medium text-white">
            Kapasitas: 40
          </div>
        </div>

        <!-- Info Lahan -->
        <div class="flex-1">
          <h4 class="text-lg font-bold text-gray-900 dark:text-white">Blok A2</h4>
          <p class="text-sm text-gray-500 dark:text-gray-400">Ukuran: 1.2 x 8 Meter</p>
        </div>

        <!-- Status Footer -->
        <div class="mt-4 border-t border-stroke pt-4 dark:border-strokedark">
          <span
            class="inline-block rounded bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600 border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
            ⚪ Kosong (Siap Tanam)
          </span>
        </div>
      </div>

    </div>

    <!-- 3. Setup Array Data untuk Modal Form -->
    @php
      $plotFields = [
        ['name' => 'nama', 'label' => 'Nama/Kode Bedengan', 'type' => 'text', 'placeholder' => 'Nama ...'],
        ['name' => 'ukuran', 'label' => 'Dimensi (Misal: 1.5 x 10m)', 'type' => 'text', 'placeholder' => 'Ukuran ...'],
        ['name' => 'kapasitas', 'label' => 'Kapasitas (Lubang Tanam)', 'type' => 'number', 'placeholder' => 'Kapasitas ...'],
        [
          'name' => 'status',
          'label' => 'Status Lahan',
          'type' => 'select',
          'options' => [
            'kosong' => 'Kosong (Siap Tanam)',
            'terisi' => 'Sedang Ditanami',
            'istirahat' => 'Istirahat (Pembalikan Tanah)'
          ]
        ]
      ];
    @endphp

    <!-- Panggil Komponen Reusable Modal -->
    <x-ui.modal-form id="modal-bedengan" title="Bedengan" :fields="$plotFields" />

  </div>
@endsection