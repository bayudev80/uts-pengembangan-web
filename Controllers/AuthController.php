<?php
require_once __DIR__ . '/../model/UserModel.php';

class AuthController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Tampilkan halaman login admin
    public function login()
    {
        if (!empty($_SESSION['admin_user'])) {
            header('Location: index.php?action=dashboard');
            exit;
        }

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/auth/login.php';
    }

    // Proses autentikasi login (POST)
    public function processLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=login');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Username dan password wajib diisi!'
            ];
            header('Location: index.php?action=login');
            exit;
        }

        $user = $this->userModel->findByUsername($username);

        $passwordMatch = false;
        if ($user) {
            if ($user['password'] === $password || password_verify($password, $user['password'])) {
                $passwordMatch = true;
            }
        }

        if ($user && $passwordMatch) {
            $_SESSION['admin_user'] = [
                'id'       => $user['id'],
                'username' => $user['username']
            ];

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Login berhasil! Selamat datang, ' . htmlspecialchars($user['username']) . '.'
            ];

            header('Location: index.php?action=dashboard');
            exit;
        }

        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Username atau password salah! Silakan coba lagi.'
        ];
        header('Location: index.php?action=login');
        exit;
    }

    // Logout session admin
    public function logout()
    {
        unset($_SESSION['admin_user']);
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Anda telah berhasil logout dari panel admin.'
        ];
        header('Location: index.php?action=login');
        exit;
    }
}

