<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\User; // Pastikan model User dipanggil

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     * Mengarahkan user ke dashboard masing-masing jika sesi masih aktif.
     */
    public function index(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        
        return view('auth.login');
    }

    /**
     * Proses Autentikasi.
     * Menggunakan verifikasi manual agar bisa menerima password teks biasa/non-hash.
     */
    public function login(Request $request): RedirectResponse
    {
        // Validasi input
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ], [
            'username.required' => 'Username wajib diisi!',
            'password.required' => 'Password wajib diisi!',
        ]);

        // Cari user berdasarkan username
        $user = User::where('username', $request->username)->first();

        // Verifikasi manual: Cek user ada dan password cocok (tanpa hashing)
        if ($user && $user->password === $request->password) {
            // Login user secara manual
            Auth::login($user, $request->remember ?? false);
            
            // Keamanan: Regenerasi session untuk mencegah session fixation
            $request->session()->regenerate();
            
            // Redirect ke dashboard spesifik berdasarkan folder role
            return $this->redirectByRole($user->role)
                ->with('success', 'Selamat datang kembali, ' . ($user->nama_lengkap ?? $user->username) . '! Anda masuk sebagai ' . strtoupper($user->role));
        }

        // Jika gagal, kembalikan ke login dengan input username lama
        return back()->withErrors([
            'loginError' => 'Akses ditolak! Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    /**
     * Helper untuk menentukan arah redirect berdasarkan role.
     */
    private function redirectByRole(string $role): RedirectResponse
    {
        return match ($role) {
            'admin'    => redirect()->intended('admin/dashboard'),
            'petugas'  => redirect()->intended('petugas/dashboard'),
            'pimpinan' => redirect()->intended('pimpinan/dashboard'),
            default    => $this->handleInvalidRole(),
        };
    }

    /**
     * Penanganan jika role tidak terdaftar di sistem.
     */
    private function handleInvalidRole(): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('login')->withErrors([
            'loginError' => 'Gagal! Role pengguna tidak memiliki akses ke sistem.'
        ]);
    }

    /**
     * Proses Logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        // Menghapus semua data session
        $request->session()->invalidate();

        // Membuat token baru
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Sesi Anda telah berakhir. Sampai jumpa kembali!');
    }
}