@extends('layouts.app')

@section('content')
  <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">

    <!-- 1. Header & Tombol Mulai Tanam -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Monitoring Masa Tanam</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Pantau progres pertumbuhan, jadwal siram, dan estimasi
          panen.</p>
      </div>

      <button @click="$dispatch('open-modal-modal-tanam', { mode: 'add', action: '/cultivates' })"
        class="rounded bg-brand-600 px-5 py-2.5 font-medium text-white shadow-sm hover:bg-brand-700 transition">
        + Mulai Tanam Baru
      </button>
    </div>

    <!-- 2. Grid Cards Masa Tanam -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">

      <!-- Card Item: Sawi Hijau -->
      <div
        class="relative rounded-sm border border-stroke bg-white p-5 shadow-default dark:border-strokedark dark:bg-boxdark">

        <!-- Dropdown Edit/Delete -->
        <div x-data="{ open: false }" class="absolute right-3 top-3 z-10">
          <button @click="open = !open" @click.outside="open = false"
            class="flex h-8 w-8 items-center justify-center rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-800 dark:hover:text-white transition">⋮</button>

          <div x-show="open" x-cloak x-transition
            class="absolute right-0 mt-1 w-36 rounded-md border border-stroke bg-white shadow-lg dark:border-strokedark dark:bg-boxdark z-20">
            <button
              @click="$dispatch('open-modal-modal-tanam', { mode: 'edit', action: '/cultivates/1', data: { tanaman_id: '1', bedengan_id: '1', tanggal_tanam: '2026-05-01', jumlah_bibit: 50 } }); open = false"
              class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 dark:text-gray-200 dark:hover:bg-brand-900/20">
              ✏️ Edit Data
            </button>
            <form action="/cultivates/1" method="POST" class="m-0 border-t border-stroke dark:border-strokedark"
              @submit.prevent="if(confirm('Batalkan dan hapus siklus tanam ini?')) $el.submit()">
              @csrf @method('DELETE')
              <button type="submit"
                class="flex w-full items-center gap-2 px-4 py-2 text-sm text-error-500 hover:bg-error-50 dark:hover:bg-error-900/20">
                🗑️ Hapus
              </button>
            </form>
          </div>
        </div>

        <!-- Header Card: Tanaman & Lokasi -->
        <div class="flex items-center gap-4 border-b border-stroke pb-4 dark:border-strokedark">
          <div
            class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-brand-50 text-2xl dark:bg-brand-900/20">
            🥬</div>
          <div>
            <h4 class="text-lg font-bold text-gray-900 dark:text-white">Sawi Hijau</h4>
            <p class="text-sm font-medium text-brand-600 dark:text-brand-400">📍 Bedengan Blok A1</p>
          </div>
        </div>

        <!-- Konten: Progress & Info -->
        <div class="py-4">
          <!-- Tanggal -->
          <div class="mb-4 flex justify-between text-sm">
            <div>
              <p class="text-gray-500 dark:text-gray-400 text-xs mb-1">Tgl Tanam</p>
              <p class="font-semibold text-gray-900 dark:text-white">01 Mei 2026</p>
            </div>
            <div class="text-right">
              <p class="text-gray-500 dark:text-gray-400 text-xs mb-1">Est. Panen</p>
              <p class="font-semibold text-gray-900 dark:text-white">31 Mei 2026</p>
            </div>
          </div>

          <!-- Progress Bar Umur -->
          <div class="mb-1 flex justify-between text-xs font-medium">
            <span class="text-brand-700 dark:text-brand-400">Hari ke-7</span>
            <span class="text-gray-500">23 Hari Lagi</span>
          </div>
          <div class="h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
            <!-- Width dinamis dari backend, misal: style="width: ($hari_ini / $total_hari) * 100 %" -->
            <div class="h-2.5 rounded-full bg-brand-500 transition-all duration-500" style="width: 25%"></div>
          </div>
        </div>

        <!-- Footer: Quick Actions -->
        <div class="flex gap-2 pt-2">
          <!-- Tombol Catat Siram (Bisa langsung trigger method atau buka modal kecil) -->
          <button class="flex-1 flex items-center justify-center gap-2 rounded border border-blue-200 bg-blue-50 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50" @click="$dispatch('open-modal-log-siram', { 
            action: '/siram/1', 
                subtitle: 'Mencatat waktu penyiraman untuk <strong>Sawi Hijau</strong> di 🪧 <strong>Blok 1</strong>.',
                logs: [
                    { icon: '✔', text: '07 Mei 2026, 08:00 AM' },
                    { icon: '✔', text: '06 Mei 2026, 17:30 PM' },
                    { icon: '✔', text: '05 Mei 2026, 07:45 AM' }
                ]
            })">
            💧 Siram
          </button>
          <!-- Tombol Pupuk -->
          <button
            class="flex-1 flex items-center justify-center gap-2 rounded border border-amber-200 bg-amber-50 py-2 text-sm font-medium text-amber-600 transition hover:bg-amber-100 dark:border-amber-800 dark:bg-amber-900/30 dark:text-amber-400 dark:hover:bg-amber-900/50">
            🧪 Pupuk
          </button>
          <!-- Tombol Panen -->
          <button
            class="flex-1 flex items-center justify-center gap-2 rounded bg-brand-600 py-2 text-sm font-medium text-white transition hover:bg-brand-700 shadow-sm">
            ✂️ Panen
          </button>
        </div>

      </div>
      <!-- End Card Item -->

    </div>

    <!-- 3. Definisi Form Modal Tanam Baru -->
    @php
      $tanamFields = [
        [
          'name' => 'tanaman_id',
          'label' => 'Pilih Tanaman',
          'type' => 'select',
          'options' => [
            '1' => 'Sawi Hijau',
            '2' => 'Kangkung Cabut'
          ]
        ],
        [
          'name' => 'bedengan_id',
          'label' => 'Pilih Lokasi Bedengan',
          'type' => 'select',
          'options' => [
            '1' => 'Blok A1 (Kosong)',
            '2' => 'Blok A2 (Kosong)'
          ]
        ],
        ['name' => 'tanggal_tanam', 'label' => 'Tanggal Tanam', 'type' => 'text', 'placeholder' => 'Isi Tanggal'], // Nanti type text ini bisa diubah ke 'date' di komponen
        ['name' => 'jumlah_bibit', 'label' => 'Jumlah Bibit/Lubang', 'type' => 'number', 'placeholder' => 'Isi Jumlah bibit']
      ];

      $siramFields = [
        [
          'name' => 'waktu_siram',
          'label' => 'Waktu Disiram',
          'type' => 'datetime-local', // Otomatis memunculkan ikon kalender/jam bawaan browser
          'half' => false // Set true jika ingin sejajar sebelah-sebelahan
        ]
      ];
    @endphp

    <x-ui.modal-form id="modal-tanam" title="Data Masa Tanam" :fields="$tanamFields" />
    <x-ui.modal-log id="siram" title="Catat Penyiraman" icon="💧" colorTheme="blue" logTitle="RIWAYAT PENYIRAMAN"
      :fields="$siramFields" />

  </div>
@endsection