<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;

class ProductsController extends Controller
{
    public function index(): void
    {
        Security::requireAuth();
        $this->view('products.index', [
            'title'     => 'Produk',
            'user_name' => \App\Core\Session::get('user_name'),
        ]);
    }
}
