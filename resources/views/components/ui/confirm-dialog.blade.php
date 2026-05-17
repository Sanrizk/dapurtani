@props([
    'name' => 'confirm-dialog', // Nama default
    'maxWidth' => 'sm'
])

@php
    $maxWidthClass = match($maxWidth) {
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        default => 'sm:max-w-md',
    };
@endphp

<div x-data="{
        open: false,
        title: 'Konfirmasi',
        message: 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
        confirmText: 'Ya, Lanjutkan',
        cancelText: 'Batal',
        confirmTheme: 'danger',
        onConfirmCallback: null, // Menyimpan aksi yang akan dijalankan
        
        getThemeClass() {
            if (this.confirmTheme === 'danger') return 'bg-red-600 text-white hover:bg-red-700';
            if (this.confirmTheme === 'primary') return 'bg-blue-600 text-white hover:bg-blue-700';
            if (this.confirmTheme === 'warning') return 'bg-yellow-500 text-white hover:bg-yellow-600';
            return 'bg-blue-600 text-white hover:bg-blue-700';
        },

        openModal(detail) {
            // Cek apakah parameter berupa string (cara lama) atau object (cara baru)
            let targetName = typeof detail === 'string' ? detail : (detail?.name ?? null);
            
            if (targetName === '{{ $name }}') {
                if (typeof detail === 'object') {
                    // Timpa nilai default dengan nilai dari tombol yang diklik
                    this.title = detail.title ?? 'Konfirmasi';
                    this.message = detail.message ?? 'Apakah Anda yakin?';
                    this.confirmText = detail.confirmText ?? 'Ya, Lanjutkan';
                    this.cancelText = detail.cancelText ?? 'Batal';
                    this.confirmTheme = detail.confirmTheme ?? 'danger';
                    this.onConfirmCallback = detail.onConfirm ?? null;
                }
                this.open = true;
            }
        },

        confirm() {
            if (typeof this.onConfirmCallback === 'function') {
                this.onConfirmCallback(); // Eksekusi fungsi submit/action
            } else {
                $dispatch('confirmed-{{ $name }}'); // Fallback ke cara lama
            }
            this.open = false;
        },

        init() {
            this.$watch('open', value => {
                document.body.style.overflow = value ? 'hidden' : 'unset';
            });
        }
    }" 
    x-show="open" 
    x-cloak 
    @keydown.escape.window="open = false"
    @open-modal.window="openModal($event.detail)"
    @close-modal.window="if (($event.detail?.name || $event.detail) === '{{ $name }}') open = false"
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
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white" x-text="title"></h3>
            <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                <span x-html="message"></span>
            </div>
        </div>

        <!-- Footer / Actions -->
        <div class="mt-6 flex flex-row-reverse gap-3">
            @if(isset($actions))
                {{ $actions }}
            @else
                <button type="button" @click="confirm()" 
                    :class="getThemeClass()"
                    class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors">
                    <span x-text="confirmText"></span>
                </button>
                <button type="button" @click="open = false" 
                    class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 transition-colors hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-600 dark:hover:bg-gray-700">
                    <span x-text="cancelText"></span>
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