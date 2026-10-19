<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Quản lý phiên chơi</h2>
    <a class="btn btn-primary" href="<?= htmlspecialchars(BASE_URL . '/sessions/open') ?>">Mở phiên</a>
</div>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>#</th>
            <th>Máy</th>
            <th>Khách hàng</th>
            <th>Bắt đầu</th>
            <th>Kết thúc</th>
            <th>Đơn giá/giờ</th>
            <th>Tổng phí</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($sessions === []): ?>
            <tr><td colspan="9" class="text-center text-muted">Chưa có dữ liệu.</td></tr>
        <?php else: ?>
            <?php foreach ($sessions as $session): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $session['id']) ?></td>
                    <td><?= htmlspecialchars((string) $session['computer_code']) ?> - <?= htmlspecialchars((string) $session['computer_name']) ?></td>
                    <td><?= htmlspecialchars((string) ($session['customer_name'] ?? 'Khách lẻ')) ?></td>
                    <td><?= htmlspecialchars((string) $session['start_time']) ?></td>
                    <td><?= htmlspecialchars((string) ($session['end_time'] ?? '-')) ?></td>
                    <td><?= htmlspecialchars(number_format((float) $session['hourly_rate'], 0, ',', '.')) ?> đ</td>
                    <td><?= htmlspecialchars(number_format((float) $session['total_fee'], 0, ',', '.')) ?> đ</td>
                    <td><span class="badge text-bg-info"><?= htmlspecialchars((string) $session['status']) ?></span></td>
                    <td>
                        <?php if ($session['status'] === 'playing'): ?>
                            <a class="btn btn-sm btn-danger" href="<?= htmlspecialchars(BASE_URL . '/sessions/close?id=' . (int) $session['id']) ?>">Đóng phiên</a>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
