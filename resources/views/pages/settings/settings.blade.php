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
      <div class="rounded-sm border border-stroke bg-white dark:bg-gray-800 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="border-b border-stroke py-4 px-7 dark:border-strokedark">
          <h3 class="font-medium text-black dark:text-white">
            Informasi Personal
          </h3>
        </div>

        <!-- Alpine.js form handler -->
        <div class="p-7" x-data="{ isSubmitting: false }">
          <form action="" method="POST" @submit="isSubmitting = true">
            @csrf
            @method('PATCH')

            <!-- Username Input -->
            <div class="mb-5.5">
              <label class="mb-3 block text-sm font-medium text-black dark:text-white" for="username">
                Username
              </label>
              <input type="text" name="username" id="username"
                value="{{ old('username', auth()->user()->username ?? '') }}"
                class="w-full rounded border border-stroke bg-gray py-3 px-4.5 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                placeholder="Masukkan username Anda" required />
              @error('username') <span class="text-sm text-danger mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Email Input -->
            <div class="mb-5.5">
              <label class="mb-3 block text-sm font-medium text-black dark:text-white" for="email">
                Alamat Email
              </label>
              <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                class="w-full rounded border border-stroke bg-gray py-3 px-4.5 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                placeholder="Masukkan email Anda" required />
              @error('email') <span class="text-sm text-danger mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4.5">
              <button type="submit"
                class="rounded bg-brand-600 dark:bg-brand-900 px-4 py-2 font-medium text-white hover:bg-brand-700 dark:hover:bg-brand-700 transition" x-bind:disabled="isSubmitting">
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
      <div class="rounded-sm border border-stroke bg-white dark:bg-gray-800 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="border-b border-stroke py-4 px-7 dark:border-strokedark">
          <h3 class="font-medium text-black dark:text-white">
            Ubah Password
          </h3>
        </div>

        <div class="p-7" x-data="{ isSubmitting: false }">
          <form action="" method="POST" @submit="isSubmitting = true">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div class="mb-5.5">
              <label class="mb-3 block text-sm font-medium text-black dark:text-white" for="current_password">
                Password Saat Ini
              </label>
              <input type="password" name="current_password" id="current_password"
                class="w-full rounded border border-stroke bg-gray py-3 px-4.5 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                required />
              @error('current_password') <span class="text-sm text-danger mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- New Password -->
            <div class="mb-5.5">
              <label class="mb-3 block text-sm font-medium text-black dark:text-white" for="password">
                Password Baru
              </label>
              <input type="password" name="password" id="password"
                class="w-full rounded border border-stroke bg-gray py-3 px-4.5 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                required />
              @error('password') <span class="text-sm text-danger mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-5.5">
              <label class="mb-3 block text-sm font-medium text-black dark:text-white" for="password_confirmation">
                Konfirmasi Password Baru
              </label>
              <input type="password" name="password_confirmation" id="password_confirmation"
                class="w-full rounded border border-stroke bg-gray py-3 px-4.5 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                required />
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4.5">
              <button type="submit"
                class="rounded bg-brand-600 dark:bg-brand-900 px-4 py-2 font-medium text-white hover:bg-brand-700 dark:hover:bg-brand-700 transition" x-bind:disabled="isSubmitting">
                <span x-show="!isSubmitting">Ubah Password</span>
                <span x-show="isSubmitting">Memproses...</span>
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
@endsection