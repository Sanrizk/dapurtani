@props(['id', 'title', 'icon', 'colorTheme' => 'blue', 'logTitle', 'fields' => []])

@php
  $themes = [
    'purple' => ['text' => 'text-[#8B229A]', 'bg' => 'bg-[#8B229A]', 'hover' => 'hover:bg-[#721c7e]', 'logBg' => 'bg-gray-50', 'logIcon' => 'text-[#8B229A]'],
    'orange' => ['text' => 'text-[#E65C00]', 'bg' => 'bg-[#E65C00]', 'hover' => 'hover:bg-[#cc5200]', 'logBg' => 'bg-[#FFFaf0]', 'logIcon' => 'text-[#E65C00]'],
    'blue' => ['text' => 'text-[#0284c7]', 'bg' => 'bg-[#0284c7]', 'hover' => 'hover:bg-[#0369a1]', 'logBg' => 'bg-blue-50', 'logIcon' => 'text-[#0284c7]'],
  ];
  $theme = $themes[$colorTheme] ?? $themes['blue'];
@endphp

<div x-data="{ open: false, formAction: '', subtitle: '', logs: [], formData: {} }" @open-modal-log-{{ $id }}.window="
        open = true;
        formAction = $event.detail.action;
        subtitle = $event.detail.subtitle;
        logs = $event.detail.logs || [];
        formData = {};
    " x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
  x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @keydown.escape.window="open = false"
  class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true">
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
    class="relative z-10 w-full max-w-md rounded-2xl bg-white p-6 sm:p-8 shadow-xl dark:bg-boxdark">
    <div class="mb-3 flex items-center gap-3">
      <span class="text-2xl">{{ $icon }}</span>
      <h2 class="text-2xl font-bold {{ $theme['text'] }} dark:text-white">{{ $title }}</h2>
    </div>

    <p class="mb-5 text-sm text-gray-600 dark:text-gray-400 leading-relaxed" x-html="subtitle"></p>

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
              class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-4 outline-none transition focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-strokedark dark:bg-form-input dark:text-white"
              required>
            @if(isset($field['number']) && $field['number'])
              <label class="mb-1.5 mt-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                Jumlah Panen
              </label>
              <input type="number" name="qty" x-model="formData.qty" placeholder="Isi jumlah..."
                class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-4 outline-none transition focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-strokedark dark:bg-form-input dark:text-white"
                required>
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


    <hr class="my-6 border-t border-dashed border-gray-300 dark:border-strokedark">

    <div>
      <h4 class="mb-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
        {{ $logTitle }}
      </h4>

      <div class="max-h-40 overflow-y-auto pr-2 space-y-2 custom-scrollbar">
        <template x-for="(log, index) in logs" :key="index">
          <div
            class="flex items-center justify-between gap-3 rounded-lg px-4 py-3 {{ $theme['logBg'] }} dark:bg-gray-800">
            <div>
              <span class="font-bold {{ $theme['logIcon'] }}" x-text="log.icon"></span>
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="log.text"></span>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="log.qty"></span>
            <button class="btn btn-sm bg-error-600 hover:bg-error-700 text-white p-1 rounded-sm">
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>
        </template>

        <template x-if="logs.length === 0">
          <div class="text-center py-4 text-sm text-gray-400 italic">
            Belum ada catatan riwayat.
          </div>
        </template>
      </div>
    </div>

  </div>
</div>