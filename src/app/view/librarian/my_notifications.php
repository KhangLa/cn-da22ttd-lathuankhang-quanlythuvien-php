<?php
$pageTitle = 'Thông báo của tôi - Thủ thư';
require_once __DIR__ . '/../layouts/librarian_header.php';
?>

<div class="page-header">
    <h1>📬 Thông báo của tôi</h1>
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/librarian/dashboard">Dashboard</a>
        <span>/</span>
        <span>Thông báo</span>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>📋 Danh sách thông báo</h3>
        <?php 
        $unreadCount = 0;
        foreach ($data['notifications'] as $notification) {
            if ($notification['is_read'] == 0) {
                $unreadCount++;
            }
        }
        ?>
        <?php if ($unreadCount > 0): ?>
            <a href="<?= BASE_URL ?>/librarian/markAllNotificationsRead" 
               class="btn btn-sm btn-primary"
               onclick="return confirm('Đánh dấu tất cả thông báo là đã đọc?')">
                <i class="fas fa-check-double"></i> Đọc tất cả
            </a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if (empty($data['notifications'])): ?>
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h3>Chưa có thông báo</h3>
                <p>Bạn chưa nhận được thông báo nào</p>
            </div>
        <?php else: ?>
            <div class="notifications-list">
                <?php foreach ($data['notifications'] as $notification): ?>
                    <div class="notification-item <?= $notification['is_read'] == 0 ? 'unread' : '' ?> notification-<?= $notification['type'] ?>">
                        <div class="notification-icon">
                            <?php 
                            $icons = [
                                'info' => 'ℹ️',
                                'success' => '✅',
                                'warning' => '⚠️',
                                'danger' => '🚨',
                                'error' => '❌'
                            ];
                            echo $icons[$notification['type']] ?? 'ℹ️';
                            ?>
                        </div>
                        <div class="notification-content">
                            <div class="notification-header">
                                <h4><?= htmlspecialchars($notification['title']) ?></h4>
                                <span class="notification-time">
                                    <?= timeAgo($notification['created_at']) ?>
                                </span>
                            </div>
                            <p class="notification-message"><?= nl2br(htmlspecialchars($notification['message'])) ?></p>
                            <?php if ($notification['is_read'] == 0): ?>
                                <a href="<?= BASE_URL ?>/librarian/markNotificationRead/<?= $notification['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary mt-2">
                                    Đánh dấu đã đọc
                                </a>
                            <?php else: ?>
                                <span class="badge badge-secondary">Đã đọc</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.card {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 8px 8px 0 0;
}

.card-header h3 {
    margin: 0;
    font-size: 1.2rem;
}

.d-flex {
    display: flex;
}

.justify-content-between {
    justify-content: space-between;
}

.align-items-center {
    align-items: center;
}

.card-body {
    padding: 20px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 80px;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #666;
    margin-bottom: 10px;
}

.empty-state p {
    color: #999;
}

.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.notification-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    background: white;
    transition: all 0.3s;
}

.notification-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.notification-item.unread {
    background: #f8f9ff;
    border-left: 4px solid #667eea;
}

.notification-icon {
    font-size: 32px;
    flex-shrink: 0;
}

.notification-content {
    flex: 1;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 8px;
}

.notification-header h4 {
    margin: 0;
    font-size: 1.1rem;
    color: #333;
}

.notification-time {
    color: #999;
    font-size: 0.85rem;
    white-space: nowrap;
}

.notification-message {
    color: #666;
    margin: 0;
    line-height: 1.6;
}

.notification-info {
    border-left-color: #17a2b8;
}

.notification-success {
    border-left-color: #28a745;
}

.notification-warning {
    border-left-color: #ffc107;
}

.notification-danger, .notification-error {
    border-left-color: #dc3545;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.85rem;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-outline-primary {
    background: transparent;
    border: 1px solid #667eea;
    color: #667eea;
}

.btn-outline-primary:hover {
    background: #667eea;
    color: white;
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-secondary {
    background: #6c757d;
    color: white;
}

.mt-2 {
    margin-top: 0.5rem;
}

.breadcrumb {
    display: flex;
    gap: 8px;
    align-items: center;
    margin-top: 10px;
}

.breadcrumb a {
    color: #667eea;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}
</style>

<?php require_once __DIR__ . '/../layouts/librarian_footer.php'; ?>
