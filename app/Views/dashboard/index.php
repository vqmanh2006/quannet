<h2 class="mb-4">Dashboard</h2>

<div class="row g-3 mb-4">
    <div class="col-md-2"><div class="card card-stat p-3"><strong>Tổng máy</strong><div><?= htmlspecialchars((string) $stats['total']) ?></div></div></div>
    <div class="col-md-2"><div class="card card-stat p-3"><strong>Available</strong><div><?= htmlspecialchars((string) $stats['available']) ?></div></div></div>
    <div class="col-md-2"><div class="card card-stat p-3"><strong>Playing</strong><div><?= htmlspecialchars((string) $stats['playing']) ?></div></div></div>
    <div class="col-md-2"><div class="card card-stat p-3"><strong>Maintenance</strong><div><?= htmlspecialchars((string) $stats['maintenance']) ?></div></div></div>
    <div class="col-md-2"><div class="card card-stat p-3"><strong>Phiên hoạt động</strong><div><?= htmlspecialchars((string) $activeSessionsCount) ?></div></div></div>
    <div class="col-md-2"><div class="card card-stat p-3"><strong>Doanh thu hôm nay</strong><div><?= htmlspecialchars(number_format((float) $todayRevenue, 0, ',', '.')) ?> đ</div></div></div>
</div>

<div class="card">
    <div class="card-header">Phiên đang chơi</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>#</th>
                <th>Mã máy</th>
                <th>Tên máy</th>
                <th>Khách hàng</th>
                <th>Bắt đầu</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($activeSessions === []): ?>
                <tr><td colspan="5" class="text-center text-muted">Không có phiên nào đang hoạt động.</td></tr>
            <?php else: ?>
                <?php foreach ($activeSessions as $session): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $session['id']) ?></td>
                        <td><?= htmlspecialchars((string) $session['computer_code']) ?></td>
                        <td><?= htmlspecialchars((string) $session['computer_name']) ?></td>
                        <td><?= htmlspecialchars((string) ($session['customer_name'] ?? 'Khách lẻ')) ?></td>
                        <td><?= htmlspecialchars((string) $session['start_time']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
