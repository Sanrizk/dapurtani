@extends('layouts.app')

@section('content')
  {{-- per batch --}}
  <!-- Inisialisasi Alpine.js dengan fungsi harvestBoard() -->
  <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10" x-data="harvestBoard({{ Js::from($formattedHarvests) }})">

    {{-- per batch --}}
    <!-- 1. Header & Search -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Panen Per Batch</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola hasil panen dan catat pengeluaran stok.</p>
      </div>

      <!-- 1.5 Baris Pencarian (Search) -->
      <div class="mb-6 flex">
        <div class="relative w-full sm:w-72 xl:w-96">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">
            <i class="fa-solid fa-search"></i>
          </span>
          <input type="text" x-model="search" placeholder="Cari tanaman, blok, atau batch..."
            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-14 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
        </div>
      </div>
    </div>

    {{-- data kosongan --}}
    <div class="flex flex-col items-center justify-center py-16 px-6 text-center" x-show="harvests.length === 0" x-cloak>
      <div class="w-20 h-20 mb-5 rounded-full bg-brand-100 dark:bg-brand-800 flex items-center justify-center">
        <svg class="w-10 h-10 text-brand-400 dark:text-brand-300" fill="none" stroke="currentColor" stroke-width="1.5"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 3C8 3 4.5 6.5 5 11c.3 2.5 2 4.5 4 5.5V19h6v-2.5c2-1 3.7-3 4-5.5.5-4.5-3-8-7-8z" />
        </svg>
      </div>
      <p class="text-lg font-semibold text-brand-800 dark:text-brand-400">Belum ada Panen</p>
      <p class="mt-1 text-sm text-brand-500">Data Panen yang ditambahkan akan muncul di sini.</p>
    </div>

    <!-- Tampilan Jika Data Tidak Ditemukan -->
    <div x-show="harvests.length > 0 && filteredHarvests.length === 0" x-cloak
      class="flex flex-col items-center justify-center rounded-xl border border-stroke bg-white dark:bg-gray-800 py-16 px-4 shadow-sm dark:border-strokedark dark:bg-boxdark">
      <div
        class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800">
        <i class="fa-solid fa-magnifying-glass text-2xl"></i>
      </div>
      <h3 class="text-lg font-bold text-gray-900 dark:text-white">Data tidak ditemukan</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 text-center">
        Tidak ada data Panen yang cocok dengan pencarian.
      </p>
      <button @click="search = ''" class="mt-4 text-sm font-medium text-brand-600 hover:underline dark:text-brand-400">
        Bersihkan Pencarian
      </button>
    </div>

    <!-- 2. Grid Cards Riwayat Panen -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3" x-show="paginatedHarvests.length > 0">

      <!-- Looping Data Gudang Panen -->
      <template x-for="item in paginatedHarvests" :key="item.id">
        <div
          class="relative flex flex-col rounded-xl border border-stroke bg-white dark:bg-brand-green-bg shadow-sm overflow-visible transition hover:shadow-md dark:border-strokedark dark:bg-boxdark">

          <!-- Card Header (batch & Tanggal) -->
          <div
            class="flex items-center justify-between rounded-t-xl border-b border-orange-100 bg-[#FFFaf0] px-5 py-3 dark:border-orange-900/30 dark:bg-orange-900/20">
            <div class="flex items-center gap-2 font-bold text-[#E65C00] dark:text-orange-200">
              <span>📦</span> <span x-text="item.batch"></span>
            </div>

            <div class="flex items-center gap-3">
              <div class="text-sm font-semibold text-gray-500 dark:text-gray-400" x-text="item.harvest_date"></div>

              <!-- Dropdown Menu -->
              {{-- <div x-data="{ open: false }" class="relative">
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
              </div> --}}
            </div>
          </div>

          <!-- Card Body -->
          <div class="p-5">
            <!-- Info Tanaman & plot -->
            <div class="mb-6 flex items-center gap-4">
              <div
                class="flex h-[72px] w-[72px] shrink-0 items-center justify-center rounded-2xl bg-[#FEF5E5] text-4xl dark:bg-orange-900/20"
                x-text="item.icon"></div>
              <div>
                <h3 class="text-xl font-bold text-[#11310E] dark:text-white" x-text="item.plant_name"></h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="'Dari: 🪧 ' + item.plot"></p>
              </div>
            </div>

            <!-- Stok Progress -->
            <div class="mb-5">
              <div class="mb-2 flex items-end justify-between">
                <span class="text-sm text-gray-500 dark:text-gray-400">Sisa Stok:</span>
                <div class="text-sm text-gray-700 dark:text-gray-300">
                  <span class="text-lg font-bold" :class="item.sisa_stok === 0 ? 'text-error-500' : 'text-[#4CA716]'"
                    x-text="item.sisa_stok"></span>
                  / <span x-text="`${item.total_panen} ${item.unit}`"></span>
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
              @click="$dispatch('open-modal-log-panen', { action: `/consumes/add/${item.id}`, subtitle: `Mencatat penggunaan <strong>${item.plant_name}</strong> di <strong>${item.batch}</strong>.`, logs: {{ Js::from($consumes) }}[item.id] ?? [] })"
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
                                : 'border-gray-200 bg-transparent text-gray-600 hover:border-brand-500 hover:text-brand-600 dark:border-gray-700 dark:text-gray-300 dark:hover:border-brand-500 dark:hover:text-brand-400'"
            x-text="page">
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

    @php
      $penggunaanFields = [
        ['name' => 'qty', 'label' => 'Jumlah Penggunaan (Kg)', 'type' => 'number', 'placeholder' => 'Isi Jumlah'],
        ['name' => 'datetime', 'label' => 'Tanggal Penggunaan', 'type' => 'datetime-local']
      ];
    @endphp

    <x-ui.modal-log icon="✂️" logTitle="RIWAYAT PANEN" id="panen" title="Penggunaan Stok" action="/harvests/add"
      :fields="$penggunaanFields" />

  </div>


  {{-- per jenis --}}
  <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10" x-data="harvestBoard({{ Js::from($harvestsStok) }})">
    {{-- per jenis (hanya tampilan keseluruhan tanpa aksi) --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Data Panen Per Jenis</h2>
      </div>
      <!-- 1.5 Baris Pencarian (Search) -->
      <div class="mb-6 flex">
        <div class="relative w-full sm:w-72 xl:w-96">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">
            <i class="fa-solid fa-search"></i>
          </span>
          <input type="text" x-model="search" placeholder="Cari tanaman, blok, atau batch..."
            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-14 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
        </div>
      </div>
    </div>

    {{-- data kosongan --}}
    <div class="flex flex-col items-center justify-center py-16 px-6 text-center" x-show="harvests.length === 0" x-cloak>
      <div class="w-20 h-20 mb-5 rounded-full bg-brand-100 dark:bg-brand-800 flex items-center justify-center">
        <svg class="w-10 h-10 text-brand-400 dark:text-brand-300" fill="none" stroke="currentColor" stroke-width="1.5"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 3C8 3 4.5 6.5 5 11c.3 2.5 2 4.5 4 5.5V19h6v-2.5c2-1 3.7-3 4-5.5.5-4.5-3-8-7-8z" />
        </svg>
      </div>
      <p class="text-lg font-semibold text-brand-800 dark:text-brand-400">Belum ada Panen</p>
      <p class="mt-1 text-sm text-brand-500">Data Panen yang ditambahkan akan muncul di sini.</p>
    </div>

    <!-- Tampilan Jika Data Tidak Ditemukan -->
    <div x-show="harvests.length > 0 && filteredHarvestsStok.length === 0" x-cloak
      class="flex flex-col items-center justify-center rounded-xl border border-stroke bg-white dark:bg-gray-800 py-16 px-4 shadow-sm dark:border-strokedark dark:bg-boxdark">
      <div
        class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800">
        <i class="fa-solid fa-magnifying-glass text-2xl"></i>
      </div>
      <h3 class="text-lg font-bold text-gray-900 dark:text-white">Data tidak ditemukan</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 text-center">
        Tidak ada data Panen yang cocok dengan pencarian.
      </p>
      <button @click="search = ''" class="mt-4 text-sm font-medium text-brand-600 hover:underline dark:text-brand-400">
        Bersihkan Pencarian
      </button>
    </div>

    <!-- 2. Grid Cards Riwayat Panen -->
    <div class=" grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3" x-show="paginatedHarvestsStok.length > 0">

      <!-- Looping Data Gudang Panen -->
      <template x-for="(item, index) in paginatedHarvestsStok" :key="index">
        <div
          class="relative flex flex-col rounded-xl border border-stroke bg-white dark:bg-brand-green-bg shadow-sm overflow-visible transition hover:shadow-md dark:border-strokedark dark:bg-boxdark">

          <!-- Card Body -->
          <div class="p-5">
            <!-- Info Tanaman & plot -->
            <div class="mb-6 flex items-center gap-4">
              <div
                class="flex h-[72px] w-[72px] shrink-0 items-center justify-center rounded-2xl bg-[#FEF5E5] text-4xl dark:bg-orange-900/20"
                x-text="item.icon"></div>
              <div>
                <h3 class="text-xl font-bold text-[#11310E] dark:text-white" x-text="item.plant_name"></h3>
              </div>
            </div>

            <!-- Stok Progress -->
            <div class="mb-5">
              <div class="mb-2 flex items-end justify-between">
                <span class="text-sm text-gray-500 dark:text-gray-400">Sisa Stok:</span>
                <div class="text-sm text-gray-700 dark:text-gray-300">
                  <span class="text-lg font-bold" :class="item.sisa_stok === 0 ? 'text-error-500' : 'text-[#4CA716]'"
                    x-text="item.sisa_stok"></span>
                  / <span x-text="`${item.total_panen} ${item.unit}`"></span>
                </div>
              </div>

              <!-- Progress Bar Dinamis -->
              <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700 relative">
                <div class="h-full rounded-full transition-all duration-500"
                  :class="item.sisa_stok === 0 ? 'bg-error-500' : 'bg-[#4CA716]'"
                  :style="`width: ${(item.sisa_stok / item.total_panen) * 100}%`"></div>
              </div>
            </div>

          </div>
        </div>
      </template>

    </div>

    <!-- 2.5 Baris Pagination -->
    <div x-show="filteredHarvestsStok.length > itemsPerPage" x-cloak
      class="mt-8 flex flex-col sm:flex-row items-center justify-between border-t border-stroke pt-5 dark:border-strokedark gap-4">
      <p class="text-sm text-gray-500 dark:text-gray-400 text-center sm:text-left">
        Menampilkan <span class="font-semibold text-gray-900 dark:text-white"
          x-text="((currentPage - 1) * itemsPerPage) + 1"></span>
        - <span class="font-semibold text-gray-900 dark:text-white"
          x-text="Math.min(currentPage * itemsPerPage, filteredHarvestsStok.length)"></span>
        dari <span class="font-semibold text-gray-900 dark:text-white" x-text="filteredHarvestsStok.length"></span> data
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
        <template x-for="page in totalPagesStok" :key="page">
          <button @click="goToPage(page)"
            class="flex h-8 w-8 items-center justify-center rounded border text-sm font-medium transition"
            :class="currentPage === page
                                ? 'border-brand-600 bg-brand-600 text-white shadow-sm'
                                : 'border-gray-200 bg-transparent text-gray-600 hover:border-brand-500 hover:text-brand-600 dark:border-gray-700 dark:text-gray-300 dark:hover:border-brand-500 dark:hover:text-brand-400'"
            x-text="page">
          </button>
        </template>

        <!-- Tombol Next -->
        <button @click="nextPage()" :disabled="currentPage === totalPagesStok" class="flex h-8 w-8 items-center justify-center rounded border transition
                                   border-gray-200 bg-transparent text-gray-400
                                   hover:border-brand-500 hover:text-brand-600
                                   disabled:border-gray-100 disabled:text-gray-300 disabled:cursor-not-allowed
                                   dark:border-gray-700 dark:text-gray-500
                                   dark:hover:border-brand-500 dark:hover:text-brand-400
                                   dark:disabled:border-gray-800 dark:disabled:text-gray-700">
          <i class="fa-solid fa-chevron-right text-xs"></i>
      </div>
    </div>
    <x-ui.confirm-dialog name="global-confirm" />

  </div>
@endsection

@push('scripts')
  <script>
    function harvestBoard(arrData) {
      return {
        search: '',
        currentPage: 1,
        itemsPerPage: 6, // Set ke 6 supaya grid 3 kolom terlihat bagus

        // harvests: {{ Js::from($formattedHarvests) }},
        harvests: arrData,

        init() {
          // Reset halaman jika sedang melakukan pencarian
          this.$watch('search', () => { this.currentPage = 1; });
        },

        // Fitur Pencarian Data
        get filteredHarvests() {
          // 1. Amankan data utama (mencegah error jika harvests belum ter-load)
          if (!this.harvests || this.harvests.length === 0) {
            return [];
          }

          // 2. Siapkan keyword pencarian dan bersihkan spasi kosong
          const keyword = (this.search || '').trim().toLowerCase();

          // Jika input pencarian kosong, langsung kembalikan semua data
          if (keyword === '') {
            return this.harvests;
          }

          // 3. Proses filter dengan pengaman (fallback string kosong untuk mencegah null)
          return this.harvests.filter(item => {
            const plantName = (item.plant_name || '').toLowerCase();
            const plotName = (item.plot || '').toLowerCase();
            const batchName = (item.batch || '').toLowerCase();

            return plantName.includes(keyword) ||
              plotName.includes(keyword) ||
              batchName.includes(keyword);
          });
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

        // Fitur Pencarian Data
        get filteredHarvestsStok() {
          // 1. Amankan data utama jika belum ter-load
          if (!this.harvests || this.harvests.length === 0) {
            return [];
          }

          // 2. Amankan input pencarian (bersihkan spasi berlebih)
          const keyword = (this.search || '').trim().toLowerCase();

          // Jika pencarian kosong, kembalikan semua data stok
          if (keyword === '') {
            return this.harvests;
          }

          // 3. Proses filter dengan fallback string kosong ('') pada plant_name
          return this.harvests.filter(item => {
            const plantName = (item.plant_name || '').toLowerCase();

            return plantName.includes(keyword);
          });
        },

        // Hitung Total Halaman
        get totalPagesStok() {
          return Math.ceil(this.filteredHarvestsStok.length / this.itemsPerPage);
        },

        // Potong Array untuk Halaman Saat Ini
        get paginatedHarvestsStok() {
          const start = (this.currentPage - 1) * this.itemsPerPage;
          const end = start + this.itemsPerPage;
          return this.filteredHarvestsStok.slice(start, end);
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