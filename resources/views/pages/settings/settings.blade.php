@extends('layouts.app')

@section('content')
  <div class="mx-auto max-w-270">
    <!-- Breadcrumb -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h2 class="text-title-md2 font-semibold text-black dark:text-white">
        Account Settings
      </h2>
    </div>

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">

      <!-- ========================================= -->
      <!-- Card 1: Update Personal Information       -->
      <!-- ========================================= -->
      <div
        class="rounded-sm border border-stroke bg-white dark:bg-gray-800 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="border-b border-stroke py-4 px-7 dark:border-strokedark">
          <h3 class="font-medium text-black dark:text-white">
            Informasi Personal
          </h3>
        </div>

        <!-- Alpine.js form handler -->
        <div class="p-7" x-data="{ isSubmitting: false }">
          <form action="/users/update" method="POST" @submit="isSubmitting = true">
            @csrf
            @method('PATCH')

            @if(session('successProfile'))
            <x-ui.alert variant="success" title="{{ session('successProfile') }}" message=""
              :showLink="false" />
            @endif

            <!-- Username Input -->
            <div class="mb-5.5 @if(session('successProfile')) mt-4 @endif">
              <label class="mb-3 block text-sm font-medium text-black dark:text-white" for="username">
                Username
              </label>
              <input type="text" name="username" id="username"
                value="{{ old('username', auth()->user()->username ?? '') }}"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                placeholder="Masukkan username Anda" required />
              @error('username') <span class="text-sm text-danger mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- full_name Input -->
            <div class="mb-5.5">
              <label class="mb-3 block text-sm font-medium text-black dark:text-white" for="full_name">
                Nama Lengkap
              </label>
              <input type="text" name="full_name" id="full_name"
                value="{{ old('full_name', auth()->user()->full_name ?? '') }}"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                placeholder="Masukkan full_name Anda" required />
              @error('full_name') <span class="text-sm text-danger mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Email Input -->
            <div class="mb-5.5">
              <label class="mb-3 block text-sm font-medium text-black dark:text-white" for="email">
                Alamat Email
              </label>
              <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                placeholder="Masukkan email Anda" required />
              @error('email') <span class="text-sm text-danger mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4.5">
              <button type="submit"
                class="rounded bg-brand-600 dark:bg-brand-900 px-4 py-2 font-medium text-white hover:bg-brand-700 dark:hover:bg-brand-700 transition"
                x-bind:disabled="isSubmitting">
                <span x-show="!isSubmitting">Simpan Perubahan</span>
                <span x-show="isSubmitting">Menyimpan...</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- ========================================= -->
      <!-- Card 2: Update Password                   -->
      <!-- ========================================= -->
      <div
        class="rounded-sm border border-stroke bg-white dark:bg-gray-800 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="border-b border-stroke py-4 px-7 dark:border-strokedark">
          <h3 class="font-medium text-black dark:text-white">
            Ubah Password
          </h3>
        </div>

        <div class="p-7" x-data="{ isSubmitting: false }">
          <form action="/profile/password" method="POST" x-data="{
        isSubmitting: false,
        showCurrent: false,
        showNew: false,
        showConfirm: false,
        password: '',
        passwordConfirmation: ''
      }" @submit="isSubmitting = true">

            @csrf
            @method('PUT')
            
            @if(session('successPassword'))
            <x-ui.alert variant="success" title="{{ session('successPassword') }}" message=""
              :showLink="false" />
            @endif

            <!-- Current Password -->
            <div class="mb-5.5 @if(session('successPassword')) mt-4 @endif">
              <label class="mb-3 block text-sm font-medium text-black dark:text-white" for="current_password">
                Password Saat Ini
              </label>
              <div class="relative">
                <input x-bind:type="showCurrent ? 'text' : 'password'" name="current_password" id="current_password"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                  required />
                <button type="button" @click="showCurrent = !showCurrent"
                  class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                  <!-- Mata Terbuka -->
                  <svg x-show="showCurrent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  </svg>
                  <!-- Mata Tertutup -->
                  <svg x-show="!showCurrent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                  </svg>
                </button>
              </div>
              @error('current_password') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- New Password -->
            <div class="mb-5.5">
              <label class="mb-3 block text-sm font-medium text-black dark:text-white" for="password">
                Password Baru
              </label>
              <div class="relative">
                <input x-bind:type="showNew ? 'text' : 'password'" x-model="password" name="password" id="password"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                  required />
                <button type="button" @click="showNew = !showNew"
                  class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                  <!-- Mata Terbuka -->
                  <svg x-show="showNew" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  </svg>
                  <!-- Mata Tertutup -->
                  <svg x-show="!showNew" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                  </svg>
                </button>
              </div>
              @error('password') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-5.5">
              <label class="mb-3 block text-sm font-medium text-black dark:text-white" for="password_confirmation">
                Konfirmasi Password Baru
              </label>
              <div class="relative">
                <input x-bind:type="showConfirm ? 'text' : 'password'" x-model="passwordConfirmation"
                  name="password_confirmation" id="password_confirmation"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                  required />
                <button type="button" @click="showConfirm = !showConfirm"
                  class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                  <!-- Mata Terbuka -->
                  <svg x-show="showConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  </svg>
                  <!-- Mata Tertutup -->
                  <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                  </svg>
                </button>
              </div>

              <!-- Pesan Validasi Kecocokan (Akan muncul ketika user mulai mengetik di konfirmasi) -->
              <div x-show="passwordConfirmation.length > 0" class="mt-2 text-sm" x-cloak>
                <span x-show="password === passwordConfirmation"
                  class="text-green-500 font-medium flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                  </svg>
                  Password cocok
                </span>
                <span x-show="password !== passwordConfirmation" class="text-red-500 font-medium flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                  </svg>
                  Password tidak cocok
                </span>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4.5">
              <button type="submit"
                class="rounded bg-brand-600 dark:bg-brand-900 px-4 py-2 font-medium text-white hover:bg-brand-700 dark:hover:bg-brand-700 transition disabled:opacity-70 disabled:cursor-not-allowed"
                x-bind:disabled="isSubmitting || (password !== passwordConfirmation && passwordConfirmation.length > 0)">
                <span x-show="!isSubmitting">Ubah Password</span>
                <span x-show="isSubmitting" style="display: none;">Memproses...</span>
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
@endsection