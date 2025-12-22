<?php
$pageTitle = 'Tạo phiếu phạt - Thủ thư';
require_once __DIR__ . '/../layouts/librarian_header.php';

$students = $data['students'] ?? [];
?>

<div class="container-fluid py-4">
    <div class="mb-3">
        <a href="<?= BASE_URL ?>/librarian/fines" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">💰 Tạo phiếu phạt mới</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>/librarian/create-fine">
                        <div class="form-group">
                            <label for="user_id">Sinh viên <span class="text-danger">*</span></label>
                            <select class="form-control" id="user_id" name="user_id" required>
                                <option value="">-- Chọn sinh viên --</option>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?= $student['id'] ?>">
                                        <?= htmlspecialchars($student['full_name']) ?> - <?= htmlspecialchars($student['student_code']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="fine_type">Loại phạt <span class="text-danger">*</span></label>
                            <select class="form-control" id="fine_type" name="fine_type" required>
                                <option value="">-- Chọn loại phạt --</option>
                                <option value="overdue">Trả trễ</option>
                                <option value="damaged">Sách hư hỏng</option>
                                <option value="lost">Mất sách</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="amount">Số tiền phạt (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="amount" name="amount" 
                                   min="1000" step="1000" required placeholder="VD: 50000">
                            <small class="form-text text-muted">Nhập số tiền phạt, tối thiểu 1,000 VNĐ</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="borrow_id">Mã phiếu mượn (nếu có)</label>
                            <input type="number" class="form-control" id="borrow_id" name="borrow_id" 
                                   placeholder="VD: 15">
                            <small class="form-text text-muted">Để trống nếu không liên quan đến phiếu mượn cụ thể</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="reason">Lý do phạt <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="reason" name="reason" rows="5" 
                                      required placeholder="Nhập lý do phạt chi tiết (ít nhất 10 ký tự)..."></textarea>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Tạo phiếu phạt
                            </button>
                            <a href="<?= BASE_URL ?>/librarian/fines" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">📝 Hướng dẫn</h6>
                </div>
                <div class="card-body">
                    <h6>Mức phạt tham khảo:</h6>
                    <ul>
                        <li><strong>Trả trễ:</strong> 5,000 VNĐ/ngày</li>
                        <li><strong>Sách hư hỏng:</strong> 20,000 - 100,000 VNĐ tùy mức độ</li>
                        <li><strong>Mất sách:</strong> 100% giá trị sách</li>
                    </ul>
                    <hr>
                    <small class="text-muted">
                        <strong>Lưu ý:</strong> Sinh viên sẽ nhận thông báo ngay sau khi phiếu phạt được tạo.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/librarian_footer.php'; ?>
