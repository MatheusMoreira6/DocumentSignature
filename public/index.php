<?php

require __DIR__ . '/../bootstrap/app.php';

$router = new Core\Router();

require __DIR__ . '/../route/web.php';

$router->dispatch();
