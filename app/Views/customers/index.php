<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Danh sách khách hàng</h2>
    <a class="btn btn-primary" href="<?= htmlspecialchars(BASE_URL . '/customers/create') ?>">Thêm khách hàng</a>
</div>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>#</th>
            <th>Họ tên</th>
            <th>SĐT</th>
            <th>Email</th>
            <th>Ghi chú</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($customers === []): ?>
            <tr><td colspan="6" class="text-center text-muted">Chưa có dữ liệu.</td></tr>
        <?php else: ?>
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $customer['id']) ?></td>
                    <td><?= htmlspecialchars((string) $customer['full_name']) ?></td>
                    <td><?= htmlspecialchars((string) $customer['phone']) ?></td>
                    <td><?= htmlspecialchars((string) $customer['email']) ?></td>
                    <td><?= htmlspecialchars((string) $customer['note']) ?></td>
                    <td>
                        <a class="btn btn-sm btn-warning" href="<?= htmlspecialchars(BASE_URL . '/customers/edit?id=' . (int) $customer['id']) ?>">Sửa</a>
                        <form class="d-inline" method="post" action="<?= htmlspecialchars(BASE_URL . '/customers/delete') ?>" onsubmit="return confirm('Xóa khách hàng này?');">
                            <input type="hidden" name="id" value="<?= htmlspecialchars((string) $customer['id']) ?>">
                            <button class="btn btn-sm btn-danger" type="submit">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
