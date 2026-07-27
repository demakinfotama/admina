<?php

namespace App\Core;

// Start secure session
Session::start();

// Route the request
$router = new Router();
$router->dispatch();
