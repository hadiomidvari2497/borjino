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

    public static function validate(mixed $token): bool
    {
        $stored = Session::get(self::SESSION_KEY);

        return is_string($token)
            && is_string($stored)
            && $token !== ''
            && hash_equals($stored, $token);
    }

    public static function verify(mixed $token): void
    {
        if (!self::validate($token)) {
            throw new RuntimeException('Invalid CSRF token.', 419);
        }
    }
}
