<h2 class="mb-3">Cập nhật khách hàng</h2>

<form method="post" action="<?= htmlspecialchars(BASE_URL . '/customers/update') ?>" class="card p-3">
    <input type="hidden" name="id" value="<?= htmlspecialchars((string) $customer['id']) ?>">
    <?php $old = $customer; require APP_ROOT . '/app/Views/customers/form_fields.php'; ?>
    <button class="btn btn-primary" type="submit">Cập nhật</button>
</form>
