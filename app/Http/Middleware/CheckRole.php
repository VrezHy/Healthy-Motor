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

        if (!in_array($request->user()->role, $roles)) {

            $fallbackUrl = $request->user()->role === 'admin'
                ? route('admin.dashboard')
                : '/';
            // Alihkan kembali dengan flash session bernama 'access_blocked'
            return redirect()->to(url()->previous() !== url()->current() ? url()->previous() : $fallbackUrl)
                ->with('access_blocked', 'Hanya Pemilik dan Mekanik yang dapat Mengakses Log Riwayat');
        }
        return $next($request);
    }
}
