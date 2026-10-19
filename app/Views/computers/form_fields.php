<?php
$statuses = ['available', 'playing', 'maintenance'];
?>
<div class="mb-3">
    <label class="form-label">Mã máy</label>
    <input class="form-control <?= !empty($errors['computer_code']) ? 'is-invalid' : '' ?>" name="computer_code" value="<?= htmlspecialchars((string) ($old['computer_code'] ?? '')) ?>">
    <?php if (!empty($errors['computer_code'])): ?><div class="invalid-feedback"><?= htmlspecialchars((string) $errors['computer_code']) ?></div><?php endif; ?>
</div>
<div class="mb-3">
    <label class="form-label">Tên máy</label>
    <input class="form-control <?= !empty($errors['computer_name']) ? 'is-invalid' : '' ?>" name="computer_name" value="<?= htmlspecialchars((string) ($old['computer_name'] ?? '')) ?>">
    <?php if (!empty($errors['computer_name'])): ?><div class="invalid-feedback"><?= htmlspecialchars((string) $errors['computer_name']) ?></div><?php endif; ?>
</div>
<div class="mb-3">
    <label class="form-label">Địa chỉ IP</label>
    <input class="form-control" name="ip_address" value="<?= htmlspecialchars((string) ($old['ip_address'] ?? '')) ?>">
</div>
<div class="mb-3">
    <label class="form-label">Đơn giá/giờ</label>
    <input type="number" min="1000" step="1000" class="form-control <?= !empty($errors['hourly_rate']) ? 'is-invalid' : '' ?>" name="hourly_rate" value="<?= htmlspecialchars((string) ($old['hourly_rate'] ?? '10000')) ?>">
    <?php if (!empty($errors['hourly_rate'])): ?><div class="invalid-feedback"><?= htmlspecialchars((string) $errors['hourly_rate']) ?></div><?php endif; ?>
</div>
<div class="mb-3">
    <label class="form-label">Trạng thái</label>
    <select class="form-select <?= !empty($errors['status']) ? 'is-invalid' : '' ?>" name="status">
        <?php foreach ($statuses as $status): ?>
            <option value="<?= htmlspecialchars($status) ?>" <?= (($old['status'] ?? 'available') === $status) ? 'selected' : '' ?>><?= htmlspecialchars($status) ?></option>
        <?php endforeach; ?>
    </select>
    <?php if (!empty($errors['status'])): ?><div class="invalid-feedback"><?= htmlspecialchars((string) $errors['status']) ?></div><?php endif; ?>
</div>
