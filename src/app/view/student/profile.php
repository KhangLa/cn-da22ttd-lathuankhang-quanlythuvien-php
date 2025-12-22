<?php 
$pageTitle = 'Thông tin cá nhân - Thư viện TVU';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container" style="padding: 2rem 0;">
    <h1 class="mb-4">👤 Thông tin cá nhân</h1>
    
    <div class="grid grid-3 gap-2">
        <!-- Profile Form -->
        <div style="grid-column: span 2;">
            <div class="card">
                <div class="card-header">
                    <h3>Thông tin tài khoản</h3>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($data['user']['username']) ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Mã sinh viên</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($data['user']['student_code'] ?? '') ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Mã lớp</label>
                            <input type="text" name="class_code" class="form-control" 
                                   value="<?= htmlspecialchars($data['user']['class_code'] ?? '') ?>" 
                                   placeholder="Ví dụ: 62THTH01">
                            <small class="text-muted">Mã lớp của bạn (có thể bỏ trống)</small>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($data['user']['full_name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['user']['email']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($data['user']['phone'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="address" class="form-control"><?= htmlspecialchars($data['user']['address'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Ảnh đại diện</label>
                            <?php if (!empty($data['user']['avatar'])): ?>
                            <div class="current-avatar mb-2">
                                <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($data['user']['avatar']) ?>" 
                                     alt="Avatar hiện tại" 
                                     class="avatar-preview"
                                     onerror="this.src='<?= BASE_URL ?>/public/uploads/user_avatars/default-avatar.png'; this.onerror=null;">
                                <p class="text-muted" style="font-size: 0.875rem; margin-top: 0.5rem;">Ảnh đại diện hiện tại</p>
                            </div>
                            <?php endif; ?>
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                            <small class="text-muted">Chọn ảnh mới để thay đổi (Chọn tệp → nhấn nút cập nhật)</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            Cập nhật thông tin
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Active Borrows -->
        <div>
            <div class="card">
                <div class="card-header">
                    <h3>Đang mượn</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($data['active_borrows'])): ?>
                        <?php foreach ($data['active_borrows'] as $borrow): ?>
                        <div class="mb-3 p-2" style="border-left: 3px solid #2563eb; background-color: #f1f5f9;">
                            <strong><?= htmlspecialchars($borrow['book_title']) ?></strong>
                            <p class="text-muted mb-0" style="font-size: 0.875rem;">
                                Hạn trả: <?= formatDate($borrow['due_date']) ?>
                            </p>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center">Không có sách đang mượn</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h3>Thống kê</h3>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Sách đang mượn:</strong>
                        <span class="badge badge-info"><?= count($data['active_borrows']) ?></span>
                    </div>
                    <div class="mb-2">
                        <strong>Trạng thái:</strong>
                        <span class="badge badge-success">
                            <?= $data['user']['status'] === 'active' ? 'Hoạt động' : 'Không hoạt động' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-preview {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #667eea;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    display: block;
}

.current-avatar {
    text-align: center;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
}

.mb-2 {
    margin-bottom: 0.5rem;
}

.text-muted {
    color: #6c757d;
}
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
