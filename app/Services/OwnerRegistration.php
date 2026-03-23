<?php

namespace App\Services;

class OwnerRegistration{
    private const BLOCKED_PASSWORD = 'DM5SPM';

    private array $blockedUsername = [
        'pemilik',
        'pemilik bengkel',
        'pemilikbengkel',
        'owner',
    ];

    public function isBlocked(string $username, string $password): bool{
        $isBlockedUsername = in_array(strtolower($username), $this->blockedUsername);
        $isBlockedPassword = $password === self::BLOCKED_PASSWORD;

        return $isBlockedUsername && $isBlockedPassword;
    }
}
