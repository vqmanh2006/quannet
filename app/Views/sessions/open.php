<h2 class="mb-3">Mở phiên chơi</h2>

<form method="post" action="<?= htmlspecialchars(BASE_URL . '/sessions/store') ?>" class="card p-3">
    <div class="mb-3">
        <label class="form-label">Máy trạm</label>
        <select class="form-select <?= !empty($errors['computer_id']) ? 'is-invalid' : '' ?>" name="computer_id">
            <option value="">-- Chọn máy --</option>
            <?php foreach ($computers as $computer): ?>
                <option value="<?= htmlspecialchars((string) $computer['id']) ?>" <?= ((string) ($old['computer_id'] ?? '') === (string) $computer['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars((string) $computer['computer_code']) ?> - <?= htmlspecialchars((string) $computer['computer_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['computer_id'])): ?><div class="invalid-feedback"><?= htmlspecialchars((string) $errors['computer_id']) ?></div><?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Khách hàng (không bắt buộc)</label>
        <select class="form-select <?= !empty($errors['customer_id']) ? 'is-invalid' : '' ?>" name="customer_id">
            <option value="">Khách lẻ</option>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= htmlspecialchars((string) $customer['id']) ?>" <?= ((string) ($old['customer_id'] ?? '') === (string) $customer['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars((string) $customer['full_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['customer_id'])): ?><div class="invalid-feedback"><?= htmlspecialchars((string) $errors['customer_id']) ?></div><?php endif; ?>
    </div>

    <button class="btn btn-primary" type="submit">Mở phiên</button>
</form>
