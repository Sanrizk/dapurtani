@extends('layouts.app')

@section('content')
  <!-- Inisialisasi Alpine.js dengan fungsi bedenganBoard() -->
  <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10" x-data="bedenganBoard()">

    <!-- 1. Header & Search-->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Denah Bedengan</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola area tanam, kapasitas, dan status lahan.</p>
      </div>

      <div class="relative w-full sm:w-72 xl:w-96">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">
          <i class="fa-solid fa-search"></i>
        </span>
        <input type="text" x-model="search" placeholder="Cari nama atau kode blok..."
          class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-14 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
      </div>

    </div>

    <!-- 1.5. Tombol Tambah -->
    <div class="mb-6 flex">
      <button @click="$dispatch('open-modal-modal-bedengan', { mode: 'add', action: '/plots' })"
        class="rounded bg-brand-600 dark:bg-brand-900 px-4 py-2 font-medium text-white hover:bg-brand-700 dark:hover:bg-brand-700 transition shadow-sm">
        <i class="fa-solid fa-plus"></i> Tambah Bedengan
      </button>
    </div>

    {{-- data kosongan --}}
    <div class="flex flex-col items-center justify-center py-16 px-6 text-center" x-show="plots.length === 0" x-cloak>
      <div class="w-20 h-20 mb-5 rounded-full bg-brand-100 dark:bg-brand-800 flex items-center justify-center">
        <svg class="w-10 h-10 text-brand-400 dark:text-brand-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 3C8 3 4.5 6.5 5 11c.3 2.5 2 4.5 4 5.5V19h6v-2.5c2-1 3.7-3 4-5.5.5-4.5-3-8-7-8z" />
        </svg>
      </div>
      <p class="text-lg font-semibold text-brand-800 dark:text-brand-400">Belum ada bedengan</p>
      <p class="mt-1 text-sm text-brand-500">Data bedengan yang ditambahkan akan muncul di sini.</p>
    </div>

    <!-- State Jika Data Tidak Ditemukan -->
    <div x-show="plots.length > 0 && filteredPlots.length === 0" x-cloak
      class="flex flex-col items-center justify-center rounded-sm border border-stroke bg-white dark:bg-gray-800 py-16 px-4 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div
        class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800">
        <i class="fa-solid fa-magnifying-glass text-2xl"></i>
      </div>
      <h3 class="text-lg font-bold text-gray-900 dark:text-white">Data tidak ditemukan</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 text-center">
        Bedengan dengan kata kunci tersebut tidak ada.
      </p>
      <button @click="search = ''" class="mt-4 text-sm font-medium text-brand-600 hover:underline dark:text-brand-400">
        Bersihkan Pencarian
      </button>
    </div>

    <!-- 2. Grid Cards untuk Bedengan -->
    <div x-data="{ formToSubmit: null, /* variabel lain Anda... */ }"
      @confirmed-delete-plot.window="if(formToSubmit) formToSubmit.submit()"
      class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" x-show="paginatedPlots.length > 0">

      <!-- Looping Array Data Bedengan -->
      <template x-for="plot in paginatedPlots" :key="plot.id">
        <div
          class="relative flex flex-col rounded-sm border border-stroke bg-white dark:bg-brand-green-bg p-5 shadow-default transition hover:-translate-y-1 hover:shadow-lg dark:border-strokedark dark:bg-boxdark">

          <!-- Menu Dropdown -->
          <div x-data="{ open: false }" class="absolute right-3 top-3 z-10">
            <button @click="open = !open" @click.outside="open = false"
              class="text-gray-900 hover:text-gray-900 dark:hover:text-gray-500 pb-1 px-2 text-xl font-bold border rounded-full bg-white dark:bg-gray-900 dark:text-gray-200 border-gray-400">⋮</button>

            <div x-show="open" x-cloak x-transition
              class="absolute right-0 mt-1 w-32 rounded border border-stroke bg-white shadow-lg dark:border-strokedark dark:bg-boxdark z-20">
              <button @click="$dispatch('open-modal-modal-bedengan', { 
                                                mode: 'edit', 
                                                action: `/plots/edit/${plot.id}`, 
                                                data: {...plot} 
                                            }); open = false"
                class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-brand-100 hover:text-brand-700 dark:text-gray-200 dark:hover:bg-brand-700 dark:bg-brand-900">
                ✏️ Edit
              </button>
              <form :action="`/plots/delete/${plot.id}`" method="POST"
                class="m-0 border-t border-stroke dark:border-strokedark">
                @csrf @method('DELETE')

                <!-- Tombol Trigger untuk menghapus data spesifik -->
                <button type="submit" @click.prevent="$dispatch('open-modal', {
                  name: 'global-confirm',
                  title: `Hapus Bedengan ${plot.plot_name}?`,
                  message: `Apakah Anda yakin ingin menghapus bedengan ${plot.plot_name} ini? Data tidak dapat dikembalikan.`,
                  confirmText: 'Ya, Hapus',
                  confirmTheme: 'danger',
                  onConfirm: () => $event.target.closest('form').submit()
              })"
                  class="block w-full text-left px-4 py-2 text-sm text-error-500 hover:bg-error-100 dark:text-error-100 dark:hover:bg-error-500 dark:bg-error-900">
                  🗑️ Hapus
                </button>

              </form>
            </div>
          </div>

          <!-- Visual Tanah Bedengan: Sedang Ditanami -->
          <template x-if="plot.status === 'terisi'">
            <div
              class="mb-4 flex h-32 w-full items-center justify-center rounded-md border-4 border-dashed border-[#2E5E16] bg-[#1A3C0F] relative overflow-hidden">
              <div class="flex gap-3 text-3xl">
                <span>🌱</span><span>🌱</span><span>🌱</span>
              </div>
            </div>
          </template>

          <!-- Visual Tanah Bedengan: Kosong -->
          <template x-if="plot.status === 'kosong'">
            <div
              class="mb-4 flex h-32 w-full items-center justify-center rounded-md border-4 border-dashed border-[#2E5E16] bg-[#1A3C0F] relative opacity-70">
              <span class="text-sm text-white/70 font-medium">Lahan Kosong</span>
            </div>
          </template>

          <!-- Visual Tanah Bedengan: Istirahat -->
          <template x-if="plot.status === 'istirahat'">
            <div
              class="mb-4 flex h-32 w-full items-center justify-center rounded-md border-4 border-dashed border-amber-900/50 bg-amber-900/20 relative opacity-80">
              <span class="text-sm text-amber-800 dark:text-amber-500 font-medium">Pembalikan Tanah</span>
            </div>
          </template>

          <!-- Info Lahan -->
          <div class="flex-1">
            <h4 class="text-lg font-bold text-gray-900 dark:text-white" x-text="plot.plot_name"></h4>
          </div>

          <!-- Status Footer -->
          <div class="mt-4 border-t border-stroke pt-4 dark:border-strokedark">
            <!-- Badge Terisi -->
            <span x-show="plot.status === 'terisi'"
              class="inline-block rounded bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-700 border border-brand-200 dark:border-brand-800 dark:bg-brand-900/30 dark:text-brand-400">
              🟢 Sedang Ditanami
            </span>
            <!-- Badge Kosong -->
            <span x-show="plot.status === 'kosong'"
              class="inline-block rounded bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600 border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
              ⚪ Kosong (Siap Tanam)
            </span>
            <!-- Badge Istirahat -->
            <span x-show="plot.status === 'istirahat'"
              class="inline-block rounded bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 border border-amber-200 dark:border-amber-800 dark:bg-amber-900/30 dark:text-amber-500">
              🟤 Istirahat / Pembalikan
            </span>
          </div>
        </div>
      </template>

    </div>

    <!-- 2.5 Baris Pagination -->
    <div x-show="filteredPlots.length > itemsPerPage" x-cloak
      class="mt-8 flex flex-col sm:flex-row items-center justify-between border-t border-stroke pt-5 dark:border-strokedark gap-4">
      <p class="text-sm text-gray-500 dark:text-gray-400 text-center sm:text-left">
        Menampilkan <span class="font-semibold text-gray-900 dark:text-white"
          x-text="((currentPage - 1) * itemsPerPage) + 1"></span>
        - <span class="font-semibold text-gray-900 dark:text-white"
          x-text="Math.min(currentPage * itemsPerPage, filteredPlots.length)"></span>
        dari <span class="font-semibold text-gray-900 dark:text-white" x-text="filteredPlots.length"></span> data
      </p>

      <div class="flex items-center gap-1">
        <button @click="prevPage()" :disabled="currentPage === 1" class="flex h-8 w-8 items-center justify-center rounded border transition
                     border-gray-200 bg-transparent text-gray-400
                     hover:border-brand-500 hover:text-brand-600
                     disabled:border-gray-100 disabled:text-gray-300 disabled:cursor-not-allowed
                     dark:border-gray-700 dark:text-gray-500
                     dark:hover:border-brand-500 dark:hover:text-brand-400
                     dark:disabled:border-gray-800 dark:disabled:text-gray-700">
          <i class="fa-solid fa-chevron-left text-xs"></i>
        </button>

        <template x-for="page in totalPages" :key="page">
          <button @click="goToPage(page)"
            class="flex h-8 w-8 items-center justify-center rounded border text-sm font-medium transition"
            :class="currentPage === page
                  ? 'border-brand-600 bg-brand-600 text-white shadow-sm'
                  : 'border-gray-200 bg-transparent text-gray-600 hover:border-brand-500 hover:text-brand-600 dark:border-gray-700 dark:text-gray-300 dark:hover:border-brand-500 dark:hover:text-brand-400'"
            x-text="page" x-text="page">
          </button>
        </template>

        <button @click="nextPage()" :disabled="currentPage === totalPages" class="flex h-8 w-8 items-center justify-center rounded border transition
                     border-gray-200 bg-transparent text-gray-400
                     hover:border-brand-500 hover:text-brand-600
                     disabled:border-gray-100 disabled:text-gray-300 disabled:cursor-not-allowed
                     dark:border-gray-700 dark:text-gray-500
                     dark:hover:border-brand-500 dark:hover:text-brand-400
                     dark:disabled:border-gray-800 dark:disabled:text-gray-700">
          <i class="fa-solid fa-chevron-right text-xs"></i>
        </button>
      </div>
    </div>

    <!-- 3. Setup Array Data untuk Modal Form -->
    @php
      $plotFields = [
        ['name' => 'plot_name', 'label' => 'Nama/Kode Bedengan', 'type' => 'text', 'placeholder' => 'Nama ...'],
      ];
    @endphp

    <!-- Panggil Komponen Reusable Modal -->
    <x-ui.modal-form id="modal-bedengan" title="Bedengan" :fields="$plotFields" action="/plots/add" />

    <x-ui.confirm-dialog name="global-confirm" />

  </div>
