<?php

namespace App\Core;

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
        $url = BASE_URL . '/' . ltrim($route, '/');

        // Ajax Request
        if (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            header("X-Redirect: $url");
            exit;
        }

        header("Location: $url");
        exit;
    }

    protected function layout(string $view, array $params = []): void
    {
        $this->view('layout/header', $params);
        $this->view($view, $params);
        $this->view('layout/footer', $params);
    }
}
