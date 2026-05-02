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

function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

function url(string $route = ''): string
{
    return BASE_URL . '/' . ltrim($route, '/');
}
