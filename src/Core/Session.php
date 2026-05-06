<?php

namespace App\Core;

use Exception;

class Session
{
    private const USER_KEY = '_auth_user';

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'domain'   => '',
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ]);

        if (!session_start()) {
            throw new Exception('Falha ao iniciar a sessão.');
        }
    }

    private static function regenerate(): void
    {
        session_regenerate_id(delete_old_session: true);
    }

    public static function destroy(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        $_SESSION = [];
        session_destroy();

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
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

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function setUser(int|string $user_id): void
    {
        self::regenerate();

        $_SESSION[self::USER_KEY] = $user_id;
    }

    public static function userId(): int|string|null
    {
        return $_SESSION[self::USER_KEY] ?? null;
    }

    public static function forgetUser(): void
    {
        self::remove(self::USER_KEY);
    }
}