@endsection

@push('scripts')
  <script>
    function bedenganBoard() {
      return {
        search: '',
        currentPage: 1,
        itemsPerPage: 4, // Setting limit grid pagination. Saya set 4 agar sejajar dengan xl:grid-cols-4

        // Data dummy array
        // plots: [
        //   { id: 1, nama: 'Blok A1', status: 'terisi' },
        //   { id: 2, nama: 'Blok A2', status: 'kosong' },
        //   { id: 3, nama: 'Blok B1', status: 'terisi' },
        //   { id: 4, nama: 'Blok B2', status: 'istirahat' },
        //   { id: 5, nama: 'Blok C1', status: 'terisi' },
        //   { id: 6, nama: 'Blok C2', status: 'kosong' },
        // ],

        plots: {{ Js::from($plots) }},

        init() {
          // Reset ke halaman pertama setiap kali input pencarian diketik
          this.$watch('search', () => { this.currentPage = 1; });
        },

        // Logic Pencarian Text
        get filteredPlots() {
          // 1. Validasi jika data utama dari awal memang kosong atau belum di-load (null/undefined)
          if (!this.plots || this.plots.length === 0) {
            return [];
          }

          // 2. Jika input pencarian kosong (hanya spasi juga dihitung kosong)
          if (this.search.trim() === '') {
            return this.plots;
          }

          // 3. Proses filter pencarian
          return this.plots.filter(plant =>
            // Opsional: Validasi plant_name tidak null agar tidak error saat di-toLowerCase()
            plant.plot_name.toLowerCase().includes(this.search.trim().toLowerCase())
          );

        },

        // Logic Pagination: Total Halaman
        get totalPages() {
          return Math.ceil(this.filteredPlots.length / this.itemsPerPage);
        },

        // Logic Pagination: Data yang dipotong untuk halaman saat ini
        get paginatedPlots() {
          const start = (this.currentPage - 1) * this.itemsPerPage;
          const end = start + this.itemsPerPage;
          return this.filteredPlots.slice(start, end);
        },

        // Navigasi
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