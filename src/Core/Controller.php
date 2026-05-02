<?php

namespace Core;

class Controller
{
    protected function view(string $view, array $params = []): void
    {
        extract($params);

        require BASE_PATH . "/src/Views/{$view}.php";
    }

    protected function json(array $data, int $status = 200): void
    {
        header("Content-Type: application/json");
        http_response_code($status);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function redirect(string $route): void
    {
        header("Location: " . BASE_URL . "/$route");
        exit;
    }
}
