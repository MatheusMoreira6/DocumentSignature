<?php

namespace Helpers;

class Request
{
    private static array $errors = [];

    public static function validatePost(array $keys): bool
    {
        return self::process($keys, INPUT_POST);
    }

    public static function validateGet(array $keys): bool
    {
        return self::process($keys, INPUT_GET);
    }

    public static function errors(): array
    {
        return self::$errors;
    }

    private static function process(array $keys, int $source): bool
    {
        self::$errors = [];

        foreach ($keys as $key) {
            $raw = filter_input($source, $key, FILTER_UNSAFE_RAW);

            if ($raw === null || trim($raw) === '') {
                self::$errors[] = $key;
            }
        }

        return empty(self::$errors);
    }
}
