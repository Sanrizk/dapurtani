@props([
    'name' => null, // Digunakan untuk trigger modal via event
    'isOpen' => false,
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
    'confirmText' => 'Ya, Lanjutkan',
    'cancelText' => 'Batal',
    'confirmTheme' => 'danger', // opsi: danger, primary, warning
    'maxWidth' => 'sm' // opsi: sm, md, lg
])

@php
    $maxWidthClass = match($maxWidth) {
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        default => 'sm:max-w-md',
    };

    $confirmBtnClass = match($confirmTheme) {
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700',
        'warning' => 'bg-yellow-500 text-white hover:bg-yellow-600',
        default => 'bg-blue-600 text-white hover:bg-blue-700',
    };
@endphp

<div x-data="{
        open: @js($isOpen),
        init() {
            this.$watch('open', value => {
                if (value) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = 'unset';
                }
            });
        }
    }" 
    x-show="open" 
    x-cloak 
    @keydown.escape.window="open = false"
    {{-- Listener untuk membuka/menutup modal via $dispatch --}}
    @if($name)
        @open-modal.window="if ($event.detail === '{{ $name }}') open = true"
        @close-modal.window="if ($event.detail === '{{ $name }}') open = false"
    @endif
    class="modal fixed inset-0 z-[99999] flex items-center justify-center overflow-y-auto p-4 sm:p-5"
    {{ $attributes->except('class') }}>

    <!-- Backdrop -->
    <div @click="open = false" class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[3px] dark:bg-gray-900/60"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <!-- Modal Content -->
    <div @click.stop class="relative w-full {{ $maxWidthClass }} rounded-3xl bg-white p-6 shadow-xl dark:bg-gray-900 {{ $attributes->get('class') }}"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95">

        <!-- Body Konfirmasi -->
        <div class="mt-2">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ $title }}
            </h3>
            <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                {{ $slot->isEmpty() ? $message : $slot }}
            </div>
        </div>

        <!-- Footer / Actions -->
        <div class="mt-6 flex flex-row-reverse gap-3">
            @if(isset($actions))
                {{-- Jika custom actions diberikan --}}
                {{ $actions }}
            @else
                {{-- Default Buttons --}}
                <button type="button" @click="$dispatch('confirmed-{{ $name }}'); open = false" 
                    class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors {{ $confirmBtnClass }}">
                    {{ $confirmText }}
                </button>
                <button type="button" @click="open = false" 
                    class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 transition-colors hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-600 dark:hover:bg-gray-700">
                    {{ $cancelText }}
                </button>
            @endif
        </div>
    </div>
</div>

@once
<style>
    [x-cloak] { display: none !important; }
</style>
@endonce