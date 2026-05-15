@extends('layouts.app')

@section('content')
  <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10" x-data="cultivateBoard()">

    <!-- 1. Header & Filter Search -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Monitoring Masa Tanam</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Pantau progres pertumbuhan, jadwal siram, dan estimasi panen.
        </p>
      </div>

      <!-- Filter Dropdown -->
      <div class="flex flex-col sm:flex-row">
        <div class="relative w-full sm:w-48 my-1 sm:m-1">
          <select x-model="status"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
            <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" value="all">Semua Status</option>
            <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" value="active">Belum Dipanen</option>
            <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" value="harvested">Sudah Dipanen</option>
          </select>
          <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">
            <i class="fa-solid fa-chevron-down text-xs"></i>
          </span>
        </div>

        <!-- Search Input -->
        <div class="relative w-full sm:w-72 my-1">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">
            <i class="fa-solid fa-search"></i>
          </span>
          <input type="text" x-model="search" placeholder="Cari nama tanaman, blok..."
            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-14 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
        </div>
      </div>

    </div>

    <!-- 1.5. Add Button -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center">
      <button @click="$dispatch('open-modal-modal-tanam', { mode: 'add', action: '/cultivates' })"
        class="rounded bg-brand-600 dark:bg-brand-900 px-5 py-2.5 font-medium text-white shadow-sm hover:bg-brand-700 dark:hover:bg-brand-700 transition">
        <i class="fa-solid fa-plus"></i> Mulai Tanam Baru
      </button>
    </div>

    <!-- State Jika Data Tidak Ditemukan -->
    <div x-show="filteredData.length === 0" x-cloak
      class="flex flex-col items-center justify-center rounded-sm border border-stroke bg-white dark:bg-gray-800 py-16 px-4 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div
        class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800">
        <i class="fa-solid fa-magnifying-glass text-2xl"></i>
      </div>
      <h3 class="text-lg font-bold text-gray-900 dark:text-white">Data tidak ditemukan</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 text-center">
        Tidak ada data Menanam yang cocok dengan pencarian.
      </p>
      <button @click="search = ''; status = 'all'"
        class="mt-4 text-sm font-medium text-brand-600 hover:underline dark:text-brand-400">
        Bersihkan Pencarian
      </button>
    </div>

    <!-- 2. Grid Cards Masa Tanam -->
    <!-- UBAH: Sekarang mengecek paginatedData.length -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3" x-show="paginatedData.length > 0"
      @confirmed-delete-cultivate.window="if(formToSubmit) formToSubmit.submit()">

      <!-- UBAH: Looping sekarang menggunakan array "paginatedData" alih-alih "filteredData" -->
      <template x-for="item in paginatedData" :key="item.id">
        <div class="h-full">

          <!-- KOMPONEN 1: BELUM DIPANEN (is_harvested == false) -->
          <template x-if="!item.is_harvested">
            <div
              class="relative flex h-full flex-col justify-between rounded-sm border border-stroke bg-white dark:bg-brand-green-bg p-5 shadow-default dark:border-strokedark dark:bg-boxdark">

              <!-- Dropdown Edit/Delete -->
              <div x-data="{ open: false }" class="absolute right-3 top-3 z-10">
                <button @click="open = !open" @click.outside="open = false"
                  class="flex h-8 w-8 items-center justify-center rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-800 dark:hover:text-white transition">⋮</button>
                <div x-show="open" x-cloak x-transition
                  class="absolute right-0 mt-1 w-36 rounded-md border border-stroke bg-white shadow-lg dark:border-strokedark dark:bg-boxdark z-20">
                  <button
                    @click="$dispatch('open-modal-modal-tanam', { mode: 'edit', action: `/cultivates/edit/${item.id}`, data: item, }); open = false"
                    class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-brand-100 hover:text-brand-700 dark:text-gray-200 dark:hover:bg-brand-700 dark:bg-brand-900">
                    ✏️ Edit Data
                  </button>
                  <form :action="`/cultivates/delete/${item.id}`" method="POST"
                    class="m-0 border-t border-stroke dark:border-strokedark"
                    @click.prevent="formToSubmit = $event.target.closest('form'); $dispatch('open-modal', 'delete-cultivate')"
                    @csrf @method('DELETE') <button type="submit"
                    class="block w-full text-left px-4 py-2 text-sm text-error-500 hover:bg-error-100 dark:text-error-100 dark:hover:bg-error-500 dark:bg-error-900">
                    🗑️ Hapus
                    </button>
                  </form>
                </div>
              </div>

              <div>
                <!-- Header Card: Tanaman & Lokasi -->
                <div class="flex items-center gap-4 border-b border-stroke pb-4 dark:border-strokedark">
                  <div
                    class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-brand-50 text-2xl dark:bg-brand-900/20"
                    x-text="item.icon"></div>
                  <div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white" x-text="item.name"></h4>
                    <p class="text-sm font-medium text-brand-600 dark:text-brand-400" x-text="'📍 ' + item.location"></p>
                  </div>
                </div>

                <!-- Konten: Progress & Info -->
                <div class="py-4">
                  <div class="mb-4 flex justify-between text-sm">
                    <div>
                      <p class="text-gray-500 dark:text-gray-400 text-xs mb-1">Tgl Tanam</p>
                      <p class="font-semibold text-gray-900 dark:text-white" x-text="item.date_plant"></p>
                    </div>
                    <div class="text-right">
                      <p class="text-gray-500 dark:text-gray-400 text-xs mb-1">Est. Panen</p>
                      <p class="font-semibold text-gray-900 dark:text-white" x-text="item.date_action"></p>
                    </div>
                  </div>

                  <!-- Progress Bar Umur -->
                  <div class="mb-1 flex justify-between text-xs font-medium">
                    <span class="text-brand-700 dark:text-brand-400" x-text="item.age_label"></span>
                    <span class="text-gray-500" x-text="item.remaining_label"></span>
                  </div>
                  <div class="h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                    <div class="h-2.5 rounded-full bg-brand-500 transition-all duration-500"
                      :style="`width: ${item.progress}%`"></div>
                  </div>
                </div>
              </div>

              <!-- Footer: Quick Actions -->
              <div class="flex gap-2 pt-2 mt-auto">
                <button
                  class="flex-1 flex items-center justify-center gap-2 rounded border border-blue-200 bg-blue-50 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-900/30 dark:text-blue-400"
                  @click="$dispatch('open-modal-log-siram', { action: `/waters/add/${item.id}`, subtitle: `Mencatat penyiraman <strong>${item.name}</strong> di <strong>${item.location}</strong>.`, logs: {{ Js::from($waters) }}[item.id] ?? [] })">
                  💧 Siram
                </button>
                <button
                  class="flex-1 flex items-center justify-center gap-2 rounded border border-amber-200 bg-amber-50 py-2 text-sm font-medium text-amber-600 transition hover:bg-amber-100 dark:border-amber-800 dark:bg-amber-900/30 dark:text-amber-400"
                  @click="$dispatch('open-modal-log-pupuk', { action: `/fertilizes/add/${item.id}`, subtitle: `Mencatat pemupukan <strong>${item.name}</strong> di <strong>${item.location}</strong>.`, logs: {{ Js::from($fertilizes) }}[item.id] ?? [] })">
                  🧪 Pupuk
                </button>
                <button
                  class="flex-1 flex items-center justify-center gap-2 rounded border border-purple-200 bg-purple-50 py-2 text-sm font-medium text-purple-600 transition hover:bg-purple-100 dark:border-purple-800 dark:bg-purple-900/30 dark:text-purple-400"
                  @click="$dispatch('open-modal-log-panen', { 
                    action: `/harvests/add/${item.id}`, 
                    subtitle: `Mencatat panen <strong>${item.name}</strong> di <strong>${item.location}</strong>.`, 
                    unit: item.unit, 
                    logs: {{ Js::from($harvests) }}[item.id] ?? [] })">
                  ✂️ Panen
                </button>
              </div>

            </div>
          </template>

          <!-- KOMPONEN 2: SUDAH DIPANEN (is_harvested == true) -->
          <template x-if="item.is_harvested">
            <div
              class="relative flex h-full flex-col justify-between rounded-sm border border-stroke bg-white dark:bg-brand-green-bg p-5 shadow-default dark:border-strokedark dark:bg-boxdark opacity-90 hover:opacity-100 transition-opacity">

              <!-- Dropdown Edit/Delete -->
              <div x-data="{ open: false }" class="absolute right-3 top-3 z-10">
                <button @click="open = !open" @click.outside="open = false"
                  class="flex h-8 w-8 items-center justify-center rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-800 dark:hover:text-white transition">⋮</button>
                <div x-show="open" x-cloak x-transition
                  class="absolute right-0 mt-1 w-36 rounded-md border border-stroke bg-white shadow-lg dark:border-strokedark dark:bg-boxdark z-20">
                  <form :action="`/cultivates/${item.id}`" method="POST"
                    class="m-0 border-t border-stroke dark:border-strokedark"
                    @submit.prevent="if(confirm('Batalkan dan hapus siklus tanam ini?')) $el.submit()">
                    @csrf @method('DELETE')
                    <button type="submit"
                      class="block w-full text-left px-4 py-2 text-sm text-error-500 hover:bg-error-100 dark:text-error-100 dark:hover:bg-error-500 dark:bg-error-900">
                      🗑️ Hapus
                    </button>
                  </form>
                </div>
              </div>

              <div>
                <!-- Header Card: Tanaman & Lokasi -->
                <div class="flex items-center gap-4 border-b border-stroke pb-4 dark:border-strokedark">
                  <div
                    class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-success-50 text-2xl dark:bg-success-900/20"
                    x-text="item.icon"></div>
                  <div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white" x-text="item.name"></h4>
                    <p class="text-sm font-medium text-success-600 dark:text-success-400">✅ <span
                        x-text="item.location"></span></p>
                  </div>
                </div>

                <!-- Konten: Progress & Info -->
                <div class="py-4">
                  <div class="mb-4 flex justify-between text-sm">
                    <div>
                      <p class="text-gray-500 dark:text-gray-400 text-xs mb-1">Tgl Tanam</p>
                      <p class="font-semibold text-gray-900 dark:text-white" x-text="item.date_plant"></p>
                    </div>
                    <div class="text-right">
                      <p class="text-gray-500 dark:text-gray-400 text-xs mb-1">Sudah Panen</p>
                      <p class="font-semibold text-gray-900 dark:text-white" x-text="item.date_action"></p>
                    </div>
                  </div>

                  <!-- Progress Bar Umur -->
                  <div class="mb-1 flex justify-center text-sm font-medium">
                    <span class="text-success-600 dark:text-success-400 font-bold">Telah Dipanen Sepenuhnya</span>
                  </div>
                  <div class="h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                    <div class="h-2.5 rounded-full bg-success-500 transition-all duration-500" style="width: 100%"></div>
                  </div>
                </div>
              </div>

              <!-- Footer: Quick Actions -->
              <div class="flex gap-2 pt-2 mt-auto">
                <button
                  class="flex-1 flex items-center justify-center gap-2 py-2 text-sm font-medium underline text-blue-600 dark:text-blue-400"
                  @click="$dispatch('open-modal-log-riwayat', { action: `/siram/${item.id}`, subtitle: `Melihat riwayat <strong>${item.name}</strong> di <strong>${item.location}</strong>.`, logs: {{ Js::from($mergedLogs) }}[item.id] ?? []  })">
                  Lihat Data Riwayat
                </button>
              </div>

            </div>
          </template>
        </div>
      </template>

    </div>

    <!-- 2.5 BARIS PAGINATION -->
    <div x-show="filteredData.length > itemsPerPage" x-cloak
      class="mt-8 flex flex-col sm:flex-row items-center justify-between border-t border-stroke pt-5 dark:border-strokedark gap-4">
      <p class="text-sm text-gray-500 dark:text-gray-400 text-center sm:text-left">
        Menampilkan <span class="font-semibold text-gray-900 dark:text-white"
          x-text="((currentPage - 1) * itemsPerPage) + 1"></span>
        - <span class="font-semibold text-gray-900 dark:text-white"
          x-text="Math.min(currentPage * itemsPerPage, filteredData.length)"></span>
        dari <span class="font-semibold text-gray-900 dark:text-white" x-text="filteredData.length"></span> data
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

    <!-- 3. Modal Definitions (Biarkan bawaan Anda) -->
    <!-- ... -->
    <!-- 3. Definisi Form Modal Tanam Baru -->
    @php
      $tanamFields = [
        [
          'name' => 'plant_id',
          'label' => 'Pilih Tanaman',
          'type' => 'select',
          'options' => $plants->pluck('plant_name', 'id')
        ],
        [
          'name' => 'plot_id',
          'label' => 'Pilih Lokasi Bedengan',
          'type' => 'select',
          'options' => $plots->pluck('plot_name', 'id')
        ],
        ['name' => 'datetime', 'label' => 'Tanggal Tanam', 'type' => 'datetime-local', 'placeholder' => 'Isi Jumlah bibit'],
        // ['name' => 'jumlah_bibit', 'label' => 'Jumlah Bibit/Lubang', 'type' => 'number', 'placeholder' => 'Isi Jumlah bibit']
      ];

      $siramFields = [
        [
          'name' => 'datetime',
          'label' => 'Waktu Disiram',
          'type' => 'datetime-local', // Otomatis memunculkan ikon kalender/jam bawaan browser
          'half' => false, // Set true jika ingin sejajar sebelah-sebelahan
          'number' => false, // set true hanya untuk panen
          'water' => true
        ]
      ];

      $pupukFields = [
        [
          'name' => 'datetime',
          'label' => 'Waktu Dipupuk',
          'type' => 'datetime-local', // Otomatis memunculkan ikon kalender/jam bawaan browser
          'half' => false, // Set true jika ingin sejajar sebelah-sebelahan
          'number' => false, // set true hanya untuk panen
          'fertilize' => true 
        ]
      ];

      $panenFields = [
        [
          'name' => 'datetime',
          'batch' => $newBatch,
          'label' => 'Waktu Dipanen',
          'type' => 'datetime-local', // Otomatis memunculkan ikon kalender/jam bawaan browser
          'half' => false, // Set true jika ingin sejajar sebelah-sebelahan
          'number' => true // set true hanya untuk panen
        ]
      ];
    @endphp

    <x-ui.modal-form id="modal-tanam" title="Data Masa Tanam" action="/cultivates/add" :fields="$tanamFields" />

    <x-ui.modal-log id="siram" title="Catat Penyiraman" icon="💧" colorTheme="blue" logTitle="RIWAYAT PENYIRAMAN"
      :fields="$siramFields" />

    <x-ui.modal-log id="pupuk" title="Catat Pemupukan" icon="🧪" colorTheme="orange" logTitle="RIWAYAT PEMUPUKAN"
      :fields="$pupukFields" />

    <x-ui.modal-log id="panen" title="Catat Pemanenan" icon="✂️" colorTheme="purple" logTitle="RIWAYAT PANEN"
      :fields="$panenFields" />

    <x-ui.modal-log id="riwayat" title="Daftar Riwayat" icon="✂️" colorTheme="slate" logTitle="DAFTAR RIWAYAT"
      :fields="false" />

    <x-ui.confirm-dialog name="delete-cultivate" title="Hapus Data Menanam"
      message="Apakah Anda yakin ingin menghapus data menanam ini? Data yang dihapus tidak dapat dikembalikan."
      confirmText="Hapus" confirmTheme="danger" />

  </div>
