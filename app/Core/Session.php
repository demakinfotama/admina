<?php

namespace App\Core;

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_samesite', 'Strict');
            if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'production') {
                ini_set('session.cookie_secure', 1);
            }
            session_name('ADMINA_SESS');
            session_start();
        }
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function destroy(): void
    {
        session_unset();
        session_destroy();
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }
}
