<?php
$pageTitle = 'Yêu cầu mượn sách - Thủ thư';
require_once __DIR__ . '/../layouts/librarian_header.php';
?>

<div class="page-header">
    <h1>📋 Yêu cầu mượn sách chờ duyệt</h1>
    <span class="badge badge-warning" style="font-size: 1.2rem;">
        <?= count($data['requests']) ?> yêu cầu
    </span>
</div>

<?php if (empty($data['requests'])): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <div style="font-size: 4rem; margin-bottom: 1rem;">✅</div>
            <h3>Không có yêu cầu mới</h3>
            <p class="text-muted">Tất cả yêu cầu đã được xử lý</p>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Sinh viên</th>
                        <th>Sách</th>
                        <th>Ngày yêu cầu</th>
                        <th>Hạn trả dự kiến</th>
                        <th>SL có sẵn</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['requests'] as $index => $request): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td>
                            <strong><?= htmlspecialchars($request['user_name']) ?></strong><br>
                            <small class="text-muted">
                                <?= htmlspecialchars($request['student_code']) ?><br>
                                <?= htmlspecialchars($request['email']) ?>
                            </small>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($request['book_title']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($request['book_author']) ?></small>
                        </td>
                        <td><?= formatDateTime($request['created_at']) ?></td>
                        <td><?= formatDate($request['due_date']) ?></td>
                        <td>
                            <?php if ($request['available_quantity'] > 0): ?>
                                <span class="badge badge-success"><?= $request['available_quantity'] ?></span>
                            <?php else: ?>
                                <span class="badge badge-danger">Hết sách</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <?php if ($request['available_quantity'] > 0): ?>
                                    <a href="<?= BASE_URL ?>/librarian/approveRequest/<?= $request['id'] ?>" 
                                       class="btn btn-sm btn-success"
                                       onclick="return confirm('Xác nhận duyệt yêu cầu này?')">
                                        ✓ Duyệt
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-success" disabled title="Không đủ sách">
                                        ✓ Duyệt
                                    </button>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-danger" 
                                        onclick="showRejectModal(<?= $request['id'] ?>, '<?= htmlspecialchars($request['book_title']) ?>')">
                                    ✗ Từ chối
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php if ($data['pagination']['total_pages'] > 1): ?>
        <div class="pagination">
            <?php if ($data['pagination']['has_previous']): ?>
                <a href="?page=<?= $data['pagination']['current_page'] - 1 ?>" class="btn btn-sm btn-secondary">« Trước</a>
            <?php endif; ?>
            
            <span class="mx-2">
                Trang <?= $data['pagination']['current_page'] ?> / <?= $data['pagination']['total_pages'] ?>
            </span>
            
            <?php if ($data['pagination']['has_next']): ?>
                <a href="?page=<?= $data['pagination']['current_page'] + 1 ?>" class="btn btn-sm btn-secondary">Sau »</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<!-- Modal từ chối -->
<div class="modal" id="rejectModal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Từ chối yêu cầu mượn sách</h3>
                <button type="button" class="btn-close" onclick="closeRejectModal()">×</button>
            </div>
            <form method="POST" id="rejectForm">
                <div class="modal-body">
                    <p>Bạn đang từ chối yêu cầu mượn sách: <strong id="bookTitle"></strong></p>
                    <div class="form-group">
                        <label for="reason">Lý do từ chối <span class="required">*</span></label>
                        <textarea name="reason" id="reason" class="form-control" rows="4" 
                                  placeholder="Nhập lý do từ chối..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeRejectModal()">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xác nhận từ chối</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-dialog {
    width: 90%;
    max-width: 600px;
}

.modal-content {
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.modal-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.btn-close {
    background: none;
    border: none;
    color: white;
    font-size: 2rem;
    cursor: pointer;
    line-height: 1;
}

.required {
    color: #dc3545;
}

.text-center {
    text-align: center;
}

.py-5 {
    padding-top: 3rem;
    padding-bottom: 3rem;
}

.mx-2 {
    margin-left: 0.5rem;
    margin-right: 0.5rem;
}
</style>

<script>
function showRejectModal(requestId, bookTitle) {
    document.getElementById('rejectModal').style.display = 'flex';
    document.getElementById('bookTitle').textContent = bookTitle;
    document.getElementById('rejectForm').action = '<?= BASE_URL ?>/librarian/rejectRequest/' + requestId;
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('reason').value = '';
}

// Đóng modal khi click bên ngoài
document.getElementById('rejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/librarian_footer.php'; ?>
