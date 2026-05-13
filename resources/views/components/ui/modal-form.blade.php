@props(['id', 'title', 'action', 'fields' => []])

<div x-data="{ open: false, mode: 'add', formAction: '', formData: {} }" @open-modal-{{ $id }}.window="
        open = true;
        mode = $event.detail.mode;
        formAction = $event.detail.action;
        formData = $event.detail.data || {};
    " x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
  x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @keydown.escape.window="open = false"
  class="fixed inset-0 z-[99999] flex items-center justify-center">
  {{-- Overlay background — pisahkan dari konten --}}
  <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

  {{-- Konten Modal — pakai relative + z-index agar di atas overlay --}}
  <div class="relative z-10 w-full max-w-lg rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" @click.stop {{-- ❌
    Hapus @click.outside, ganti stop propagation --}}>
    <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white"
      x-text="mode === 'add' ? 'Tambah {{ $title }}' : 'Edit {{ $title }}'">
    </h2>

    <form :action="mode === 'edit' ? formAction : '{{ $action }}'" method="POST">
      @csrf
      <template x-if="mode === 'edit'">
        <input type="hidden" name="_method" value="PUT">
      </template>

      <div class="space-y-4">
        @foreach($fields as $field)
          <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
              {{ $field['label'] }}
            </label>

            @if($field['type'] === 'text' || $field['type'] === 'number' || $field['type'] === 'datetime-local')
              <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" x-model="formData.{{ $field['name'] }}"
                class="w-full rounded border-gray-300 p-2 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                required placeholder="{{ $field['placeholder'] }}" step="1">

            @elseif($field['type'] === 'select')
              <select name="{{ $field['name'] }}" x-model="formData.{{ $field['name'] }}"
                class="w-full rounded border-gray-300 p-2 focus:border-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                required>
                <option value="" disabled>Pilih...</option>
                @foreach($field['options'] as $val => $label)
                  <option value="{{ $val }}">{{ $label }}</option>
                @endforeach
              </select>

            @elseif($field['type'] === 'radio')
              <div class="flex gap-4 mt-2">
                @foreach($field['options'] as $val => $label)
                  <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300 cursor-pointer">
                    <input type="radio" name="{{ $field['name'] }}" value="{{ $val }}" x-model="formData.{{ $field['name'] }}"
                      class="text-brand-600 focus:ring-brand-500">
                    {{ $label }}
                  </label>
                @endforeach
              </div>
            @endif
          </div>
        @endforeach
      </div>

      <div class="mt-6 flex justify-end gap-3">
        <button type="button" @click="open = false"
          class="rounded bg-gray-200 px-4 py-2 font-medium text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
          Batal
        </button>
        <button type="submit" class="rounded bg-brand-600 px-4 py-2 font-medium text-white hover:bg-brand-700">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>