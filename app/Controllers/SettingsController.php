<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;

class SettingsController extends Controller
{
    public function index(): void
    {
        Security::requireAuth();
        $this->view('settings.index', [
            'title'     => 'Pengaturan',
            'user_name' => \App\Core\Session::get('user_name'),
        ]);
    }
}
