<?php

namespace Helpers;

use Exception;

class Hash
{
    public static function make(string $password): string
    {
        if (empty($password)) {
            throw new Exception('A senha não pode ser vazia.');
        }

        $hash = password_hash($password, PASSWORD_ARGON2ID, [
            "memory_cost" => 65536,
            "time_cost" => 4,
            "threads" => 2,
        ]);

        if ($hash === false) {
            throw new Exception('Falha ao gerar o hash da senha.');
        }

        return $hash;
    }

    public static function check(string $password, string $hash): bool
    {
        if (empty($password) || empty($hash)) return false;

        return password_verify($password, $hash);
    }
}
