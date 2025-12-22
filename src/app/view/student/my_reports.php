<?php
$pageTitle = 'Báo cáo của tôi - Sinh viên';
require_once __DIR__ . '/../layouts/header.php';

$reports = $data['reports'] ?? [];

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
?>

<div class="container my-5">
    <div class="page-header">
        <h1>📋 Báo cáo của tôi</h1>
        <p>Danh sách các báo cáo tình trạng sách bạn đã gửi</p>
    </div>
    
    <div class="mb-3">
        <a href="<?= BASE_URL ?>/student/report-book" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo báo cáo mới
        </a>
    </div>
    
    <?php if (empty($reports)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Bạn chưa có báo cáo nào. <a href="<?= BASE_URL ?>/student/report-book">Tạo báo cáo mới</a>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="60">#</th>
                                <th>Sách</th>
                                <th>Loại vấn đề</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th>Ngày gửi</th>
                                <th>Ghi chú thủ thư</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $index => $report): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($report['book_title']) ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($report['author']) ?></small>
                                    </td>
                                    <td><?= getReportTypeText($report['report_type']) ?></td>
                                    <td>
                                        <small><?= nl2br(htmlspecialchars(substr($report['description'], 0, 100))) ?>
                                        <?php if (strlen($report['description']) > 100): ?>...<?php endif; ?>
                                        </small>
                                    </td>
                                    <td><?= getStatusBadge($report['status']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($report['created_at'])) ?></td>
                                    <td>
                                        <?php if ($report['librarian_note']): ?>
                                            <small><?= nl2br(htmlspecialchars($report['librarian_note'])) ?></small><br>
                                            <?php if ($report['reviewer_name']): ?>
                                                <small class="text-muted">- <?= htmlspecialchars($report['reviewer_name']) ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <small class="text-muted">Chưa có</small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <div class="alert alert-secondary">
                <strong>Chú thích trạng thái:</strong><br>
                <?= getStatusBadge('pending') ?> Đang chờ thủ thư xem xét<br>
                <?= getStatusBadge('reviewed') ?> Thủ thư đã xem và đang xử lý<br>
                <?= getStatusBadge('resolved') ?> Vấn đề đã được giải quyết<br>
                <?= getStatusBadge('rejected') ?> Báo cáo bị từ chối hoặc không hợp lệ
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
