<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;

class UsersController extends Controller
{
    public function index(): void
    {
        Security::requireAuth();
        $this->view('users.index', [
            'title'     => 'Pengguna',
            'user_name' => \App\Core\Session::get('user_name'),
        ]);
    }
}
