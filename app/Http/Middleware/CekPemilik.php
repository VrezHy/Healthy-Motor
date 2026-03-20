<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CekPemilik{
    public function handle(Request $request, Closure $next){
        if (session('role') !== 'pemilik'){
            return redirect()->route('login');
        }
        return $next($request);
    }
}
