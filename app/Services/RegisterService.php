<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterService
{
    public function __construct(
        private readonly UserRole $roleResolver,
        private readonly OwnerRegistration $ownerGuard,
        private readonly PasswordHasher $passwordHasher,

    ) {}

    public function register(string $name, string $username, string $password): string
    {
        if ($this->ownerGuard->isBlocked($username, $password)) {
            throw new \Exception('Tidak diizinkan untuk register dengan username dan password tersebut.');
        }

        $role = $this->roleResolver->resolve($username);
        if ($role === 'user') {
            throw new \Exception('Username harus diakhiri dengan .admin atau .mekanik.');
        }



        $cleanUsername = $this->roleResolver->stripSuffix($username);
         //dd($name, $username, $password, $role);

        $user = User::create([
            'name' => $name,
            'username' => $username,
            'password' => $this->passwordHasher->hash($password),
            'role' => $role,
        ]);

        return 'login.login';

    }
}
