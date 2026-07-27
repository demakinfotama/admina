<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;

class DashboardController extends Controller
{
    public function index(): void
    {
        Security::requireAuth();
        $this->view('dashboard.index', [
            'title'     => 'Dashboard',
            'user_name' => \App\Core\Session::get('user_name'),
        ]);
    }
}
