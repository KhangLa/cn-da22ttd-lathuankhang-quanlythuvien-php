<?php
$pageTitle = 'Trả sách - Thủ thư';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Trả sách</h1>
        <a href="<?= BASE_URL ?>/librarian/borrows" class="btn btn-secondary">← Quay lại</a>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Thông tin mượn sách</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="150">Sinh viên:</th>
                            <td><?= $data['borrow']['user_name'] ?></td>
                        </tr>
                        <tr>
                            <th>Mã SV:</th>
                            <td><?= $data['borrow']['student_code'] ?></td>
                        </tr>
                        <tr>
                            <th>Sách:</th>
                            <td><?= $data['borrow']['book_title'] ?></td>
                        </tr>
                        <tr>
                            <th>Tác giả:</th>
                            <td><?= $data['borrow']['book_author'] ?></td>
                        </tr>
                        <tr>
                            <th>Ngày mượn:</th>
                            <td><?= formatDate($data['borrow']['borrow_date']) ?></td>
                        </tr>
                        <tr>
                            <th>Hạn trả:</th>
                            <td><?= formatDate($data['borrow']['due_date']) ?></td>
                        </tr>
                        <?php if ($data['overdue_days'] > 0): ?>
                        <tr class="table-danger">
                            <th>Số ngày quá hạn:</th>
                            <td><strong><?= $data['overdue_days'] ?> ngày</strong></td>
                        </tr>
                        <tr class="table-danger">
                            <th>Tiền phạt:</th>
                            <td><strong class="text-danger"><?= formatMoney($data['calculated_fine']) ?></strong></td>
                        </tr>
                        <?php else: ?>
                        <tr class="table-success">
                            <th>Trạng thái:</th>
                            <td><span class="badge badge-success">Đúng hạn</span></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Xác nhận trả sách</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <?php if ($data['overdue_days'] > 0): ?>
                        <div class="alert alert-warning">
                            <strong>⚠️ Sách quá hạn!</strong><br>
                            Sinh viên đã trả muộn <?= $data['overdue_days'] ?> ngày.
                        </div>
                        
                        <div class="form-group">
                            <label>Tiền phạt <span class="text-danger">*</span></label>
                            <input type="number" name="fine_amount" class="form-control" 
                                   value="<?= $data['calculated_fine'] ?>" 
                                   min="0" required>
                            <small class="form-text text-muted">
                                Tự động tính: <?= formatMoney($data['calculated_fine']) ?> 
                                (<?= $data['overdue_days'] ?> ngày × <?= formatMoney(OVERDUE_FINE_PER_DAY) ?>)
                            </small>
                        </div>
                        <?php else: ?>
                        <input type="hidden" name="fine_amount" value="0">
                        <div class="alert alert-success">
                            <strong>✅ Trả đúng hạn!</strong><br>
                            Sinh viên trả sách đúng thời gian quy định.
                        </div>
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label>Ghi chú</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                      placeholder="Ghi chú về tình trạng sách khi trả..."></textarea>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                ✅ Xác nhận trả sách
                            </button>
                        </div>
                        
                        <a href="<?= BASE_URL ?>/librarian/borrows" class="btn btn-secondary btn-block">
                            Hủy
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
