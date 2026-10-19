<?php $authUser = $_SESSION['auth_user'] ?? null; ?>
<aside class="sidebar bg-dark text-white p-3">
    <h5 class="mb-3">QuanNet</h5>

    <?php if ($authUser !== null): ?>
        <p class="small mb-4">
            Xin chào,
            <strong><?= htmlspecialchars((string) $authUser['full_name']) ?></strong><br>
            <span class="text-info"><?= htmlspecialchars((string) $authUser['role']) ?></span>
        </p>

        <nav class="nav flex-column gap-2">
            <a class="nav-link text-white p-0" href="<?= htmlspecialchars(BASE_URL . '/dashboard') ?>">Dashboard</a>
            <a class="nav-link text-white p-0" href="<?= htmlspecialchars(BASE_URL . '/computers') ?>">Máy trạm</a>
            <a class="nav-link text-white p-0" href="<?= htmlspecialchars(BASE_URL . '/customers') ?>">Khách hàng</a>
            <a class="nav-link text-white p-0" href="<?= htmlspecialchars(BASE_URL . '/sessions') ?>">Phiên chơi</a>
            <a class="nav-link text-warning p-0" href="<?= htmlspecialchars(BASE_URL . '/logout') ?>">Đăng xuất</a>
        </nav>
    <?php endif; ?>
</aside>
<main class="content p-4">
