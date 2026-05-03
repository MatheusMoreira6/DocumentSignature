<?php

namespace Core;

use Helpers\Request;

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

    protected function redirect(string $route = ''): void
    {
        header("Location: " . BASE_URL . '/' . ltrim($route, '/'));
        exit;
    }

    protected function layout(string $view, array $params = []): void
    {
        $this->view('layout/header', $params);
        $this->view($view, $params);
        $this->view('layout/footer', $params);
    }

    protected function validateGet(array $keys): bool
    {
        return Request::validateGet($keys);
    }

    protected function validatePost(array $keys): bool
    {
        return Request::validatePost($keys);
    }

    protected function validationErrors(): array
    {
        return Request::errors();
    }
}
