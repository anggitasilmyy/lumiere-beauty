<?php

namespace App\Http\Middleware;

// Closure digunakan untuk meneruskan request ke proses berikutnya jika user memenuhi syarat.
use Closure;

// Request digunakan untuk menangkap request yang masuk dari user.
use Illuminate\Http\Request;

// Response digunakan sebagai tipe data hasil dari middleware.
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    // Method handle akan dijalankan setiap kali user mencoba mengakses route yang memakai middleware admin.
    public function handle(Request $request, Closure $next): Response
    {
        // Mengecek apakah user sudah login atau belum.
        // Jika belum login, user diarahkan ke halaman login.
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Mengecek apakah user yang sedang login memiliki role admin.
        // Jika bukan admin, akses akan ditolak dengan error 403.
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses hanya untuk admin.');
        }

        // Jika user sudah login dan merupakan admin,
        // maka request dilanjutkan ke controller atau proses berikutnya.
        return $next($request);
    }
}