<?php
$pageTitle = 'Phiếu phạt của tôi - Sinh viên';
require_once __DIR__ . '/../layouts/header.php';

$fines = $data['fines'] ?? [];
$totalUnpaid = $data['total_unpaid'] ?? 0;

// Helper functions
function getStatusBadge($status) {
    $badges = [
        'unpaid' => '<span class="badge badge-danger">Chưa thanh toán</span>',
        'paid' => '<span class="badge badge-success">Đã thanh toán</span>',
        'waived' => '<span class="badge badge-info">Đã miễn</span>'
    ];
    return $badges[$status] ?? '<span class="badge badge-secondary">Không rõ</span>';
}

function getFineTypeText($type) {
    $types = [
        'overdue' => 'Trả trễ',
        'damaged' => 'Sách hư hỏng',
        'lost' => 'Mất sách',
        'other' => 'Khác'
    ];
    return $types[$type] ?? $type;
}
?>

<div class="container my-5">
    <div class="page-header mb-4">
        <h1>Lịch sử vi phạm</h1>
        <p>Danh sách các khoản phạt và trạng thái thanh toán</p>
    </div>
    
    <?php if ($totalUnpaid > 0): ?>
        <div class="alert alert-danger">
            <h5><strong>⚠️ Bạn có khoản phạt chưa thanh toán!</strong></h5>
            <p class="mb-0">Tổng số tiền cần thanh toán: <strong><?= number_format($totalUnpaid) ?> VNĐ</strong></p>
            <small>Vui lòng đến quầy thủ thư để thanh toán.</small>
        </div>
    <?php endif; ?>
    
    <?php if (empty($fines)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            Bạn không có phiếu phạt nào. Tuyệt vời!
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Loại phạt</th>
                                <th>Số tiền</th>
                                <th>Lý do</th>
                                <th>Sách</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fines as $index => $fine): ?>
                                <tr class="<?= $fine['status'] === 'unpaid' ? 'table-danger' : '' ?>">
                                    <td><?= $index + 1 ?></td>
                                    <td><?= getFineTypeText($fine['fine_type']) ?></td>
                                    <td>
                                        <strong class="text-danger"><?= number_format($fine['amount']) ?> VNĐ</strong>
                                    </td>
                                    <td>
                                        <small><?= nl2br(htmlspecialchars(substr($fine['reason'], 0, 80))) ?>
                                        <?php if (strlen($fine['reason']) > 80): ?>...<?php endif; ?>
                                        </small>
                                        
                                        <!-- Tooltip để xem đầy đủ -->
                                        <?php if (strlen($fine['reason']) > 80): ?>
                                        <button type="button" class="btn btn-sm btn-link" 
                                                data-toggle="modal" data-target="#reasonModal<?= $fine['id'] ?>">
                                            Xem đầy đủ
                                        </button>
                                        
                                        <!-- Modal -->
                                        <div class="modal fade" id="reasonModal<?= $fine['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Lý do phạt</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?= nl2br(htmlspecialchars($fine['reason'])) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($fine['book_title']): ?>
                                            <small><?= htmlspecialchars($fine['book_title']) ?></small>
                                        <?php else: ?>
                                            <small class="text-muted">-</small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= getStatusBadge($fine['status']) ?></td>
                                    <td>
                                        <?= date('d/m/Y', strtotime($fine['created_at'])) ?><br>
                                        <small class="text-muted"><?= date('H:i', strtotime($fine['created_at'])) ?></small>
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
                <strong>📌 Chú thích trạng thái:</strong><br>
                <?= getStatusBadge('unpaid') ?> Cần thanh toán tại quầy thủ thư<br>
                <?= getStatusBadge('paid') ?> Đã hoàn thành thanh toán<br>
                <?= getStatusBadge('waived') ?> Đã được miễn phạt
            </div>
        </div>
        
        <?php if ($totalUnpaid > 0): ?>
        <div class="alert alert-warning mt-3">
            <h6><strong>💡 Hướng dẫn thanh toán:</strong></h6>
            <ol class="mb-0">
                <li>Đến quầy thủ thư trong giờ làm việc</li>
                <li>Xuất trình thẻ sinh viên</li>
                <li>Thanh toán số tiền phạt</li>
                <li>Nhận xác nhận từ thủ thư</li>
            </ol>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<style>
.page-header {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 1rem;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
