<?php

namespace App\Core;

class Auth
{
    public static function check(): bool
    {
        return Session::userId() !== null;
    }

    public static function id(): int|string|null
    {
        return Session::userId();
    }

    public static function login(int|string $user_id): void
    {
        Session::setUser($user_id);
    }

    public static function logout(): void
    {
        Session::destroy();
    }
}
