<div class="mb-3">
    <label class="form-label">Họ tên</label>
    <input class="form-control <?= !empty($errors['full_name']) ? 'is-invalid' : '' ?>" name="full_name" value="<?= htmlspecialchars((string) ($old['full_name'] ?? '')) ?>">
    <?php if (!empty($errors['full_name'])): ?><div class="invalid-feedback"><?= htmlspecialchars((string) $errors['full_name']) ?></div><?php endif; ?>
</div>
<div class="mb-3">
    <label class="form-label">Số điện thoại</label>
    <input class="form-control" name="phone" value="<?= htmlspecialchars((string) ($old['phone'] ?? '')) ?>">
</div>
<div class="mb-3">
    <label class="form-label">Email</label>
    <input class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" name="email" value="<?= htmlspecialchars((string) ($old['email'] ?? '')) ?>">
    <?php if (!empty($errors['email'])): ?><div class="invalid-feedback"><?= htmlspecialchars((string) $errors['email']) ?></div><?php endif; ?>
</div>
<div class="mb-3">
    <label class="form-label">Ghi chú</label>
    <textarea class="form-control" name="note" rows="3"><?= htmlspecialchars((string) ($old['note'] ?? '')) ?></textarea>
</div>
