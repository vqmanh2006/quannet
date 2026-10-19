<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars((string) ($pageTitle ?? 'Đăng nhập')) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3">Đăng nhập hệ thống</h4>

                    <?php if (($flash ?? null) !== null): ?>
                        <div class="alert alert-<?= htmlspecialchars((string) $flash['type']) ?>">
                            <?= htmlspecialchars((string) $flash['message']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors['credential'])): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars((string) $errors['credential']) ?></div>
                    <?php endif; ?>

                    <form method="post" action="<?= htmlspecialchars(BASE_URL . '/login') ?>">
                        <div class="mb-3">
                            <label class="form-label" for="username">Tên đăng nhập</label>
                            <input
                                class="form-control <?= !empty($errors['username']) ? 'is-invalid' : '' ?>"
                                id="username"
                                name="username"
                                value="<?= htmlspecialchars((string) ($old['username'] ?? '')) ?>"
                            >
                            <?php if (!empty($errors['username'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars((string) $errors['username']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Mật khẩu</label>
                            <input
                                type="password"
                                class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>"
                                id="password"
                                name="password"
                            >
                            <?php if (!empty($errors['password'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars((string) $errors['password']) ?></div>
                            <?php endif; ?>
                        </div>

                        <button class="btn btn-primary w-100" type="submit">Đăng nhập</button>
                    </form>

                    <p class="text-muted small mt-3 mb-0">Demo: admin / admin123</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
