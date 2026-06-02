<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan pengguna sudah login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Periksa apakah role pengguna ada di dalam daftar role yang diperbolehkan
        // (Asumsi Anda memiliki kolom 'role' di tabel users)
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}