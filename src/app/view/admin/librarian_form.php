<?php
$pageTitle = (isset($data['librarian']) ? 'Sửa' : 'Thêm') . ' thủ thư - Admin';
$isEdit = isset($data['librarian']);
require_once __DIR__ . '/../layouts/admin_header.php';
?>

<div class="page-header">
        <h1><?= $isEdit ? 'Sửa thông tin' : 'Thêm' ?> thủ thư</h1>
        <a href="<?= BASE_URL ?>/admin/librarians" class="btn btn-secondary">← Quay lại</a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tên đăng nhập <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" 
                                   value="<?= $data['librarian']['username'] ?? '' ?>"
                                   <?= $isEdit ? 'readonly' : 'required' ?>>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?= $data['librarian']['email'] ?? '' ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" 
                                   value="<?= $data['librarian']['full_name'] ?? '' ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" 
                                   value="<?= $data['librarian']['phone'] ?? '' ?>">
                        </div>
                    </div>
                </div>
                
                <?php if ($isEdit): ?>
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="active" <?= $data['librarian']['status'] === 'active' ? 'selected' : '' ?>>
                            Hoạt động
                        </option>
                        <option value="inactive" <?= $data['librarian']['status'] === 'inactive' ? 'selected' : '' ?>>
                            Khóa
                        </option>
                    </select>
                </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Mật khẩu <?= $isEdit ? '(Để trống nếu không đổi)' : '<span class="text-danger">*</span>' ?></label>
                    <input type="password" name="password" class="form-control" 
                           <?= $isEdit ? '' : 'required' ?>>
                    <?php if (!$isEdit): ?>
                    <small class="form-text text-muted">Tối thiểu 6 ký tự</small>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        💾 <?= $isEdit ? 'Cập nhật' : 'Thêm mới' ?>
                    </button>
                    <a href="<?= BASE_URL ?>/admin/librarians" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
