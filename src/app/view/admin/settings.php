<?php
$pageTitle = 'Cài đặt hệ thống - Admin';
require_once __DIR__ . '/../layouts/admin_header.php';

// Lấy giá trị cài đặt hoặc dùng giá trị mặc định
$settings = $data['settings'] ?? [];
?>

<div class="page-header">
    <h1>Cài đặt hệ thống</h1>
</div>

<!-- Thông tin hệ thống (read-only) -->
<div class="card">
    <div class="card-header">
        <h3>Thông tin hệ thống</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th width="250">Tên hệ thống:</th>
                <td><?= SYSTEM_NAME ?></td>
            </tr>
            <tr>
                <th>Tên trường:</th>
                <td><?= UNIVERSITY_NAME ?></td>
            </tr>
            <tr>
                <th>Phiên bản:</th>
                <td><?= SYSTEM_VERSION ?></td>
            </tr>
            <tr>
                <th>URL gốc:</th>
                <td><?= BASE_URL ?></td>
            </tr>
            <tr>
                <th>Số ngày mượn tối đa:</th>
                <td><?= MAX_BORROW_DAYS ?> ngày</td>
            </tr>
            <tr>
                <th>Số sách mượn tối đa:</th>
                <td><?= MAX_BOOKS_PER_USER ?> cuốn</td>
            </tr>
            <tr>
                <th>Phí phạt quá hạn:</th>
                <td><?= formatMoney(OVERDUE_FINE_PER_DAY) ?>/ngày</td>
            </tr>
        </table>
        
        <div class="alert alert-info mt-3">
            <strong>Lưu ý:</strong> Các thông tin cơ bản trên được cấu hình trong file <code>config/config.php</code>
        </div>
    </div>
</div>

