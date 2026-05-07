<?php

$router->get("/auth/login", "AuthController@loginForm");
$router->post("/auth/login", "AuthController@login");
$router->get("/auth/register", "AuthController@registerForm");
$router->post("/auth/register", "AuthController@register");
$router->post("/auth/logout", "AuthController@logout");

$router->get("/", "DocumentsController@index", true);
