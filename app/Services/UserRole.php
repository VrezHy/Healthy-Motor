<?php

namespace App\Services;

class UserRole{
    private array $suffixRoleMap = [
        '.admin' => 'admin',
        '.mekanik' => 'mekanik',
    ];

    public function resolve(string $username): string {
        foreach ($this->suffixRoleMap as $suffix => $role){
            if (str_ends_with($username, $suffix)){
                return $role;
            }
        }
        return 'user';
    }

    public function stripSuffix(string $username): string{
        foreach (array_keys($this->suffixRoleMap) as $suffix){
            if(str_ends_with($username, $suffix)){
                return substr($username, 0 , -strlen($suffix));
            }
        }
        return $username;
    }
}
