<?php

namespace App\Core;

use DateTime;

class Request
{
    private static array $errors = [];

    public static function validatePost(array $fields): bool
    {
        return self::process($fields, INPUT_POST);
    }

    public static function validateGet(array $fields): bool
    {
        return self::process($fields, INPUT_GET);
    }

    public static function errors(string $message = 'Dados inválidos'): array
    {
        return [
            'message' => $message,
            'errors' => self::$errors
        ];
    }

    private static function process(array $fields, int $source): bool
    {
        self::$errors = [];

        foreach ($fields as $field => $message) {
            $raw = filter_input($source, $field, FILTER_UNSAFE_RAW);

            if ($raw === null || trim($raw) === '') {
                self::$errors[$field] = $message ?: "Campo {$field} é obrigatório";
            }
        }

        return empty(self::$errors);
    }

    public static function getInt(string $key): int
    {
        return (int) filter_input(INPUT_GET, $key, FILTER_VALIDATE_INT);
    }

    public static function getFloat(string $key): float
    {
        return (float) filter_input(INPUT_GET, $key, FILTER_VALIDATE_FLOAT);
    }

    public static function getString(string $key): string
    {
        $value = filter_input(INPUT_GET, $key, FILTER_UNSAFE_RAW) ?? '';

        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    public static function getEmail(string $key): string|false
    {
        return filter_input(INPUT_GET, $key, FILTER_VALIDATE_EMAIL);
    }

    public static function getUrl(string $key): string|false
    {
        return filter_input(INPUT_GET, $key, FILTER_VALIDATE_URL);
    }

    public static function getBool(string $key): bool
    {
        return filter_input(INPUT_GET, $key, FILTER_VALIDATE_BOOLEAN) ?? false;
    }

    public static function getArray(string $key): array
    {
        $value = filter_input(INPUT_GET, $key, FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);

        if (!is_array($value)) return [];

        return array_map(fn($v) => htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8'), $value);
    }

    public static function getDate(string $key): string|false
    {
        $value = filter_input(INPUT_GET, $key, FILTER_UNSAFE_RAW) ?? '';

        $date = DateTime::createFromFormat('Y-m-d', trim($value));

        return $date ? $date->format('Y-m-d') : false;
    }

    public static function getRegex(string $key, string $pattern): string
    {
        $value = filter_input(INPUT_GET, $key, FILTER_UNSAFE_RAW) ?? '';

        return preg_match($pattern, $value) ? $value : '';
    }

    public static function getNumbers(string $key): string
    {
        return self::getRegex($key, '/\D/');
    }

    public static function postInt(string $key): int
    {
        return (int) filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT);
    }

    public static function postFloat(string $key): float
    {
        return (float) filter_input(INPUT_POST, $key, FILTER_VALIDATE_FLOAT);
    }

    public static function postString(string $key): string
    {
        $value = filter_input(INPUT_POST, $key, FILTER_UNSAFE_RAW) ?? '';

        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    public static function postEmail(string $key): string|false
    {
        return filter_input(INPUT_POST, $key, FILTER_VALIDATE_EMAIL);
    }

    public static function postUrl(string $key): string|false
    {
        return filter_input(INPUT_POST, $key, FILTER_VALIDATE_URL);
    }

    public static function postBool(string $key): bool
    {
        return filter_input(INPUT_POST, $key, FILTER_VALIDATE_BOOLEAN) ?? false;
    }

    public static function postArray(string $key): array
    {
        $value = filter_input(INPUT_POST, $key, FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);

        if (!is_array($value)) return [];

        return array_map(fn($v) => htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8'), $value);
    }

    public static function postDate(string $key): string|false
    {
        $value = filter_input(INPUT_POST, $key, FILTER_UNSAFE_RAW) ?? '';

        $date = DateTime::createFromFormat('Y-m-d', trim($value));

        return $date ? $date->format('Y-m-d') : false;
    }

    public static function postRegex(string $key, string $pattern): string
    {
        $value = filter_input(INPUT_POST, $key, FILTER_UNSAFE_RAW) ?? '';

        return preg_match($pattern, $value) ? $value : '';
    }

    public static function postNumbers(string $key): string
    {
        return self::postRegex($key, '/\D/');
    }
}
