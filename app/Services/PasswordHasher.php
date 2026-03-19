<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordHasher{
    public function hash(string $plainPassword): string{
        return Hash::make($plainPassword);
    }
}
