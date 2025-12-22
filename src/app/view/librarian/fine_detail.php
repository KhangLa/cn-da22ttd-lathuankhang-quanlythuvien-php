<?php
$pageTitle = 'Chi tiết phiếu phạt - Thủ thư';
require_once __DIR__ . '/../layouts/librarian_header.php';

$fine = $data['fine'] ?? null;

if (!$fine) {
    redirect('librarian/fines');
}

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

<div class="container-fluid py-4">
    <div class="mb-3">
        <a href="<?= BASE_URL ?>/librarian/fines" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">💰 Chi tiết phiếu phạt #<?= $fine['id'] ?></h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Sinh viên:</strong><br>
                            <?= htmlspecialchars($fine['student_name']) ?><br>
                            <small class="text-muted">
                                Mã SV: <?= htmlspecialchars($fine['student_code']) ?><br>
                                Email: <?= htmlspecialchars($fine['student_email']) ?><br>
                                <?php if ($fine['student_phone']): ?>
                                    SĐT: <?= htmlspecialchars($fine['student_phone']) ?>
                                <?php endif; ?>
                            </small>
                        </div>
                        <div class="col-md-6">
                            <strong>Trạng thái:</strong><br>
                            <?= getStatusBadge($fine['status']) ?>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Loại phạt:</strong><br>
                            <span class="badge badge-warning badge-lg">
                                <?= getFineTypeText($fine['fine_type']) ?>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Số tiền:</strong><br>
                            <h4 class="text-danger"><?= number_format($fine['amount']) ?> VNĐ</h4>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Lý do phạt:</strong>
                        <div class="alert alert-light mt-2">
                            <?= nl2br(htmlspecialchars($fine['reason'])) ?>
                        </div>
                    </div>
                    
                    <?php if ($fine['book_title']): ?>
                    <div class="mb-3">
                        <strong>Sách liên quan:</strong><br>
                        <?= htmlspecialchars($fine['book_title']) ?>
                        <?php if ($fine['author']): ?>
                            - <small class="text-muted"><?= htmlspecialchars($fine['author']) ?></small>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Ngày tạo:</strong><br>
                            <?= date('d/m/Y H:i', strtotime($fine['created_at'])) ?><br>
                            <small class="text-muted">Bởi: <?= htmlspecialchars($fine['creator_name']) ?></small>
                        </div>
                        <?php if ($fine['paid_date']): ?>
                        <div class="col-md-6">
                            <strong>Ngày thanh toán:</strong><br>
                            <?= date('d/m/Y H:i', strtotime($fine['paid_date'])) ?><br>
                            <small class="text-muted">Số tiền: <?= number_format($fine['paid_amount']) ?> VNĐ</small>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($fine['payment_note']): ?>
                    <hr>
                    <div class="mb-3">
                        <strong>Ghi chú thanh toán:</strong>
                        <div class="alert alert-info mt-2">
                            <?= nl2br(htmlspecialchars($fine['payment_note'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <?php if ($fine['status'] === 'unpaid'): ?>
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">✅ Xác nhận thanh toán</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>/librarian/mark-fine-paid/<?= $fine['id'] ?>">
                        <div class="form-group">
                            <label for="paid_amount">Số tiền đã thanh toán <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="paid_amount" name="paid_amount" 
                                   value="<?= $fine['amount'] ?>" min="0" step="1000" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment_note">Ghi chú</label>
                            <textarea class="form-control" id="payment_note" name="payment_note" 
                                      rows="3" placeholder="Ghi chú về thanh toán..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-check"></i> Xác nhận đã thanh toán
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">🎁 Miễn phạt</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>/librarian/waive-fine/<?= $fine['id'] ?>" 
                          onsubmit="return confirm('Bạn có chắc muốn miễn phạt này?')">
                        <div class="form-group">
                            <label for="waive_note">Lý do miễn phạt</label>
                            <textarea class="form-control" id="waive_note" name="waive_note" 
                                      rows="3" placeholder="Nhập lý do miễn phạt..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fas fa-gift"></i> Miễn phạt
                        </button>
                    </form>
                </div>
            </div>
            <?php else: ?>
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">ℹ️ Thông tin</h6>
                </div>
                <div class="card-body">
                    <p>Phiếu phạt này đã được xử lý.</p>
                    <?php if ($fine['status'] === 'paid'): ?>
                        <p class="text-success">✅ Đã thanh toán</p>
                    <?php elseif ($fine['status'] === 'waived'): ?>
                        <p class="text-info">🎁 Đã được miễn</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
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
