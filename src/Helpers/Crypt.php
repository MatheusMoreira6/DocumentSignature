<?php

namespace App\Helpers;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Exception;

class Crypt
{
    private static ?Key $key = null;

    private static function getKey(): Key
    {
        if (!defined("ENCRYPTION_KEY")) {
            throw new Exception("Chave de criptografia não definida!");
        }

        if (self::$key === null) {
            try {
                self::$key = Key::loadFromAsciiSafeString(ENCRYPTION_KEY);
            } catch (Exception $e) {
                error_log($e->getMessage());
                throw new Exception("Erro ao carregar chave de criptografia!");
            }
        }

        return self::$key;
    }

    public static function encrypt(string $data): string
    {
        return Crypto::encrypt($data, self::getKey());
    }

    public static function decrypt(string $cipher): string
    {
        return Crypto::decrypt($cipher, self::getKey());
    }
}
