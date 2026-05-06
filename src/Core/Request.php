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
        return filter_var($this->data[$key] ?? null, FILTER_UNSAFE_RAW);
    }

    public function validate(array $fields): bool
    {
        $this->errors = [];

        foreach ($fields as $field => $message) {
            $raw = $this->raw($field);

            if ($raw === null || trim((string) $raw) === '') {
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
        return trim((string) ($this->raw($key) ?? ''));
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
        $value = filter_var($this->raw($key), FILTER_VALIDATE_EMAIL);

        return $value !== false ? $value : null;
    }

    public function url(string $key): ?string
    {
        $value = filter_var($this->raw($key), FILTER_VALIDATE_URL);

        return $value !== false ? $value : null;
    }

    public function date(string $key, string $format = 'Y-m-d'): ?string
    {
        $date = DateTime::createFromFormat($format, trim((string) ($this->raw($key) ?? '')));

        return $date ? $date->format($format) : null;
    }

    public function array(string $key): array
    {
        $value = $this->data[$key] ?? [];

        if (!is_array($value)) return [];

        return array_map(fn($v) => trim((string) $v), $value);
    }

    public function numbers(string $key): string
    {
        $value = (string) ($this->raw($key) ?? '');

        return preg_replace('/\D/', '', $value);
    }

    public function regex(string $key, string $pattern): ?string
    {
        $value = (string) ($this->raw($key) ?? '');

        return preg_match($pattern, $value) ? $value : null;
    }
}
