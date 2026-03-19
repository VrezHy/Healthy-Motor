<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Services\RegisterService;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

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
        try {
            $redirectRoute = $this->registerService->register(
                name:     $request->input('name'),
                username: $request->input('username'),
                password: $request->input('password'),
            );

            return redirect()->route($redirectRoute);

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
                default => redirect() -> route ('login.login'),
            };
        }

        return back()
        -> withInput($request->only('username'))
        ->withErrors(['username' => 'Username atau Password salah']);
    }
}