@endsection

@push('scripts')
  <script>
    function cultivateBoard() {
      return {
        search: '',
        status: 'all',
        currentPage: 1,      // <-- Halaman aktif
        itemsPerPage: 6,     // <-- Batas card per halaman (contoh: 3)

        // dataCultivates: [
        //   { id: 1, name: 'Tomat Merah', icon: '🍅', location: 'Bedengan Blok A1', date_plant: '01 Mei 2026', date_action: '31 Mei 2026', age_label: 'Hari ke-7', remaining_label: '23 Hari Lagi', progress: 25, is_harvested: false },
        //   { id: 2, name: 'Sawi Hijau', icon: '🥬', location: 'Bedengan Blok B2', date_plant: '01 April 2026', date_action: '30 April 2026', age_label: '-', remaining_label: '-', progress: 100, is_harvested: true },
        //   { id: 3, name: 'Kangkung Cabut', icon: '🌱', location: 'Bedengan Blok C1', date_plant: '05 Mei 2026', date_action: '26 Mei 2026', age_label: 'Hari ke-4', remaining_label: '17 Hari Lagi', progress: 18, is_harvested: false },
        //   { id: 4, name: 'Cabai Rawit', icon: '🌶️', location: 'Bedengan Blok A2', date_plant: '20 April 2026', date_action: '20 Juli 2026', age_label: 'Hari ke-18', remaining_label: '72 Hari Lagi', progress: 20, is_harvested: false },
        //   { id: 5, name: 'Bawang Merah', icon: '🧅', location: 'Bedengan Blok D1', date_plant: '10 Maret 2026', date_action: '10 Mei 2026', age_label: '-', remaining_label: '-', progress: 100, is_harvested: true },
        //   { id: 6, name: 'Wortel Manis', icon: '🥕', location: 'Bedengan Blok C2', date_plant: '08 Mei 2026', date_action: '08 Agustus 2026', age_label: 'Hari ke-1', remaining_label: '90 Hari Lagi', progress: 1, is_harvested: false },
        //   { id: 7, name: 'Selada Air', icon: '🥗', location: 'Bedengan Blok B1', date_plant: '02 Mei 2026', date_action: '22 Mei 2026', age_label: 'Hari ke-6', remaining_label: '14 Hari Lagi', progress: 30, is_harvested: false },
        // ],

        dataCultivates: {{ Js::from($formattedCultivates) }},

        init() {
          // Jika pencarian atau filter status diubah, paksa kembali ke halaman 1
          this.$watch('search', () => { this.currentPage = 1; });
          this.$watch('status', () => { this.currentPage = 1; });
        },

        // Filter Data Keseluruhan (Pencarian & Status)
        get filteredData() {
          return this.dataCultivates.filter((item) => {
            const searchTerm = this.search.toLowerCase();
            const isMatchedSearch = item.name.toLowerCase().includes(searchTerm) ||
              item.location.toLowerCase().includes(searchTerm);

            let isMatchedStatus = true;
            if (this.status === 'active') {
              isMatchedStatus = item.is_harvested === false;
            } else if (this.status === 'harvested') {
              isMatchedStatus = item.is_harvested === true;
            }

            return isMatchedSearch && isMatchedStatus;
          });
        },

        // Kalkulasi Total Halaman (Berdasarkan sisa hasil filter)
        get totalPages() {
          return Math.ceil(this.filteredData.length / this.itemsPerPage);
        },

        // Potong (Slice) Array khusus untuk Tampil di Halaman Ini
        get paginatedData() {
          const start = (this.currentPage - 1) * this.itemsPerPage;
          const end = start + this.itemsPerPage;
          return this.filteredData.slice(start, end);
        },

        // Fungsi Navigasi Tombol Pagination
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