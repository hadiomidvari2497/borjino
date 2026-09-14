<?php

declare(strict_types=1);

namespace App\Support;

use RuntimeException;

final class Csrf
{
    private const SESSION_KEY = 'security.csrf_token';

    public static function token(): string
    {
        $token = Session::get(self::SESSION_KEY);
        if (!is_string($token) || strlen($token) < 32) {
            $token = bin2hex(random_bytes(32));
            Session::put(self::SESSION_KEY, $token);
        }
        return $token;
    }

    public static function verify(mixed $token): void
    {
        if (!is_string($token) || !hash_equals(self::token(), $token)) {
            throw new RuntimeException('Invalid CSRF token.', 419);
        }
    }
}
