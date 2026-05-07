@extends('layouts.app')

@section('content')
  <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Panen & Gudang</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola hasil panen dan catat pengeluaran stok.</p>
      </div>

      <button @click="$dispatch('open-modal-modal-panen', { mode: 'add', action: '/harvests' })"
        class="rounded bg-brand-600 px-5 py-2.5 font-medium text-white shadow-sm hover:bg-brand-700 transition">
        + Catat Panen Baru
      </button>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">

      <div
        class="relative flex flex-col rounded-xl border border-stroke bg-white shadow-sm overflow-visible transition hover:shadow-md dark:border-strokedark dark:bg-boxdark">

        <div
          class="flex items-center justify-between rounded-t-xl border-b border-orange-100 bg-[#FFFaf0] px-5 py-3 dark:border-orange-900/30 dark:bg-orange-900/20">
          <div class="flex items-center gap-2 font-bold text-[#E65C00] dark:text-orange-400">
            <span>📦</span> Gelombang 1
          </div>

          <div class="flex items-center gap-3">
            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">
              12 Mei 2026
            </div>

            <div x-data="{ open: false }" class="relative">
              <button @click="open = !open" @click.outside="open = false"
                class="flex h-6 w-6 items-center justify-center rounded text-gray-400 hover:bg-orange-100 hover:text-[#E65C00] transition dark:hover:bg-orange-900/50">
                ⋮
              </button>

              <div x-show="open" x-cloak x-transition
                class="absolute right-0 mt-2 w-36 rounded-md border border-stroke bg-white shadow-lg dark:border-strokedark dark:bg-boxdark z-30"
                style="display: none;">
                <button @click="$dispatch('open-modal-modal-panen', { 
                                              mode: 'edit', 
                                              action: '/harvests/1', 
                                              data: { tanaman_id: '1', bedengan_id: '1', tanggal_panen: '2026-05-12', jumlah_hasil: 100 } 
                                          }); open = false"
                  class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 dark:text-gray-200 dark:hover:bg-brand-900/20">
                  ✏️ Edit Panen
                </button>
                <form action="/harvests/1" method="POST" class="m-0 border-t border-stroke dark:border-strokedark"
                  @submit.prevent="if(confirm('Yakin ingin menghapus data panen ini? Stok juga akan terhapus.')) $el.submit()">
                  @csrf @method('DELETE')
                  <button type="submit"
                    class="flex w-full items-center gap-2 px-4 py-2 text-sm text-error-500 hover:bg-error-50 dark:hover:bg-error-900/20">
                    🗑️ Hapus
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="p-5">

          <div class="mb-6 flex items-center gap-4">
            <div
              class="flex h-[72px] w-[72px] shrink-0 items-center justify-center rounded-2xl bg-[#FEF5E5] text-4xl dark:bg-orange-900/20">
              🍅
            </div>
            <div>
              <h3 class="text-xl font-bold text-[#11310E] dark:text-white">Tomat Sayur</h3>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Dari: 🪧 Blok 2
              </p>
            </div>
          </div>

          <div class="mb-5">
            <div class="mb-2 flex items-end justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Sisa Stok:</span>
              <div class="text-sm text-gray-700 dark:text-gray-300">
                <span class="text-lg font-bold text-[#4CA716]">75</span> / 100 Kg
              </div>
            </div>
            <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
              <div class="h-full rounded-full bg-[#4CA716] transition-all duration-500" style="width: 75%"></div>
            </div>
          </div>

          <hr class="mb-4 border-gray-100 dark:border-strokedark">

          <button
            @click="$dispatch('open-modal-modal-penggunaan', { mode: 'add', action: '/usages', data: { harvest_id: '1' } })"
            class="flex w-full items-center justify-center gap-2 rounded-lg border border-[#E65C00] py-2.5 text-sm font-bold text-[#E65C00] transition hover:bg-[#FFFaf0] dark:border-orange-500 dark:text-orange-500 dark:hover:bg-orange-900/20">
            🛒 Catat Penggunaan
          </button>

        </div>
      </div>
    </div>

    @php
      $panenFields = [
        ['name' => 'tanaman_id', 'label' => 'Tanaman yang Dipanen', 'type' => 'select', 'options' => ['1' => 'Tomat Sayur', '2' => 'Sawi Hijau']],
        ['name' => 'bedengan_id', 'label' => 'Asal Bedengan', 'type' => 'select', 'options' => ['1' => 'Blok 1', '2' => 'Blok 2']],
        ['name' => 'tanggal_panen', 'label' => 'Tanggal Panen', 'type' => 'text', 'placeholder' => 'Isi Tanggal'],
        ['name' => 'jumlah_hasil', 'label' => 'Total Panen Awal (Kg)', 'type' => 'number', 'placeholder' => 'Isi Jumlah']
      ];
    @endphp
    <x-ui.modal-form id="modal-panen" title="Data Panen" :fields="$panenFields" />

    @php
      $penggunaanFields = [
        ['name' => 'harvest_id', 'label' => 'ID Panen', 'type' => 'hidden'],
        ['name' => 'jumlah_keluar', 'label' => 'Jumlah Keluar (Kg)', 'type' => 'number', 'placeholder' => 'Isi Penggunaan'],
        [
          'name' => 'kategori',
          'label' => 'Tujuan',
          'type' => 'select',
          'options' => [
            'dijual' => '💰 Dijual / Pasar',
            'konsumsi' => '🍽️ Konsumsi Pribadi',
            'rusak' => '🥀 Rusak / Afkir'
          ]
        ],
        ['name' => 'tanggal_keluar', 'label' => 'Tanggal Penggunaan', 'type' => 'text', 'placeholder' => 'Isi Tanggal']
      ];
    @endphp
    <x-ui.modal-form id="modal-penggunaan" title="Penggunaan Stok" :fields="$penggunaanFields" />

  </div>
@endsection

