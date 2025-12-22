<?php
$pageTitle = 'Tạo phiếu mượn - Thủ thư';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Tạo phiếu mượn mới</h1>
        <a href="<?= BASE_URL ?>/librarian/borrows" class="btn btn-secondary">← Quay lại</a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" id="borrowForm">
                <div class="form-group">
                    <label>Mã sinh viên <span class="text-danger">*</span></label>
                    <input type="text" name="student_code" id="studentCode" class="form-control" 
                           placeholder="Nhập mã sinh viên" required autofocus>
                    <small class="form-text text-muted">Nhập mã sinh viên để kiểm tra</small>
                </div>
                
                <div class="form-group">
                    <label>Sách <span class="text-danger">*</span></label>
                    <select name="book_id" class="form-control" required>
                        <option value="">-- Chọn sách --</option>
                        <?php foreach ($data['books'] as $book): ?>
                        <option value="<?= $book['id'] ?>">
                            <?= $book['title'] ?> - <?= $book['author'] ?> 
                            (Còn: <?= $book['available_quantity'] ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Ghi chú</label>
                    <textarea name="notes" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="alert alert-info">
                    <strong>Thông tin:</strong>
                    <ul class="mb-0">
                        <li>Số ngày mượn: <?= MAX_BORROW_DAYS ?> ngày</li>
                        <li>Số sách mượn tối đa: <?= MAX_BOOKS_PER_USER ?> cuốn</li>
                        <li>Phí phạt quá hạn: <?= formatMoney(OVERDUE_FINE_PER_DAY) ?>/ngày</li>
                    </ul>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        💾 Tạo phiếu mượn
                    </button>
                    <a href="<?= BASE_URL ?>/librarian/borrows" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
