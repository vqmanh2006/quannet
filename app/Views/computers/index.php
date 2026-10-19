<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Danh sách máy trạm</h2>
    <a class="btn btn-primary" href="<?= htmlspecialchars(BASE_URL . '/computers/create') ?>">Thêm máy</a>
</div>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>#</th>
            <th>Mã máy</th>
            <th>Tên máy</th>
            <th>IP</th>
            <th>Đơn giá/giờ</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($computers === []): ?>
            <tr><td colspan="7" class="text-center text-muted">Chưa có dữ liệu.</td></tr>
        <?php else: ?>
            <?php foreach ($computers as $computer): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $computer['id']) ?></td>
                    <td><?= htmlspecialchars((string) $computer['computer_code']) ?></td>
                    <td><?= htmlspecialchars((string) $computer['computer_name']) ?></td>
                    <td><?= htmlspecialchars((string) $computer['ip_address']) ?></td>
                    <td><?= htmlspecialchars(number_format((float) $computer['hourly_rate'], 0, ',', '.')) ?> đ</td>
                    <td><span class="badge text-bg-secondary"><?= htmlspecialchars((string) $computer['status']) ?></span></td>
                    <td>
                        <a class="btn btn-sm btn-warning" href="<?= htmlspecialchars(BASE_URL . '/computers/edit?id=' . (int) $computer['id']) ?>">Sửa</a>
                        <form class="d-inline" method="post" action="<?= htmlspecialchars(BASE_URL . '/computers/delete') ?>" onsubmit="return confirm('Xóa máy này?');">
                            <input type="hidden" name="id" value="<?= htmlspecialchars((string) $computer['id']) ?>">
                            <button class="btn btn-sm btn-danger" type="submit">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
