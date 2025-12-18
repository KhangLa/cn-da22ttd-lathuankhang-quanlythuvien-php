<?php
$pageTitle = (isset($data['student']) ? 'Sửa' : 'Thêm') . ' sinh viên - Admin';
$isEdit = isset($data['student']);
require_once __DIR__ . '/../layouts/admin_header.php';
?>

<div class="page-header">
        <h1><?= $isEdit ? 'Sửa thông tin' : 'Thêm' ?> sinh viên</h1>
        <a href="<?= BASE_URL ?>/admin/students" class="btn btn-secondary">← Quay lại</a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tên đăng nhập <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" 
                                   value="<?= $data['student']['username'] ?? '' ?>"
                                   <?= $isEdit ? 'readonly' : 'required' ?>>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?= $data['student']['email'] ?? '' ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" 
                                   value="<?= $data['student']['full_name'] ?? '' ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mã sinh viên <span class="text-danger">*</span></label>
                            <input type="text" name="student_code" class="form-control" 
                                   value="<?= $data['student']['student_code'] ?? '' ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mã lớp</label>
                            <input type="text" name="class_code" class="form-control" 
                                   value="<?= $data['student']['class_code'] ?? '' ?>"
                                   placeholder="Ví dụ: DA22TTD">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" 
                                   value="<?= $data['student']['phone'] ?? '' ?>"
                                   placeholder="Ví dụ: 0123456789">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select name="status" class="form-control">
                                <option value="active" <?= isset($data['student']) && $data['student']['status'] === 'active' ? 'selected' : '' ?>>
                                    Hoạt động
                                </option>
                                <option value="inactive" <?= isset($data['student']) && $data['student']['status'] === 'inactive' ? 'selected' : '' ?>>
                                    Khóa
                                </option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Địa chỉ</label>
                            <textarea name="address" class="form-control" rows="4"><?= $data['student']['address'] ?? '' ?></textarea>
                        </div>
                    </div>
                </div>
                
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
                    <a href="<?= BASE_URL ?>/admin/students" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
