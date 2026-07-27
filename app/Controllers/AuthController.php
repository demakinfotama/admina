<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;
use App\Models\UserModel;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Security::isAuthenticated()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth.login');
    }

    public function login(): void
    {
        $this->csrf();

        $username = Security::sanitize($this->input('username'));
        $password = $this->input('password');

        if (empty($username) || empty($password)) {
            $this->redirect('/login?error=empty');
        }

        $model = new UserModel();
        $user  = $model->findByUsername($username);

        if (!$user || !Security::verifyPassword($password, $user['password'])) {
            usleep(random_int(100000, 300000));
            $this->redirect('/login?error=invalid');
        }

        Session::regenerate();
        Session::set('user_id', $user['id']);
        Session::set('user_name', $user['name']);
        Session::set('user_role', $user['role']);

        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        Session::destroy();
        $this->redirect('/login');
    }
}
