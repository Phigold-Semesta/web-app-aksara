<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('/')->withErrors(['login' => 'Anda harus login terlebih dahulu.']);
        }

        // 2. Ambil data user yang sedang login
        $user = Auth::user();

        // 3. Cek apakah role user saat ini ada di dalam parameter $roles yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 4. Jika role tidak cocok, lempar error 403 (Forbidden)
        abort(403, 'Akses Ditolak: Role Anda (' . $user->role . ') tidak memiliki akses ke halaman ini.');
    }
}