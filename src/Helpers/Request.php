<?php

namespace Helpers;

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
}
