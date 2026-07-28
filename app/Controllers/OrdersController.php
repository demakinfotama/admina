<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;

class OrdersController extends Controller
{
    public function index(): void
    {
        Security::requireAuth();
        $this->view('orders.index', [
            'title'     => 'Pesanan',
            'user_name' => \App\Core\Session::get('user_name'),
        ]);
    }
}
