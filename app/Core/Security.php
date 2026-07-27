<?php

namespace App\Core;

class Security
{
    public static function generateCsrf(): string
    {
        if (!Session::get('csrf_token')) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::get('csrf_token');
    }

    public static function csrfField(): string
    {
        $token = self::generateCsrf();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function sanitize(string $input): string
    {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    public static function isAuthenticated(): bool
    {
        return !empty(Session::get('user_id'));
    }

    public static function requireAuth(): void
    {
        if (!self::isAuthenticated()) {
            header('Location: /login');
            exit;
        }
    }
}
