<?php

$router->get("/auth/login", "AuthController@loginForm");
$router->post("/auth/login", "AuthController@login");
$router->get("/auth/register", "AuthController@registerForm");
$router->post("/auth/register", "AuthController@register");
$router->post("/auth/logout", "AuthController@logout");

$router->get("/", "DocumentsController@index", true);

$router->get("/documents/datatable", "DocumentsController@datatable", true);
$router->get("/documents/show", "DocumentsController@show", true);
$router->post("/documents/store", "DocumentsController@store", true);
$router->post("/documents/destroy", "DocumentsController@destroy", true);
$router->post("/documents/check-status", "DocumentsController@checkStatus", true);

$router->get("/profile", "ProfileController@index", true);
$router->post("/profile", "ProfileController@update", true);
