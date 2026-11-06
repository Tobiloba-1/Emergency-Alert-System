<?php

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            // 'secure' => true, // enable once served over HTTPS
        ]);

        session_start();

        if (empty($_SESSION['last_regen']) || time() - $_SESSION['last_regen'] > 600) {
            session_regenerate_id(true);
            $_SESSION['last_regen'] = time();
        }
    }

    public static function isLoggedIn(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function role(): ?string
    {
        return $_SESSION['user_role'] ?? null;
    }

    public static function userName(): string
    {
        return $_SESSION['user_name'] ?? '';
    }

    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];
    }

    public static function requireLogin(?array $allowedRoles = null): void
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        if ($allowedRoles !== null && !in_array(self::role(), $allowedRoles, true)) {
            http_response_code(403);
            die('You do not have access to this page.');
        }
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}