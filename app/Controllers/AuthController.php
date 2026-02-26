<?php
namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session('user_id')) {
            return redirect()->to(site_url('/'));
        }

        return view('auth/login');
    }

    public function attempt()
    {
        $email = trim((string) $this->request->getPost('email'));
        $pass  = (string) $this->request->getPost('password');

        $users = new UserModel();
        $u     = $users->findByEmail($email);

        if (! $u || ! (int) $u['is_active'] || ! password_verify($pass, $u['password'])) {
            return redirect()->back()->with('error', 'Login inválido.');
        }

        session()->regenerate(true);
        session()->set([
            'user_id' => (int) $u['id'],
            'name'    => $u['name'],
            'email'   => $u['email'],
        ]);

        return redirect()->to(site_url('/'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }
}
