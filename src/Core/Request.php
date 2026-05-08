<?php

namespace App\Core;

use DateTime;

class Request
{
    private string $method;
    private array $data;
    private array $errors = [];

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        $this->data = match ($this->method) {
            'POST' => $_POST,
            'GET' => $_GET,
            default => $_REQUEST
        };
    }

    private function raw(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    private function sanitizeString(?string $value): ?string
    {
        return $value !== null ? trim($value) : null;
    }

    private function sanitizeArray(array $values): array
    {
        return array_map(function ($value) {
            if (is_array($value)) {
                return $this->sanitizeArray($value);
            }

            return is_string($value) ? trim($value) : $value;
        }, $values);
    }

    public function validate(array $fields): bool
    {
        $this->errors = [];

        foreach ($fields as $field => $message) {
            $value = $this->string($field);

            if ($value === '') {
                $this->errors[$field] = $message ?: "O campo {$field} é obrigatório.";
            }
        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function input(string $key): mixed
    {
        return $this->raw($key);
    }

    public function string(string $key): string
    {
        $value = $this->raw($key);

        if (!is_string($value)) return '';

        return $this->sanitizeString($value) ?? '';
    }

    public function int(string $key): ?int
    {
        $value = filter_var($this->raw($key), FILTER_VALIDATE_INT);

        return $value !== false ? (int) $value : null;
    }

    public function float(string $key): ?float
    {
        $value = filter_var($this->raw($key), FILTER_VALIDATE_FLOAT);

        return $value !== false ? (float) $value : null;
    }

    public function bool(string $key): bool
    {
        return filter_var($this->raw($key), FILTER_VALIDATE_BOOLEAN) === true;
    }

    public function email(string $key): ?string
    {
        $value = $this->string($key);

        if ($value === '') return null;

        return filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : null;
    }

    public function url(string $key): ?string
    {
        $value = $this->string($key);

        if ($value === '') return null;

        return filter_var($value, FILTER_VALIDATE_URL) ? $value : null;
    }

    public function date(string $key, string $format = 'Y-m-d'): ?string
    {
        $value = $this->string($key);

        if ($value === '') return null;

        $date = DateTime::createFromFormat($format, $value);

        return $date ? $date->format($format) : null;
    }

    public function array(string $key): array
    {
        $value = $this->raw($key);

        if (!is_array($value)) return [];

        return $this->sanitizeArray($value);
    }

    public function numbers(string $key): string
    {
        return preg_replace('/\D/', '', $this->string($key));
    }

    public function regex(string $key, string $pattern): ?string
    {
        $value = $this->string($key);

        if ($value === '') return null;

        return preg_match($pattern, $value) ? $value : null;
    }
}
