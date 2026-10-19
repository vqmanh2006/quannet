<?php

declare(strict_types=1);

abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        $viewFile = APP_ROOT . '/app/Views/' . $view . '.php';

        if (!is_file($viewFile)) {
            http_response_code(500);
            echo 'View not found';
            return;
        }

        $pageTitle = $data['pageTitle'] ?? APP_NAME;
        $flash = $this->getFlash();

        extract($data, EXTR_SKIP);

        require APP_ROOT . '/app/Views/layouts/header.php';
        require APP_ROOT . '/app/Views/layouts/sidebar.php';
        require APP_ROOT . '/app/Views/layouts/flash.php';
        require $viewFile;
        require APP_ROOT . '/app/Views/layouts/footer.php';
    }

    protected function renderAuth(string $view, array $data = []): void
    {
        $viewFile = APP_ROOT . '/app/Views/' . $view . '.php';

        if (!is_file($viewFile)) {
            http_response_code(500);
            echo 'View not found';
            return;
        }

        $flash = $this->getFlash();
        extract($data, EXTR_SKIP);

        require $viewFile;
    }

    protected function redirect(string $path): never
    {
        $target = BASE_URL . $path;
        header('Location: ' . ($target === '' ? '/' : $target));
        exit;
    }

    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    protected function getFlash(): ?array
    {
        if (!isset($_SESSION['flash'])) {
            return null;
        }

        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $flash;
    }

    protected function currentUser(): ?array
    {
        return $_SESSION['auth_user'] ?? null;
    }

    protected function requireAuth(): void
    {
        if ($this->currentUser() === null) {
            $this->setFlash('danger', 'Vui lòng đăng nhập để tiếp tục.');
            $this->redirect('/login');
        }
    }

    protected function requireAdmin(): void
    {
        $user = $this->currentUser();

        if ($user === null || ($user['role'] ?? '') !== 'admin') {
            $this->setFlash('danger', 'Bạn không có quyền truy cập chức năng này.');
            $this->redirect('/dashboard');
        }
    }
}
