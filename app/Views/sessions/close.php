<h2 class="mb-3">Đóng phiên chơi</h2>

<div class="card p-3">
    <p><strong>Máy:</strong> <?= htmlspecialchars((string) $session['computer_code']) ?> - <?= htmlspecialchars((string) $session['computer_name']) ?></p>
    <p><strong>Bắt đầu:</strong> <?= htmlspecialchars((string) $session['start_time']) ?></p>
    <p><strong>Tạm tính:</strong> <?= htmlspecialchars(number_format((float) $estimatedFee, 0, ',', '.')) ?> đ</p>

    <form method="post" action="<?= htmlspecialchars(BASE_URL . '/sessions/close') ?>" onsubmit="return confirm('Xác nhận đóng phiên chơi này?');">
        <input type="hidden" name="id" value="<?= htmlspecialchars((string) $session['id']) ?>">
        <button class="btn btn-danger" type="submit">Xác nhận đóng phiên</button>
        <a class="btn btn-secondary" href="<?= htmlspecialchars(BASE_URL . '/sessions') ?>">Hủy</a>
    </form>
</div>
