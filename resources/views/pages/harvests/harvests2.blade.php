@extends('layouts.app')

@section('content')
  <!-- Inisialisasi Alpine.js dengan fungsi harvestBoard() -->
  <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10" x-data="harvestBoard()">

    <!-- 1. Header & Search -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Panen & Gudang</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola hasil panen dan catat pengeluaran stok.</p>
      </div>

      <!-- 1.5 Baris Pencarian (Search) -->
      <div class="mb-6 flex">
        <div class="relative w-full sm:w-72 xl:w-96">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">
            <i class="fa-solid fa-search"></i>
          </span>
          <input type="text" x-model="search" placeholder="Cari tanaman, blok, atau gelombang..."
            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-14 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
        </div>
      </div>
    </div>


    <!-- Tampilan Jika Data Tidak Ditemukan -->
    <div x-show="filteredHarvests.length === 0" x-cloak
      class="flex flex-col items-center justify-center rounded-xl border border-stroke bg-white dark:bg-gray-700 py-16 px-4 shadow-sm dark:border-strokedark dark:bg-boxdark">
      <div
        class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800">
        <i class="fa-solid fa-box-open text-2xl"></i>
      </div>
      <h3 class="text-lg font-bold text-gray-900 dark:text-white">Data tidak ditemukan</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 text-center">
        Riwayat panen dengan kata kunci tersebut tidak ada di dalam gudang.
      </p>
      <button @click="search = ''" class="mt-4 text-sm font-medium text-[#E65C00] hover:underline dark:text-orange-200">
        Bersihkan Pencarian
      </button>
    </div>

    <!-- 2. Grid Cards Riwayat Panen -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3" x-show="paginatedHarvests.length > 0">

      <!-- Looping Data Gudang Panen -->
      <template x-for="item in paginatedHarvests" :key="item.id">
        <div
          class="relative flex flex-col rounded-xl border border-stroke bg-white dark:bg-brand-green-bg shadow-sm overflow-visible transition hover:shadow-md dark:border-strokedark dark:bg-boxdark">

          <!-- Card Header (Gelombang & Tanggal) -->
          <div
            class="flex items-center justify-between rounded-t-xl border-b border-orange-100 bg-[#FFFaf0] px-5 py-3 dark:border-orange-900/30 dark:bg-orange-900/20">
            <div class="flex items-center gap-2 font-bold text-[#E65C00] dark:text-orange-200">
              <span>📦</span> <span x-text="item.gelombang"></span>
            </div>

            <div class="flex items-center gap-3">
              <div class="text-sm font-semibold text-gray-500 dark:text-gray-400" x-text="item.tanggal_panen"></div>

              <!-- Dropdown Menu -->
              <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false"
                  class="flex h-6 w-6 items-center justify-center rounded text-gray-400 hover:bg-orange-100 hover:text-[#E65C00] transition dark:hover:bg-orange-900/50">
                  ⋮
                </button>

                <div x-show="open" x-cloak x-transition
                  class="absolute right-0 mt-2 w-36 rounded-md border border-stroke bg-white shadow-lg dark:border-strokedark dark:bg-boxdark z-30">
                  <button @click="$dispatch('open-modal-modal-panen', { 
                                          mode: 'edit', 
                                          action: `/harvests/${item.id}`, 
                                          data: item 
                                      }); open = false"
                    class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-brand-100 hover:text-brand-700 dark:text-gray-200 dark:hover:bg-brand-900/20">
                    ✏️ Edit Panen
                  </button>
                  <form :action="`/harvests/${item.id}`" method="POST"
                    class="m-0 border-t border-stroke dark:border-strokedark"
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

          <!-- Card Body -->
          <div class="p-5">
            <!-- Info Tanaman & Lokasi -->
            <div class="mb-6 flex items-center gap-4">
              <div
                class="flex h-[72px] w-[72px] shrink-0 items-center justify-center rounded-2xl bg-[#FEF5E5] text-4xl dark:bg-orange-900/20"
                x-text="item.icon"></div>
              <div>
                <h3 class="text-xl font-bold text-[#11310E] dark:text-white" x-text="item.nama_tanaman"></h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="'Dari: 🪧 ' + item.lokasi"></p>
              </div>
            </div>

            <!-- Stok Progress -->
            <div class="mb-5">
              <div class="mb-2 flex items-end justify-between">
                <span class="text-sm text-gray-500 dark:text-gray-400">Sisa Stok:</span>
                <div class="text-sm text-gray-700 dark:text-gray-300">
                  <span class="text-lg font-bold" :class="item.sisa_stok === 0 ? 'text-error-500' : 'text-[#4CA716]'"
                    x-text="item.sisa_stok"></span>
                  / <span x-text="`${item.total_panen} ${item.satuan}`"></span>
                </div>
              </div>

              <!-- Progress Bar Dinamis -->
              <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700 relative">
                <div class="h-full rounded-full transition-all duration-500"
                  :class="item.sisa_stok === 0 ? 'bg-error-500' : 'bg-[#4CA716]'"
                  :style="`width: ${(item.sisa_stok / item.total_panen) * 100}%`"></div>
              </div>
            </div>

            <hr class="mb-4 border-gray-100 dark:border-strokedark">

            <!-- Tombol Catat Penggunaan (Disabled jika stok 0) -->
            <button
              @click="$dispatch('open-modal-modal-penggunaan', { mode: 'add', action: '/usages', data: { harvest_id: item.id } })"
              :disabled="item.sisa_stok === 0"
              class="flex w-full items-center justify-center gap-2 rounded-lg border py-2.5 text-sm font-bold transition disabled:opacity-50 disabled:cursor-not-allowed"
              :class="item.sisa_stok === 0 
                          ? 'border-gray-300 text-gray-400 dark:border-gray-600 dark:text-gray-500' 
                          : 'border-[#E65C00] text-[#E65C00] hover:bg-[#FFFaf0] dark:border-orange-500 dark:text-orange-200 dark:hover:bg-orange-900/20'">
              🛒 <span x-text="item.sisa_stok === 0 ? 'Stok Habis' : 'Catat Penggunaan'"></span>
            </button>

          </div>
        </div>
      </template>

    </div>

    <!-- 2.5 Baris Pagination -->
    <div x-show="filteredHarvests.length > itemsPerPage" x-cloak
      class="mt-8 flex flex-col sm:flex-row items-center justify-between border-t border-stroke pt-5 dark:border-strokedark gap-4">
      <p class="text-sm text-gray-500 dark:text-gray-400 text-center sm:text-left">
        Menampilkan <span class="font-semibold text-gray-900 dark:text-white"
          x-text="((currentPage - 1) * itemsPerPage) + 1"></span>
        - <span class="font-semibold text-gray-900 dark:text-white"
          x-text="Math.min(currentPage * itemsPerPage, filteredHarvests.length)"></span>
        dari <span class="font-semibold text-gray-900 dark:text-white" x-text="filteredHarvests.length"></span> data
      </p>

      <div class="flex items-center gap-1">
        <!-- Tombol Prev -->
        <button @click="prevPage()" :disabled="currentPage === 1" class="flex h-8 w-8 items-center justify-center rounded border transition
               border-gray-200 bg-transparent text-gray-400
               hover:border-brand-500 hover:text-brand-600
               disabled:border-gray-100 disabled:text-gray-300 disabled:cursor-not-allowed
               dark:border-gray-700 dark:text-gray-500
               dark:hover:border-brand-500 dark:hover:text-brand-400
               dark:disabled:border-gray-800 dark:disabled:text-gray-700">
          <i class="fa-solid fa-chevron-left text-xs"></i>
        </button>

        <!-- Loop Nomer Halaman -->
        <template x-for="page in totalPages" :key="page">
          <button @click="goToPage(page)"
            class="flex h-8 w-8 items-center justify-center rounded border text-sm font-medium transition"
            :class="currentPage === page
            ? 'border-brand-600 bg-brand-600 text-white shadow-sm'
            : 'border-gray-200 bg-transparent text-gray-600 hover:border-brand-500 hover:text-brand-600 dark:border-gray-700 dark:text-gray-300 dark:hover:border-brand-500 dark:hover:text-brand-400'" x-text="page">
          </button>
        </template>

        <!-- Tombol Next -->
        <button @click="nextPage()" :disabled="currentPage === totalPages" class="flex h-8 w-8 items-center justify-center rounded border transition
               border-gray-200 bg-transparent text-gray-400
               hover:border-brand-500 hover:text-brand-600
               disabled:border-gray-100 disabled:text-gray-300 disabled:cursor-not-allowed
               dark:border-gray-700 dark:text-gray-500
               dark:hover:border-brand-500 dark:hover:text-brand-400
               dark:disabled:border-gray-800 dark:disabled:text-gray-700">
          <i class="fa-solid fa-chevron-right text-xs"></i>
      </div>
    </div>

    <!-- 3. Definisi Form & Modal (Sama seperti bawaan Anda) -->
    @php
      $panenFields = [
        ['name' => 'tanaman_id', 'label' => 'Tanaman yang Dipanen', 'type' => 'select', 'options' => ['1' => 'Tomat Sayur', '2' => 'Sawi Hijau']],
        ['name' => 'bedengan_id', 'label' => 'Asal Bedengan', 'type' => 'select', 'options' => ['1' => 'Blok 1', '2' => 'Blok 2']],
        ['name' => 'tanggal_panen', 'label' => 'Tanggal Panen', 'type' => 'text', 'placeholder' => 'Isi Tanggal'],
        ['name' => 'jumlah_hasil', 'label' => 'Total Panen Awal (Kg)', 'type' => 'number', 'placeholder' => 'Isi Jumlah']
      ];
    @endphp
    {{-- <x-ui.modal-form id="modal-panen" title="Data Panen" :fields="$panenFields" /> --}}

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
    {{-- <x-ui.modal-form id="modal-penggunaan" title="Penggunaan Stok" action="/harvests/add" :fields="$penggunaanFields" /> --}}

  </div>
