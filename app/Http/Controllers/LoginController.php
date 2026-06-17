<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
            return redirect()->route('admin.kerusakan');
        }


        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            return match ($role) {
                'admin'   => redirect()->route('admin.kerusakan'),
                'mekanik' => redirect()->route('mekanik.diagnosa'),
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

    public function resetPassword(Request $request)
{
    $request->validate([
        'username' => 'required',
        'recovery_code' => 'required',
        'password' => 'required|confirmed|min:8',
    ]);

    $user = User::where('username', $request->username)
        ->where('recovery_code', $request->recovery_code)
        ->first();

    if (!$user) {
        return back()->withErrors([
            'recovery_code' => 'Kode pemulihan tidak valid.'
        ]);
    }

    $user->update([
        'password' => Hash::make($request->password)
    ]);

    return redirect()
        ->route('login')
        ->with('success', 'Password berhasil diubah.');
}

    public function showForgotPassword()
    {
        return view('forgot_password');
    }
}
