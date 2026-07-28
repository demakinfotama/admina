<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;

class ReportsController extends Controller
{
    public function index(): void
    {
        Security::requireAuth();
        $this->view('reports.index', [
            'title'     => 'Laporan',
            'user_name' => \App\Core\Session::get('user_name'),
        ]);
    }
}
