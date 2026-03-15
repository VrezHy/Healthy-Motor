<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function regis(Request $request){
        $name = $request -> name;
        $username = $request -> username;
        $password = $request -> password;

        return "User berhasil register";
    }
}
