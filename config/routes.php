<?php

/** @var \App\Core\Router $router */

$router->add('GET',  '/login',     'AuthController',      'loginForm');
$router->add('POST', '/login',     'AuthController',      'login');
$router->add('GET',  '/logout',    'AuthController',      'logout');
$router->add('GET',  '/dashboard', 'DashboardController', 'index');
