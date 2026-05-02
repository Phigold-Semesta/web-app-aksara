<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
     * Dilengkapi dengan proteksi session fixation dan feedback pesan sukses.
     */
    public function login(Request $request): RedirectResponse
    {
        // Validasi input dengan pesan kustom
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ], [
            'username.required' => 'Username wajib diisi!',
            'password.required' => 'Password wajib diisi!',
        ]);

        // Percobaan Login
        if (Auth::attempt($credentials, $request->remember)) {
            // Keamanan: Regenerasi session untuk mencegah session fixation
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Redirect ke dashboard spesifik berdasarkan folder role
            return $this->redirectByRole($user->role)
                ->with('success', 'Selamat datang kembali, ' . ($user->nama ?? $user->username) . '! Anda masuk sebagai ' . strtoupper($user->role));
        }

        // Jika gagal, kembalikan ke login dengan input username lama
        return back()->withErrors([
            'loginError' => 'Akses ditolak! Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    /**
     * Helper untuk menentukan arah redirect berdasarkan role.
     * Disesuaikan dengan struktur folder: admin/dashboard, petugas/dashboard, pimpinan/dashboard.
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
     * Membersihkan semua jejak session untuk keamanan maksimal.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        // Menghapus semua data session
        $request->session()->invalidate();

        // Membuat token baru untuk mencegah CSRF attack setelah logout
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Sesi Anda telah berakhir. Sampai jumpa kembali!');
    }
}