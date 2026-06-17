<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegisterService
{
    public function __construct(
        private readonly UserRole $roleResolver,
        private readonly OwnerRegistration $ownerGuard,
        private readonly PasswordHasher $passwordHasher,

    ) {}


    public function register(string $name, string $username, string $password): array
    {
        if ($this->ownerGuard->isBlocked($username, $password)) {
            throw new \Exception('Tidak diizinkan untuk register dengan username dan password tersebut.');
        }

        $role = $this->roleResolver->resolve($username);
        if ($role === 'user') {
            throw new \Exception('Username harus diakhiri dengan .admin atau .mekanik.');
        }



        $cleanUsername = $this->roleResolver->stripSuffix($username);

        $recoveryCode = 'RC-' .
        strtoupper(Str::random(4)) .
        '-' .
        strtoupper(Str::random(4));

        $user = User::create([
            'name' => $name,
            'username' => $username,
            'password' => $this->passwordHasher->hash($password),
            'role' => $role,
            'recovery_code' => $recoveryCode,
        ]);

       return [
        'route' => 'login.login',
        'recovery_code' => $recoveryCode,
    ];

    }
}
