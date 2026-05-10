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

        if (is_ajax()) {
            header("X-Redirect: $url");
        } else {
            header("Location: $url");
        }

        exit;
    }

    protected function layout(string $view, array $params = [], bool $navbar = false): void
    {
        $this->view('layout/header');

        if ($navbar) $this->view('layout/navbar');

        $this->view($view, $params);
        $this->view('layout/footer');
    }
}