@endsection

@push('scripts')
  <script>
    function harvestBoard() {
      return {
        search: '',
        currentPage: 1,
        itemsPerPage: 6, // Set ke 6 supaya grid 3 kolom terlihat bagus

        // Data Dummy Array Object
        // harvests: [
        //   { id: 1, : 'Gelombang 1', tanggal_panen: '12 Mei 2026', icon: '🍅', nama_tanaman: 'Tomat Sayur', lokasi: 'Blok 2', sisa_stok: 75, total_panen: 100, satuan: 'Kg' },
        //   { id: 2, gelombang: 'Gelombang 1', tanggal_panen: '10 Mei 2026', icon: '🥬', nama_tanaman: 'Sawi Hijau', lokasi: 'Blok 1', sisa_stok: 20, total_panen: 50, satuan: 'Ikat' },
        //   { id: 3, gelombang: 'Gelombang 2', tanggal_panen: '15 Mei 2026', icon: '🌶️', nama_tanaman: 'Cabai Rawit', lokasi: 'Blok 3', sisa_stok: 40, total_panen: 40, satuan: 'Kg' },
        //   { id: 4, gelombang: 'Gelombang 1', tanggal_panen: '08 Mei 2026', icon: '🧅', nama_tanaman: 'Bawang Merah', lokasi: 'Blok 4', sisa_stok: 0, total_panen: 80, satuan: 'Kg' },
        //   { id: 5, gelombang: 'Gelombang 3', tanggal_panen: '18 Mei 2026', icon: '🥕', nama_tanaman: 'Wortel Manis', lokasi: 'Blok 2', sisa_stok: 100, total_panen: 150, satuan: 'Kg' },
        //   { id: 6, gelombang: 'Gelombang 2', tanggal_panen: '14 Mei 2026', icon: '🌱', nama_tanaman: 'Kangkung Cabut', lokasi: 'Blok 1', sisa_stok: 5, total_panen: 60, satuan: 'Ikat' },
        //   { id: 7, gelombang: 'Gelombang 4', tanggal_panen: '20 Mei 2026', icon: '🍅', nama_tanaman: 'Tomat Cherry', lokasi: 'Blok 5', sisa_stok: 30, total_panen: 30, satuan: 'Kg' },
        // ],

        harvests: {{ Js::from($formattedHarvests) }},

        init() {
          // Reset halaman jika sedang melakukan pencarian
          this.$watch('search', () => { this.currentPage = 1; });
        },

        // Fitur Pencarian Data
        get filteredHarvests() {
          if (this.search === '') return this.harvests;

          const searchTerm = this.search.toLowerCase();
          return this.harvests.filter(item =>
            item.nama_tanaman.toLowerCase().includes(searchTerm) ||
            item.lokasi.toLowerCase().includes(searchTerm) ||
            item.gelombang.toLowerCase().includes(searchTerm)
          );
        },

        // Hitung Total Halaman
        get totalPages() {
          return Math.ceil(this.filteredHarvests.length / this.itemsPerPage);
        },

        // Potong Array untuk Halaman Saat Ini
        get paginatedHarvests() {
          const start = (this.currentPage - 1) * this.itemsPerPage;
          const end = start + this.itemsPerPage;
          return this.filteredHarvests.slice(start, end);
        },

        // Navigasi Pagination
        nextPage() {
          if (this.currentPage < this.totalPages) this.currentPage++;
        },
        prevPage() {
          if (this.currentPage > 1) this.currentPage--;
        },
        goToPage(page) {
          this.currentPage = page;
        }
      }
    }
  </script>
@endpush