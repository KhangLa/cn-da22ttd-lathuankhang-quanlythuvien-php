<?php 
$pageTitle = 'Thông báo - Thư viện TVU';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container" style="padding: 2rem 0;">
    <div class="d-flex justify-between align-center mb-4">
        <h1>🔔 Thông báo</h1>
    </div>
    
    <?php if (!empty($data['notifications'])): ?>
    <div class="card">
        <div class="card-body">
            <?php foreach ($data['notifications'] as $notification): ?>
            <div class="mb-3 p-3" style="border-left: 4px solid 
                <?php
                switch ($notification['type']) {
                    case 'success': echo '#10b981'; break;
                    case 'warning': echo '#f59e0b'; break;
                    case 'error': echo '#ef4444'; break;
                    default: echo '#3b82f6';
                }
                ?>;
                background-color: <?= $notification['is_read'] ? '#f8fafc' : '#fff' ?>;
                border-radius: 0.375rem;">
                <div class="d-flex justify-between align-center mb-2">
                    <strong style="font-size: 1.125rem;"><?= htmlspecialchars($notification['title']) ?></strong>
                    <small class="text-muted"><?= formatDateTime($notification['created_at']) ?></small>
                </div>
                <p class="mb-2"><?= nl2br(htmlspecialchars($notification['message'])) ?></p>
                <?php if (!$notification['is_read']): ?>
                <a href="<?= BASE_URL ?>/student/mark-notification-read/<?= $notification['id'] ?>" 
                   class="btn btn-sm btn-primary">
                    Đánh dấu đã đọc
                </a>
                <?php else: ?>
                <span class="badge badge-success">✓ Đã đọc</span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Pagination -->
    <?php if ($data['pagination']['total_pages'] > 1): ?>
    <nav class="mt-3">
        <ul class="pagination">
            <?php if ($data['pagination']['has_previous']): ?>
            <li><a href="?page=<?= $data['pagination']['current_page'] - 1 ?>">← Trước</a></li>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
            <li class="<?= $i == $data['pagination']['current_page'] ? 'active' : '' ?>">
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
            
            <?php if ($data['pagination']['has_next']): ?>
            <li><a href="?page=<?= $data['pagination']['current_page'] + 1 ?>">Sau →</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
    
    <?php else: ?>
    <div class="card">
        <div class="card-body text-center p-4">
            <p style="font-size: 3rem;">🔕</p>
            <h3>Không có thông báo</h3>
            <p class="text-muted">Bạn chưa có thông báo nào</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
