<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\User; // Pastikan model User dipanggil
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash; // Ditambahkan untuk mendukung verifikasi password ter-hash

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
     * Disempurnakan agar mendukung input Username ATAU Email serta verifikasi Ganda (Hash & Teks Biasa).
     */
    public function login(Request $request): RedirectResponse
    {
        // Validasi input
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ], [
            'username.required' => 'Username atau Email wajib diisi!',
            'password.required' => 'Password wajib diisi!',
        ]);

        $loginInput = $request->username;

        // Cari user berdasarkan username ATAU email agar fleksibel
        $user = User::where('username', $loginInput)
                    ->orWhere('email', $loginInput)
                    ->first();

        // Verifikasi cerdas: Mendukung password Hash (bcrypt) DAN password Teks Biasa sekaligus
        $isPasswordValid = false;
        if ($user) {
            if (Hash::check($request->password, $user->password) || $user->password === $request->password) {
                $isPasswordValid = true;
            }
        }

        // Jika user ditemukan dan password valid
        if ($user && $isPasswordValid) {
            // Login user secara manual
            Auth::login($user, $request->remember ?? false);
            
            // Keamanan: Regenerasi session untuk mencegah session fixation
            $request->session()->regenerate();
            
            // Redirect ke dashboard spesifik berdasarkan folder role
            return $this->redirectByRole($user->role)
                ->with('success', 'Selamat datang kembali, ' . ($user->nama_lengkap ?? $user->username) . '! Anda masuk sebagai ' . strtoupper($user->role));
        }

        // Jika gagal, kembalikan ke login dengan input username/email lama
        return back()->withErrors([
            'loginError' => 'Akses ditolak! Username/Email atau password salah.',
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
            'default'  => $this->handleInvalidRole(),
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

    /**
     * Tampilkan form input lupa password.
     */
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses kirim tautan reset password menggunakan identifier (username atau email).
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'identifier' => ['required', 'string']
        ], [
            'identifier.required' => 'Mohon masukkan Username atau Email Anda!'
        ]);

        $identifier = $request->identifier;

        // Cari user berdasarkan username ATAU email di tabel user
        $user = User::where('username', $identifier)
                    ->orWhere('email', $identifier)
                    ->first();

        if (!$user) {
            return back()->withErrors(['identifier' => 'Username atau Email tidak ditemukan di sistem AKSARA.']);
        }

        if (empty($user->email)) {
            return back()->withErrors(['identifier' => 'Akun Anda tidak memiliki alamat email terdaftar. Hubungi Administrator.']);
        }

        // Buat token acak 64 karakter
        $token = Str::random(64);

        // Simpan token ke tabel password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        // Buat link reset
        $resetLink = route('password.reset', ['token' => $token, 'email' => $user->email]);

        // Kirim email
        Mail::send('auth.emails.reset-password', ['resetLink' => $resetLink], function($message) use ($user) {
            $message->to($user->email);
            $message->subject('Permintaan Reset Password - AKSARA LPSE Karawang');
        });

        return back()->with('success', 'Tautan pemulihan password telah dikirim ke email: ' . $user->email);
    }

    /**
     * Tampilkan form buat password baru.
     */
    public function showResetForm(Request $request, $token): View
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * Proses simpan password baru ke database.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:user,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'token' => ['required']
        ]);

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'Token reset password tidak valid atau sudah kedaluwarsa.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = $request->password; // Sesuai sistem password teks biasa Anda
        $user->save();

        // Hapus token setelah digunakan
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil diperbarui! Silakan masuk dengan password baru Anda.');
    }
}