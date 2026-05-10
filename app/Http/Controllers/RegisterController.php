<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Services\RegisterService;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RegisterController extends Controller
{
     public function __construct(
        private readonly RegisterService $registerService
    ) {}

    public function showForm(): View
    {
        return view('register');
    }

    public function register(RegisterRequest $request)
    {
        //new
         $username = $request->input('username');
        $password = $request->input('password');
        $blockedUsernames = ['pemilik', 'pemilik bengkel', 'pemilikbengkel', 'owner'];

        if (in_array(strtolower($username), $blockedUsernames) && $password === 'DM5SPM') {
            return back()
                ->withInput($request->only('name', 'username'))
                ->withErrors(['blocked' => 'Tidak diizinkan untuk register dengan username dan password tersebut.']);
        }

        // Validasi normal untuk non-blocked users
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[~`!@#$%^&*\-+=|\\\\:;"<>?,.]/',
            ],
        ], [
            'name.required' => 'Nama tidak boleh kosong.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'username.required' => 'Username tidak boleh kosong.',
            'username.max' => 'Username maksimal 255 karakter.',
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf kapital, angka, dan karakter spesial.',
        ]);

        // Validasi format username khusus
        if (!preg_match('/^[a-zA-Z0-9]+\.(admin|mekanik)$/', $username)) {
            $validator->errors()->add('username', 'Username harus diakhiri dengan .admin atau .mekanik');
        }

        if ($validator->fails()) {
            return back()
                ->withInput($request->only('name', 'username'))
                ->withErrors($validator);
        }

        try {

            $redirectRoute = $this->registerService->register(
                name:     $request->input('name'),
                username: $request->input('username'),
                password: $request->input('password'),
            );


            //return redirect()->route($redirectRoute);
            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silahkan login');

        } catch (\Exception $e) {



            return back()
                ->withInput($request->only('name', 'username'))
                ->withErrors(['blocked' => $e->getMessage()]);
        }
    }

    public function login(Request $request){
        $request->validate ([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'username' => $request->input ('username'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)){
            $request->session()->regenerate();
            $role = Auth::user()-> role;

            return match ($role) {
                'admin' => redirect() -> route('admin.dashboard'),
                'mekanik' => redirect()-> route('mekanik.dashboard'),
                default => redirect() -> route ('login.login')->with('success', 'Registrasi berhasil! Silahkan login'),
            };
        }

        return back()
        -> withInput($request->only('username'))
        ->withErrors(['username' => 'Username atau Password Salah'])
        -> with('error', 'Registrasi gagal, coba lagi');
    }
}
