@extends('layouts.app')

@section('content')
  <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">

    <!-- 1. Header & Tombol Tambah (Menggunakan $dispatch ke Modal) -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Data Pohon</h2>

      <button @click="$dispatch('open-modal-modal-plants', { mode: 'add', action: '/plants' })"
        class="rounded bg-brand-600 px-4 py-2 font-medium text-white hover:bg-brand-700 transition">
        + Tambah Pohon
      </button>
    </div>

    <!-- 2. Grid Cards Tailadmin -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">

      <!-- Card Item -->
      <div
        class="relative rounded-sm bg-brand-orange-bg dark:bg-brand-green-bg p-6 shadow-default transition hover:-translate-y-1 hover:shadow-lg">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-2xl dark:bg-brand-900/20">
            🥬
          </div>
          <div>
            <h4 class="text-lg font-bold text-gray-900 dark:text-white">Sawi Hijau</h4>
            <div class="mt-1 flex gap-2 text-xs font-medium">
              <span
                class="rounded bg-warning-100 px-2 py-1 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400">⏳
                30 Hari</span>
              <span class="rounded bg-brand-100 px-2 py-1 text-brand-700 dark:bg-brand-900/30 dark:text-brand-400">📦
                Ikat</span>
            </div>
          </div>
        </div>

        <!-- Dropdown Alpine.js untuk Edit/Hapus -->
        <div x-data="{ open: false }" class="absolute right-4 top-4">
          <button @click="open = !open" @click.outside="open = false"
            class="text-gray-400 hover:text-gray-900 dark:hover:text-white text-xl font-bold">⋮</button>

          <div x-show="open" x-transition
            class="absolute right-0 mt-2 w-32 rounded border border-stroke bg-brand-white shadow-lg dark:border-strokedark dark:bg-boxdark z-10"
            style="display: none;">
            <!-- Tombol Edit melempar data lama ke Modal -->
            <button @click="$dispatch('open-modal-modal-plants', { 
                                    mode: 'edit', 
                                    action: '/plants/1', 
                                    data: { nama: 'Sawi Hijau', waktu_panen: 30, satuan: 'Ikat', status: 'aktif' } 
                                }); open = false"
              class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-400 dark:hover:bg-gray-700 dark:text-white">
              ✏️ Edit
            </button>
            <button
              class="block w-full text-left px-4 py-2 text-sm text-error-500 hover:bg-error-50 hover:bg-error-900/20">
              🗑️ Hapus
            </button>
          </div>
        </div>
      </div>
      <!-- End Card Item -->

    </div>

    <!-- 3. Definisi Form & Panggil Komponen Modal -->
    @php
      $plantFields = [
        ['name' => 'nama', 'label' => 'Nama Pohon', 'type' => 'text', 'placeholder' => 'Masukkan nama pohon'],
        ['name' => 'waktu_panen', 'label' => 'Waktu Panen (Hari)', 'type' => 'number', 'placeholder' => 'Misal: 90 (artinya 90 Hari)'],
        ['name' => 'satuan', 'label' => 'Satuan', 'type' => 'select', 'options' => ['Ikat' => 'Ikat', 'Kg' => 'Kilogram']],
        ['name' => 'status', 'label' => 'Status Pohon', 'type' => 'radio', 'options' => ['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif']]
      ];
    @endphp

    <!-- Komponen ini akan hidden secara default dan muncul dari tengah layar dengan efek blur -->
    <x-ui.modal-form id="modal-plants" title="Data Pohon" :fields="$plantFields" />

  </div>
@endsection