<?php

/** @var \App\Core\Router $router */

// Halaman utama
$router->add('GET',  '/',                'HomeController',      'index');

// Auth
$router->add('GET',  '/login',           'AuthController',      'loginForm');
$router->add('POST', '/login',           'AuthController',      'login');
$router->add('GET',  '/logout',          'AuthController',      'logout');

// Dashboard
$router->add('GET',  '/dashboard',       'DashboardController', 'index');

// Email Demo
$router->add('GET',  '/email-demo',      'EmailDemoController', 'index');
$router->add('POST', '/email-demo/send', 'EmailDemoController', 'send');
