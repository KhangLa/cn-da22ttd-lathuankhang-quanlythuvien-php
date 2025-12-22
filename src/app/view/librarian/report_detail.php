<?php
$pageTitle = 'Chi tiết báo cáo - Thủ thư';
require_once __DIR__ . '/../layouts/librarian_header.php';

$report = $data['report'] ?? null;
$book = $data['book'] ?? null;
$borrow = $data['borrow'] ?? null;

if (!$report) {
    redirect('librarian/book-reports');
}

// Helper functions
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

<div class="container-fluid py-4">
    <div class="mb-3">
        <a href="<?= BASE_URL ?>/librarian/book-reports" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">📋 Chi tiết báo cáo #<?= $report['id'] ?></h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Sinh viên:</strong><br>
                            <?= htmlspecialchars($report['student_name']) ?><br>
                            <small class="text-muted">Mã SV: <?= htmlspecialchars($report['student_code']) ?></small>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong><br>
                            <a href="mailto:<?= htmlspecialchars($report['student_email']) ?>">
                                <?= htmlspecialchars($report['student_email']) ?>
                            </a>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <strong>Sách:</strong><br>
                        <h5><?= htmlspecialchars($report['book_title']) ?></h5>
                        <p class="text-muted">
                            Tác giả: <?= htmlspecialchars($report['author']) ?><br>
                            ISBN: <?= htmlspecialchars($report['isbn']) ?>
                        </p>
                        <?php if ($book): ?>
                            <a href="<?= BASE_URL ?>/librarian/edit-book/<?= $book['id'] ?>" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-book"></i> Xem chi tiết sách
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <strong>Loại vấn đề:</strong><br>
                        <span class="badge badge-warning badge-lg">
                            <?= getReportTypeText($report['report_type']) ?>
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Mô tả chi tiết:</strong>
                        <div class="alert alert-light mt-2">
                            <?= nl2br(htmlspecialchars($report['description'])) ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Trạng thái:</strong><br>
                        <?= getStatusBadge($report['status']) ?>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Ngày gửi:</strong><br>
                            <?= date('d/m/Y H:i', strtotime($report['created_at'])) ?>
                        </div>
                        <?php if ($report['reviewed_at']): ?>
                        <div class="col-md-6">
                            <strong>Ngày xử lý:</strong><br>
                            <?= date('d/m/Y H:i', strtotime($report['reviewed_at'])) ?>
                            <?php if ($report['reviewer_name']): ?>
                                <br><small class="text-muted">Bởi: <?= htmlspecialchars($report['reviewer_name']) ?></small>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($report['librarian_note']): ?>
                    <hr>
                    <div class="mb-3">
                        <strong>Ghi chú từ thủ thư:</strong>
                        <div class="alert alert-info mt-2">
                            <?= nl2br(htmlspecialchars($report['librarian_note'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($borrow): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">📚 Thông tin phiếu mượn</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Ngày mượn:</strong><br>
                            <?= date('d/m/Y', strtotime($borrow['borrow_date'])) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Hạn trả:</strong><br>
                            <?= date('d/m/Y', strtotime($borrow['due_date'])) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Trạng thái:</strong><br>
                            <span class="badge badge-<?= $borrow['status'] === 'borrowed' ? 'primary' : 'success' ?>">
                                <?= ucfirst($borrow['status']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">⚙️ Xử lý báo cáo</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>/librarian/update-report-status/<?= $report['id'] ?>">
                        <div class="form-group">
                            <label for="status">Cập nhật trạng thái <span class="text-danger">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="reviewed" <?= $report['status'] === 'reviewed' ? 'selected' : '' ?>>
                                    Đang xem xét
                                </option>
                                <option value="resolved" <?= $report['status'] === 'resolved' ? 'selected' : '' ?>>
                                    Đã giải quyết
                                </option>
                                <option value="rejected" <?= $report['status'] === 'rejected' ? 'selected' : '' ?>>
                                    Từ chối
                                </option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="librarian_note">Ghi chú cho sinh viên</label>
                            <textarea class="form-control" id="librarian_note" name="librarian_note" 
                                      rows="5" placeholder="Nhập ghi chú về cách xử lý, lý do từ chối..."><?= htmlspecialchars($report['librarian_note'] ?? '') ?></textarea>
                            <small class="form-text text-muted">
                                Ghi chú này sẽ được gửi cho sinh viên
                            </small>
                        </div>
                        
                        <div class="alert alert-warning">
                            <small>
                                <strong>⚠️ Lưu ý:</strong><br>
                                - Sinh viên sẽ nhận thông báo về việc cập nhật<br>
                                - Hãy ghi rõ cách xử lý hoặc lý do
                            </small>
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-check"></i> Cập nhật trạng thái
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">📝 Gợi ý xử lý</h6>
                </div>
                <div class="card-body">
                    <small>
                        <strong>Sách bị hư hỏng:</strong> Kiểm tra mức độ hư hỏng, xem xét phí bồi thường<br><br>
                        <strong>Thiếu trang:</strong> Xác định trang bị thiếu, yêu cầu bồi thường<br><br>
                        <strong>Mất sách:</strong> Yêu cầu bồi thường toàn bộ giá trị sách<br><br>
                        <strong>Rách, xé:</strong> Đánh giá có thể sửa chữa hay không
                    </small>
                </div>
            </div>
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
