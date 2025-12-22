<?php
$pageTitle = 'Báo cáo tình trạng sách - Thủ thư';
require_once __DIR__ . '/../layouts/librarian_header.php';

$reports = $data['reports'] ?? [];
$pagination = $data['pagination'] ?? '';
$selectedStatus = $data['selected_status'] ?? '';
$pendingCount = $data['pending_count'] ?? 0;
$reviewedCount = $data['reviewed_count'] ?? 0;

// Helper function để hiển thị trạng thái
function getStatusBadge($status) {
    $badges = [
        'pending' => '<span class="badge badge-warning">Chờ xử lý</span>',
        'reviewed' => '<span class="badge badge-info">Đã xem</span>',
        'resolved' => '<span class="badge badge-success">Đã giải quyết</span>',
        'rejected' => '<span class="badge badge-danger">Từ chối</span>'
    ];
    return $badges[$status] ?? '<span class="badge badge-secondary">Không rõ</span>';
}

function getReportTypeText($type) {
    $types = [
        'damaged' => 'Sách bị hư hỏng',
        'missing_pages' => 'Thiếu trang',
        'torn' => 'Rách, xé',
        'stained' => 'Bị dơ, ố',
        'lost' => 'Mất sách',
        'other' => 'Khác'
    ];
    return $types[$type] ?? $type;
}

function getReportTypeIcon($type) {
    $icons = [
        'damaged' => '💔',
        'missing_pages' => '📄',
        'torn' => '✂️',
        'stained' => '💧',
        'lost' => '🔍',
        'other' => '❓'
    ];
    return $icons[$type] ?? '📋';
}
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>📋 Báo cáo tình trạng sách</h2>
            <p class="text-muted">Quản lý các báo cáo về sách hư hỏng, mất trang từ sinh viên</p>
        </div>
        <div>
            <span class="badge badge-warning badge-lg">
                <?= $pendingCount ?> chờ xử lý
            </span>
            <span class="badge badge-info badge-lg ml-2">
                <?= $reviewedCount ?> đang xem xét
            </span>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="<?= BASE_URL ?>/librarian/book-reports" class="form-inline">
                <label class="mr-2">Lọc theo trạng thái:</label>
                <select name="status" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="">Tất cả</option>
                    <option value="pending" <?= $selectedStatus === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                    <option value="reviewed" <?= $selectedStatus === 'reviewed' ? 'selected' : '' ?>>Đã xem</option>
                    <option value="resolved" <?= $selectedStatus === 'resolved' ? 'selected' : '' ?>>Đã giải quyết</option>
                    <option value="rejected" <?= $selectedStatus === 'rejected' ? 'selected' : '' ?>>Từ chối</option>
                </select>
                <?php if ($selectedStatus): ?>
                    <a href="<?= BASE_URL ?>/librarian/book-reports" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Xóa lọc
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <!-- Reports List -->
    <?php if (empty($reports)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Chưa có báo cáo nào
            <?= $selectedStatus ? 'với trạng thái này' : '' ?>.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th width="100">Loại</th>
                                <th>Sinh viên</th>
                                <th>Sách</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th>Ngày gửi</th>
                                <th width="150">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $index => $report): ?>
                                <tr class="<?= $report['status'] === 'pending' ? 'table-warning' : '' ?>">
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <span title="<?= getReportTypeText($report['report_type']) ?>">
                                            <?= getReportTypeIcon($report['report_type']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($report['student_name']) ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($report['student_code']) ?></small>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($report['book_title']) ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($report['author']) ?></small>
                                    </td>
                                    <td>
                                        <small><?= nl2br(htmlspecialchars(substr($report['description'], 0, 80))) ?>
                                        <?php if (strlen($report['description']) > 80): ?>...<?php endif; ?>
                                        </small>
                                    </td>
                                    <td><?= getStatusBadge($report['status']) ?></td>
                                    <td>
                                        <?= date('d/m/Y', strtotime($report['created_at'])) ?><br>
                                        <small class="text-muted"><?= date('H:i', strtotime($report['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/librarian/view-report/<?= $report['id'] ?>" 
                                           class="btn btn-sm btn-primary" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <?php if ($pagination && $pagination['total_pages'] > 1): ?>
            <div class="mt-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($pagination['has_previous']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $pagination['current_page'] - 1 ?><?= $selectedStatus ? '&status=' . $selectedStatus : '' ?>">
                                    « Trước
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                            <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?><?= $selectedStatus ? '&status=' . $selectedStatus : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['has_next']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $pagination['current_page'] + 1 ?><?= $selectedStatus ? '&status=' . $selectedStatus : '' ?>">
                                    Sau »
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    
    <div class="mt-3">
        <div class="alert alert-secondary">
            <strong>📌 Hướng dẫn xử lý:</strong>
            <ul class="mb-0">
                <li><strong>Chờ xử lý:</strong> Báo cáo mới chưa được xem</li>
                <li><strong>Đã xem:</strong> Đang kiểm tra và xử lý</li>
                <li><strong>Đã giải quyết:</strong> Vấn đề đã được xử lý xong</li>
                <li><strong>Từ chối:</strong> Báo cáo không hợp lệ hoặc không chính xác</li>
            </ul>
        </div>
    </div>
</div>

<style>
.badge-lg {
    font-size: 1rem;
    padding: 0.5rem 1rem;
}
</style>

<?php require_once __DIR__ . '/../layouts/librarian_footer.php'; ?>