<!-- Form cài đặt hệ thống -->
<form method="POST" action="<?= BASE_URL ?>/admin/settings" id="settingsForm">
    
    <!-- Thông tin liên hệ -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>Thông tin liên hệ</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="contact_email">Email hỗ trợ <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="contact_email" name="contact_email" 
                       value="<?= htmlspecialchars($settings['contact_email'] ?? 'library@tvu.edu.vn') ?>" required>
                <small class="form-text text-muted">Email để sinh viên và thủ thư liên hệ hỗ trợ</small>
            </div>
            
            <div class="form-group">
                <label for="contact_phone">Số điện thoại thư viện <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="contact_phone" name="contact_phone" 
                       value="<?= htmlspecialchars($settings['contact_phone'] ?? '(0294) 3855 246') ?>" required>
                <small class="form-text text-muted">Số điện thoại liên hệ thư viện</small>
            </div>
            
            <div class="form-group">
                <label for="contact_address">Địa chỉ thư viện <span class="text-danger">*</span></label>
                <textarea class="form-control" id="contact_address" name="contact_address" rows="3" required><?= htmlspecialchars($settings['contact_address'] ?? 'Số 126, Nguyễn Thiện Thành, Khóm 4, Phường 5, TP. Trà Vinh') ?></textarea>
                <small class="form-text text-muted">Địa chỉ của thư viện</small>
            </div>
        </div>
    </div>
    
    <!-- Cài đặt ngày giờ -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>Định dạng ngày giờ và múi giờ</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="date_format">Định dạng ngày tháng <span class="text-danger">*</span></label>
                <select class="form-control" id="date_format" name="date_format" required>
                    <option value="d/m/Y" <?= ($settings['date_format'] ?? 'd/m/Y') === 'd/m/Y' ? 'selected' : '' ?>>DD/MM/YYYY (<?= date('d/m/Y') ?>)</option>
                    <option value="m/d/Y" <?= ($settings['date_format'] ?? '') === 'm/d/Y' ? 'selected' : '' ?>>MM/DD/YYYY (<?= date('m/d/Y') ?>)</option>
                    <option value="Y-m-d" <?= ($settings['date_format'] ?? '') === 'Y-m-d' ? 'selected' : '' ?>>YYYY-MM-DD (<?= date('Y-m-d') ?>)</option>
                    <option value="d-m-Y" <?= ($settings['date_format'] ?? '') === 'd-m-Y' ? 'selected' : '' ?>>DD-MM-YYYY (<?= date('d-m-Y') ?>)</option>
                </select>
                <small class="form-text text-muted">Định dạng hiển thị ngày tháng trong toàn hệ thống</small>
            </div>
            
            <div class="form-group">
                <label for="datetime_format">Định dạng ngày giờ <span class="text-danger">*</span></label>
                <select class="form-control" id="datetime_format" name="datetime_format" required>
                    <option value="d/m/Y H:i" <?= ($settings['datetime_format'] ?? 'd/m/Y H:i') === 'd/m/Y H:i' ? 'selected' : '' ?>>DD/MM/YYYY HH:MM (<?= date('d/m/Y H:i') ?>)</option>
                    <option value="m/d/Y h:i A" <?= ($settings['datetime_format'] ?? '') === 'm/d/Y h:i A' ? 'selected' : '' ?>>MM/DD/YYYY HH:MM AM/PM (<?= date('m/d/Y h:i A') ?>)</option>
                    <option value="Y-m-d H:i" <?= ($settings['datetime_format'] ?? '') === 'Y-m-d H:i' ? 'selected' : '' ?>>YYYY-MM-DD HH:MM (<?= date('Y-m-d H:i') ?>)</option>
                    <option value="d-m-Y H:i" <?= ($settings['datetime_format'] ?? '') === 'd-m-Y H:i' ? 'selected' : '' ?>>DD-MM-YYYY HH:MM (<?= date('d-m-Y H:i') ?>)</option>
                </select>
                <small class="form-text text-muted">Định dạng hiển thị ngày và giờ</small>
            </div>
            
            <div class="form-group">
                <label for="time_format">Định dạng giờ <span class="text-danger">*</span></label>
                <select class="form-control" id="time_format" name="time_format" required>
                    <option value="H:i" <?= ($settings['time_format'] ?? 'H:i') === 'H:i' ? 'selected' : '' ?>>24 giờ (<?= date('H:i') ?>)</option>
                    <option value="h:i A" <?= ($settings['time_format'] ?? '') === 'h:i A' ? 'selected' : '' ?>>12 giờ AM/PM (<?= date('h:i A') ?>)</option>
                </select>
                <small class="form-text text-muted">Định dạng hiển thị giờ</small>
            </div>
            
            <div class="form-group">
                <label for="timezone">Múi giờ <span class="text-danger">*</span></label>
                <select class="form-control" id="timezone" name="timezone" required>
                    <option value="Asia/Ho_Chi_Minh" <?= ($settings['timezone'] ?? 'Asia/Ho_Chi_Minh') === 'Asia/Ho_Chi_Minh' ? 'selected' : '' ?>>Việt Nam (UTC+7)</option>
                    <option value="Asia/Bangkok" <?= ($settings['timezone'] ?? '') === 'Asia/Bangkok' ? 'selected' : '' ?>>Bangkok (UTC+7)</option>
                    <option value="Asia/Singapore" <?= ($settings['timezone'] ?? '') === 'Asia/Singapore' ? 'selected' : '' ?>>Singapore (UTC+8)</option>
                    <option value="Asia/Tokyo" <?= ($settings['timezone'] ?? '') === 'Asia/Tokyo' ? 'selected' : '' ?>>Tokyo (UTC+9)</option>
                    <option value="UTC" <?= ($settings['timezone'] ?? '') === 'UTC' ? 'selected' : '' ?>>UTC (UTC+0)</option>
                </select>
                <small class="form-text text-muted">Múi giờ của hệ thống</small>
            </div>
        </div>
    </div>
    
    <!-- Chế độ bảo trì -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>Chế độ bảo trì</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="maintenance_mode" name="maintenance_mode" 
                           <?= ($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' ?>>
                    <label class="custom-control-label" for="maintenance_mode">Bật chế độ bảo trì</label>
                </div>
                <small class="form-text text-muted">
                    Khi bật, sinh viên có thể truy cập website nhưng không thể đăng nhập. Admin và Thủ thư vẫn có thể đăng nhập bình thường.
                </small>
            </div>
            
            <div class="form-group">
                <label for="maintenance_message">Thông báo bảo trì</label>
                <textarea class="form-control" id="maintenance_message" name="maintenance_message" rows="3"><?= htmlspecialchars($settings['maintenance_message'] ?? 'Hệ thống đang trong quá trình bảo trì. Vui lòng quay lại sau.') ?></textarea>
                <small class="form-text text-muted">Thông báo hiển thị cho sinh viên khi chế độ bảo trì được bật</small>
            </div>
        </div>
    </div>
    
    <!-- Cài đặt gia hạn sách -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>Cài đặt gia hạn sách</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="allow_renewal" name="allow_renewal" 
                           <?= ($settings['allow_renewal'] ?? '1') === '1' ? 'checked' : '' ?>>
                    <label class="custom-control-label" for="allow_renewal">Cho phép gia hạn sách</label>
                </div>
                <small class="form-text text-muted">Bật/tắt tính năng gia hạn sách cho sinh viên</small>
            </div>
            
            <div class="form-group">
                <label for="max_renewal_times">Số lần gia hạn tối đa <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="max_renewal_times" name="max_renewal_times" 
                       min="0" max="10" value="<?= htmlspecialchars($settings['max_renewal_times'] ?? '2') ?>" required>
                <small class="form-text text-muted">Số lần gia hạn tối đa cho mỗi cuốn sách (0-10 lần)</small>
            </div>
            
            <div class="form-group">
                <label for="renewal_days">Số ngày gia hạn mỗi lần <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="renewal_days" name="renewal_days" 
                       min="1" max="30" value="<?= htmlspecialchars($settings['renewal_days'] ?? '7') ?>" required>
                <small class="form-text text-muted">Số ngày được thêm vào mỗi lần gia hạn (1-30 ngày)</small>
            </div>
        </div>
    </div>
    
    <!-- Nút lưu -->
    <div class="mt-4 mb-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Lưu cài đặt
        </button>
        <a href="<?= url('admin/dashboard') ?>" class="btn btn-secondary">
            <i class="fas fa-times"></i> Hủy
        </a>
    </div>
</form>

<script>
// Toggle các trường liên quan khi bật/tắt gia hạn sách
document.getElementById('allow_renewal').addEventListener('change', function() {
    const isChecked = this.checked;
    document.getElementById('max_renewal_times').disabled = !isChecked;
    document.getElementById('renewal_days').disabled = !isChecked;
});

// Trigger khi tải trang
document.getElementById('allow_renewal').dispatchEvent(new Event('change'));
</script>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
