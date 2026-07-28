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

// Menu
$router->add('GET',  '/users',           'UsersController',     'index');
$router->add('GET',  '/products',        'ProductsController',  'index');
$router->add('GET',  '/orders',          'OrdersController',    'index');
$router->add('GET',  '/reports',         'ReportsController',   'index');
$router->add('GET',  '/settings',        'SettingsController',  'index');

// Email Demo
$router->add('GET',  '/email-demo',      'EmailDemoController', 'index');
$router->add('POST', '/email-demo/send', 'EmailDemoController', 'send');
