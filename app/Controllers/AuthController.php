<?php

declare(strict_types=1);

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function showLogin(): void
    {
        if ($this->currentUser() !== null) {
            $this->redirect('/dashboard');
        }

        $this->renderAuth('auth/login', [
            'pageTitle' => 'Đăng nhập',
            'errors' => [],
            'old' => [
                'username' => '',
            ],
            'flash' => $this->getFlash(),
        ]);
    }

    public function login(): void
    {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $errors = [];

        if ($username === '') {
            $errors['username'] = 'Vui lòng nhập tên đăng nhập.';
        }

        if ($password === '') {
            $errors['password'] = 'Vui lòng nhập mật khẩu.';
        }

        if ($errors !== []) {
            $this->renderAuth('auth/login', [
                'pageTitle' => 'Đăng nhập',
                'errors' => $errors,
                'old' => ['username' => $username],
                'flash' => $this->getFlash(),
            ]);
            return;
        }

        $user = $this->userModel->findByUsername($username);

        if ($user === null || !password_verify($password, (string) $user['password'])) {
            $this->renderAuth('auth/login', [
                'pageTitle' => 'Đăng nhập',
                'errors' => ['credential' => 'Tên đăng nhập hoặc mật khẩu không đúng.'],
                'old' => ['username' => $username],
                'flash' => $this->getFlash(),
            ]);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['auth_user'] = [
            'id' => (int) $user['id'],
            'username' => (string) $user['username'],
            'full_name' => (string) $user['full_name'],
            'role' => (string) $user['role'],
        ];

        $this->setFlash('success', 'Đăng nhập thành công.');
        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        unset($_SESSION['auth_user']);
        session_regenerate_id(true);

        $this->setFlash('success', 'Bạn đã đăng xuất.');
        $this->redirect('/login');
    }
}
