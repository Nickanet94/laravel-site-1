<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AuthController extends Controller
{
    private $usersFile;

    public function __construct()
    {
        // Указываем полный путь к файлу
        $path = base_path('users-data.json');
        $this->usersFile = "C:\Users\heroc\clinic_manager\users-data.json";   
        $this->middleware('web'); // Добавляем middleware web
    }

    public function showLogin()
    {
        return File::get(public_path('login.php'));
    }

    public function showRegister()
    {
        return File::get(public_path('register.php'));
    }

    public function showReset()
    {
        return File::get(public_path('reset.php'));
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $users = json_decode(File::get($this->usersFile), true);

        if ($username === 'admin' && $password === 'admin') {
        $_SESSION['user'] = ['username' => 'admin', 'role' => 'admin'];
        return redirect()->to(url('/admin/doctors'));
        }

        if (isset($users[$username]) && $users[$username]['password'] === $password) {
            session_start();
            $_SESSION['user'] = $username;
            return response()->redirectTo('/dashboard');
        }

        return response()->redirectTo('/login?error=invalid_credentials');
    }

    public function register(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (!preg_match('/^[a-zA-Z0-9]+$/', $username) || !preg_match('/^[a-zA-Z0-9]+$/', $password)) {
            return response()->redirectTo('/register?error=invalid_chars');
        }

        $users = json_decode(File::get($this->usersFile), true);

        if (isset($users[$username])) {
            return response()->redirectTo('/register?error=user_exists');
        }

        $users[$username] = [
            'password' => $password,
            'created_at' => date('Y-m-d H:i:s')
        ];

        File::put($this->usersFile, json_encode($users));

        session_start();
        $_SESSION['user'] = $username;
        return response()->redirectTo('/dashboard');
    }

    public function reset(Request $request)
    {
        $username = $request->input('username');
        $newPassword = $request->input('password');

        if (!preg_match('/^[a-zA-Z0-9]+$/', $username) || !preg_match('/^[a-zA-Z0-9]+$/', $newPassword)) {
            return response()->redirectTo('/reset?error=invalid_chars');
        }

        $users = json_decode(File::get($this->usersFile), true);

        if (!isset($users[$username])) {
            return response()->redirectTo('/reset?error=user_not_found');
        }

        $users[$username]['password'] = $newPassword;
        File::put($this->usersFile, json_encode($users));

        session_start();
        $_SESSION['user'] = $username;
        return response()->redirectTo('/dashboard');
    }

    public function dashboard()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            return response()->redirectTo('/login');
        }

        return File::get(public_path('dashboard.php'));
    }

    public function logout()
    {
        session_start();
        session_destroy();
        return response()->redirectTo('/login');
    }

    private function getUsers()
    {
        $path = base_path('C:\Users\heroc\clinic_manager\users-data.json');
        return json_decode(File::get($path), true) ?? [];
    }
}