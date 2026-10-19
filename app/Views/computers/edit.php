<h2 class="mb-3">Cập nhật máy trạm</h2>

<form method="post" action="<?= htmlspecialchars(BASE_URL . '/computers/update') ?>" class="card p-3">
    <input type="hidden" name="id" value="<?= htmlspecialchars((string) $computer['id']) ?>">
    <?php $old = $computer; require APP_ROOT . '/app/Views/computers/form_fields.php'; ?>
    <button class="btn btn-primary" type="submit">Cập nhật</button>
</form>
