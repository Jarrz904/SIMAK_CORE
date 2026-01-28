<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next)
    {
        // Jika user sedang login dan akunnya TIDAK aktif
        if (auth()->check() && !auth()->user()->is_active) {
            auth()->logout(); // Paksa keluar

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Kembalikan ke halaman login dengan pesan error
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan oleh Admin.'
            ]);
        }

        return $next($request);
    }
}
