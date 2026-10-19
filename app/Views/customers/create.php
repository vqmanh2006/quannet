<h2 class="mb-3">Thêm khách hàng</h2>

<form method="post" action="<?= htmlspecialchars(BASE_URL . '/customers/store') ?>" class="card p-3">
    <?php require APP_ROOT . '/app/Views/customers/form_fields.php'; ?>
    <button class="btn btn-primary" type="submit">Lưu</button>
</form>
