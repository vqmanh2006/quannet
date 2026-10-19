<?php if (($flash ?? null) !== null): ?>
    <div class="alert alert-<?= htmlspecialchars((string) $flash['type']) ?>" role="alert">
        <?= htmlspecialchars((string) $flash['message']) ?>
    </div>
<?php endif; ?>
