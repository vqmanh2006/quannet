<h2 class="mb-3">Thêm máy trạm</h2>

<form method="post" action="<?= htmlspecialchars(BASE_URL . '/computers/store') ?>" class="card p-3">
    <?php require APP_ROOT . '/app/Views/computers/form_fields.php'; ?>
    <button class="btn btn-primary" type="submit">Lưu</button>
</form>
