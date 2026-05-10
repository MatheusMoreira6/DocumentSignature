<?php

function dd(mixed ...$vars): void
{
    echo '<pre>';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    die();
}

function base_url(): string
{
    $protocol = "http";

    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        $protocol = 'https';
    }

    return $protocol . '://' . $_SERVER['HTTP_HOST'];
}

function current_route(string $route = '/'): bool
{
    $current = '/' . trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $route = '/' . trim($route, '/');

    return $current === $route;
}

function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

function url(string $route = ''): string
{
    return BASE_URL . '/' . ltrim($route, '/');
}

function is_ajax(): bool
{
    return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
}
