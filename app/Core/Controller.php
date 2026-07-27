<?php

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract(array_map(fn($v) => is_string($v) ? htmlspecialchars($v, ENT_QUOTES, 'UTF-8') : $v, $data));
        $viewFile = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            http_response_code(500);
            exit('View not found: ' . htmlspecialchars($view, ENT_QUOTES, 'UTF-8'));
        }
        require $viewFile;
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function input(string $key, mixed $default = ''): mixed
    {
        return $_POST[$key] ?? $default;
    }

    protected function csrf(): void
    {
        $token   = Session::get('csrf_token');
        $posted  = $_POST['csrf_token'] ?? '';
        if (!$token || !hash_equals($token, $posted)) {
            http_response_code(403);
            exit('CSRF validation failed.');
        }
    }
}
