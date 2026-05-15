@props(['id', 'title', 'icon', 'colorTheme' => 'blue', 'logTitle', 'fields' => []])

@php
  $themes = [
    'purple' => ['text' => 'text-[#8B229A]', 'bg' => 'bg-[#8B229A]', 'hover' => 'hover:bg-[#721c7e]', 'logBg' => 'bg-gray-50', 'logIcon' => 'text-[#8B229A]'],
    'orange' => ['text' => 'text-[#E65C00]', 'bg' => 'bg-[#E65C00]', 'hover' => 'hover:bg-[#cc5200]', 'logBg' => 'bg-[#FFFaf0]', 'logIcon' => 'text-[#E65C00]'],
    'blue' => ['text' => 'text-[#0284c7]', 'bg' => 'bg-[#0284c7]', 'hover' => 'hover:bg-[#0369a1]', 'logBg' => 'bg-blue-50', 'logIcon' => 'text-[#0284c7]'],
    'slate' => ['text' => 'text-[#475569]', 'bg' => 'bg-[#475569]', 'hover' => 'hover:bg-[#334155]', 'logBg' => 'bg-slate-50', 'logIcon' => 'text-[#475569]'],
  ];
  $theme = $themes[$colorTheme] ?? $themes['blue'];
@endphp

<div x-data="{ open: false, formAction: '', subtitle: '', logs: [], formData: {}, unit: '' }" @open-modal-log-{{ $id }}.window="
        open = true;
        formAction = $event.detail.action;
        subtitle = $event.detail.subtitle;
        logs = $event.detail.logs || [];
        formData = {};
        unit = $event.detail.unit
    " x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
  x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @keydown.escape.window="open = false"
  class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true"
  @confirmed-delete-cultivate.window="if(formToSubmit) formToSubmit.submit()">
  >
  {{-- ✅ Overlay: hapus backdrop-blur, x-show cukup di wrapper --}}
  <div class="absolute inset-0 bg-black/50" @click="open = false" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"></div>

  {{-- ✅ Konten: pakai @click.stop ganti @click.outside --}}
  <div @click.stop x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    class="relative z-10 w-full max-w-md rounded-2xl bg-white dark:bg-gray-800 p-6 sm:p-8 shadow-xl dark:bg-boxdark">
    <div class="mb-3 flex items-center gap-3">
      <span class="text-2xl">{{ $icon }}</span>
      <h2 class="text-2xl font-bold {{ $theme['text'] }} dark:text-white">{{ $title }}</h2>
    </div>

    <p class="mb-5 text-sm text-gray-600 dark:text-gray-400 leading-relaxed" x-html="subtitle"></p>


    @if($fields)
      <form :action="formAction" method="POST">
        @csrf
        <div class="flex flex-wrap gap-4 mb-6">
          @foreach($fields as $field)
            <div class="{{ isset($field['half']) && $field['half'] ? 'w-full sm:w-[calc(50%-0.5rem)]' : 'w-full' }}">
              <label class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                {{ $field['label'] }}
              </label>
              <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" x-model="formData.{{ $field['name'] }}"
                placeholder="{{ $field['placeholder'] ?? '' }}"
                class="w-full rounded-lg border border-gray-200 bg-transparent dark:bg-dark-900 dark:bg-white/3 h-11 py-2.5 px-4 text-sm text-gray-800 dark:text-white/90 shadow-theme-xs outline-none focus:border-brand-300 dark:focus:border-brand-800 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-800 transition [&::-webkit-calendar-picker-indicator]:opacity-50 [&::-webkit-calendar-picker-indicator]:dark:invert [&::-webkit-calendar-picker-indicator]:cursor-pointer [&::-webkit-calendar-picker-indicator]:hover:opacity-100"
                required>
              @if(isset($field['number']) && $field['number'])
                <label class="mb-1.5 mt-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                  Batch
                </label>
                <input type="text" name="batch" value="{{ $field['batch'] }}" disabled name="batch"
                  class="w-full rounded border-gray-300 p-2 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                  required />
                <div class="flex">
                  <div class="row mr-1">
                    <label class="mb-1.5 mt-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                      Jumlah Panen
                    </label>
                    <input type="number" name="qty" x-model="formData.qty" placeholder="Isi jumlah..."
                      class="w-full rounded border-gray-300 p-2 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                      required />
                  </div>
                  <div class="row ml-1">
                    <label class="mb-1.5 mt-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                      Satuan
                    </label>
                    <input type="text" disabled :value="unit"
                      class="w-full rounded border-gray-300 p-2 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                      required />
                  </div>
                </div>
                <div x-data="{ checkboxToggle: false }" class="mt-3">
                  <label for="checkboxLabelOne"
                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                    <div class="relative">
                      <input type="checkbox" id="checkboxLabelOne" class="sr-only" name="harvestCheck"
                        @change="checkboxToggle = !checkboxToggle" />
                      <div
                        :class="checkboxToggle ? 'border-brand-500 bg-brand-500' :
                                                                              'bg-transparent border-gray-300 dark:border-gray-700'"
                        class="f hover:border-brand-500 dark:hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px]">
                        <span :class="checkboxToggle ? '' : 'opacity-0'">
                          <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="1.94437"
                              stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                        </span>
                      </div>
                    </div>
                    Telah Dipanen Sepenuhya?
                  </label>
                </div>
              @endif
            </div>
          @endforeach
        </div>

        <div class="flex justify-center gap-3">
          <button type="button" @click="open = false"
            class="min-w-[100px] rounded-lg bg-gray-200 px-5 py-2.5 font-bold text-gray-700 transition hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">
            Batal
          </button>
          <button type="submit"
            class="min-w-[140px] rounded-lg px-5 py-2.5 font-bold text-white transition shadow-sm {{ $theme['bg'] }} {{ $theme['hover'] }}">
            Simpan Data
          </button>
        </div>
      </form>
    @endif


    <hr class="my-6 border-t border-dashed border-gray-300 dark:border-strokedark">

    <div>
      <h4 class="mb-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
        {{ $logTitle }}
      </h4>

      <div class="max-h-40 overflow-y-auto pr-2 space-y-2 custom-scrollbar">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <!-- Header Tabel -->
            <thead>
              <tr class="border-b border-gray-200 dark:border-gray-700">
                @if(isset($fields['number']) && $field['number'])
                  <th class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Batch</th>
                  <th class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal</th>
                  <th class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Qty</th>
                  <th class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 text-right">Aksi</th>
                @endif
              </tr>
            </thead>

            <!-- Body Tabel -->
            <tbody>
              <template x-for="(log, index) in logs" :key="index">
                <tr
                  class="border-b border-gray-100 last:border-0 dark:border-gray-700 {{ $theme['logBg'] }} dark:bg-gray-800">

                  {{-- hanya muncul yang sudah panen habis --}}
                  @if(!$fields)
                    <td x-show="log.type === 'harvest'" class="text-gray-700 dark:text-gray-300">✂️ Panen</td>
                    <td x-show="log.type === 'water'" class="text-gray-700 dark:text-gray-300">💧 Siram</td>
                    <td x-show="log.type === 'fertilize'" class="text-gray-700 dark:text-gray-300">🌱 Pupuk</td>
                  @else
                    <!-- Kolom Batch -->
                    <td class="px-4 py-3 font-semibold dark:text-gray-300" x-text="log.batch"></td>
                  @endif

                  <!-- Kolom Text -->
                  <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300" x-text="log.text"></td>

                  @if(isset($fields['number']) && $field['number'])
                    <!-- Kolom Qty -->
                    <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300" x-text="log.qty"></td>
                  @endif

                  <!-- Kolom Button -->
                  <td class="px-4 py-3 text-right">
                    <form :action="`/${log.type}/delete/${log.id}`" method="POST"
                      @click.prevent="formToSubmit = $event.target.closest('form'); $dispatch('open-modal', 'delete-cultivate')">
                      @csrf @method('DELETE')
                      <button type="submit"
                        class="btn btn-sm bg-error-600 hover:bg-error-700 text-white p-1.5 rounded-sm transition-colors">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </form>
                  </td>

                </tr>
              </template>
            </tbody>
          </table>
        </div>
        <template x-if="logs.length === 0">
          <div class="text-center py-4 text-sm text-gray-400 italic">
            Belum ada catatan riwayat.
          </div>
        </template>
      </div>
    </div>

  </div>

</div>