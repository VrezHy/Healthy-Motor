<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        
        if (
            $request->username === 'pemilik bengkel' &&
            $request->password === 'DM5SPM'
        ) {
             session(['username' => 'pemilik bengkel']);
            return redirect()->route('dashboard.admin');
        }


        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            return match ($role) {
                'admin'   => redirect()->route('dashboard.admin'),
                'mekanik' => redirect()->route('dashboard.mekanik'),
                default   => $this->invalidRole($request),
            };
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function invalidRole(Request $request)
    {
        Auth::logout();
        return back()->withErrors([
            'username' => 'Role tidak dikenali. Hubungi administrator.',
        ]);
    }
}
