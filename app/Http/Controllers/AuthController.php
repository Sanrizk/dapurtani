<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function signin()
  {
    return view('pages.auth.signin', ['title' => 'Sign In']);
  }

  public function authenticate(Request $request)
  {
    // 1. Validasi input dari form
    $request->validate([
      'email' => ['required', 'string'],
      'password' => ['required', 'string'],
    ]);

    // 2. Cek apakah input berupa Email atau Username
    // Form Anda menggunakan name="email" untuk keduanya
    $loginType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    // 3. Siapkan kredensial untuk dicocokkan ke database
    $credentials = [
      $loginType => $request->email,
      'password' => $request->password
    ];

    // 4. Tangkap nilai dari checkbox "Keep me logged in"
    // Mengembalikan true jika dicentang, false jika tidak
    $remember = $request->boolean('remember');

    // 5. Proses Autentikasi
    // Fungsi attempt() menerima parameter kedua untuk fitur 'Remember Me'
    if (Auth::attempt($credentials, $remember)) {
      // Jika sukses, regenerasi session untuk mencegah session fixation
      $request->session()->regenerate();

      // Redirect ke halaman dashboard atau halaman yang dituju sebelum login
      return redirect()->intended('/');
    }

    // 6. Jika gagal, kembalikan user ke form login dengan pesan error
    return back()->withErrors([
      'email' => 'Email/Username atau password Salah.',
    ])->onlyInput('email');
  }

  public function register(Request $request)
  {
    $validatedData = $request->validate([
      'full_name' => ['required', 'string', 'max:255'],
      'username' => ['required', 'string', 'max:255', 'unique:users,username'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
      'password' => ['required', 'string', 'min:8'], // Bisa ditambahkan 'confirmed' jika ada input konfirmasi password
      'role' => ['required', 'in:pengurus,siswa,admin'], // Memastikan role sesuai dengan option di HTML
    ], [
      'email.unique' => 'Email ini sudah terdaftar.',
      'username.unique' => 'Username ini sudah dipakai.',
      'role.required' => 'Silakan pilih peran (role) Anda.',
    ]);

    User::create([
      'full_name' => $validatedData['full_name'],
      'username' => $validatedData['username'],
      'email' => $validatedData['email'],
      'password' => Hash::make($validatedData['password']), // WAJIB di-hash demi keamanan
      'role' => $validatedData['role'],
    ]);

    return redirect('/signin')->with('title', 'Daftar Berhasil')->with('register', 'Pendaftaran berhasil! Silahkan Login.');
  }

  /**
   * Memproses logout.
   */
  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/signin');
  }
}
