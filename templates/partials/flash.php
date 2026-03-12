<?php if (!empty($flashMessages)): ?>
    <div class="flash-stack">
        <?php foreach ($flashMessages as $message): ?>
            <div class="flash flash-<?= e($message['type'] ?? 'info'); ?>">
                <?= e($message['message'] ?? ''); ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
