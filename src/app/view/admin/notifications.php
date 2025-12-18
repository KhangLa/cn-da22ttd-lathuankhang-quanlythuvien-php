<?php
$pageTitle = 'Gửi thông báo - Admin';
require_once __DIR__ . '/../layouts/admin_header.php';
require_once __DIR__ . '/../../models/Database.php';

// Lấy danh sách thủ thư
$db = new Database();
$librarians = $db->fetchAll("SELECT id, full_name, email FROM users WHERE role = 'librarian' AND status = 'active' ORDER BY full_name");
?>

<div class="page-header">
    <h1>📢 Gửi thông báo đến thủ thư</h1>
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/admin/dashboard">Dashboard</a>
        <span>/</span>
        <span>Gửi thông báo</span>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3>📝 Soạn thông báo</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="recipient">Người nhận <span class="required">*</span></label>
                        <select name="recipient" id="recipient" class="form-control" required onchange="toggleLibrarianSelect()">
                            <option value="">-- Chọn người nhận --</option>
                            <option value="all">Tất cả thủ thư</option>
                            <option value="specific">Thủ thư cụ thể</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="librarian-select-group" style="display: none;">
                        <label for="librarian_id">Chọn thủ thư <span class="required">*</span></label>
                        <select name="librarian_id" id="librarian_id" class="form-control">
                            <option value="">-- Chọn thủ thư --</option>
                            <?php foreach ($librarians as $librarian): ?>
                                <option value="<?= $librarian['id'] ?>">
                                    <?= htmlspecialchars($librarian['full_name']) ?> (<?= htmlspecialchars($librarian['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="type">Loại thông báo <span class="required">*</span></label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="info">Thông tin</option>
                            <option value="warning">Cảnh báo</option>
                            <option value="success">Thành công</option>
                            <option value="danger">Quan trọng</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="title">Tiêu đề <span class="required">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" 
                               placeholder="Nhập tiêu đề thông báo" required maxlength="200">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Nội dung <span class="required">*</span></label>
                        <textarea name="message" id="message" class="form-control" rows="8" 
                                  placeholder="Nhập nội dung thông báo" required></textarea>
                        <small class="form-text text-muted">
                            Nhập nội dung chi tiết của thông báo
                        </small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Gửi thông báo
                        </button>
                        <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3>ℹ️ Hướng dẫn</h3>
            </div>
            <div class="card-body">
                <h4>Các loại thông báo:</h4>
                <ul>
                    <li><strong>Thông tin:</strong> Thông báo thông thường</li>
                    <li><strong>Cảnh báo:</strong> Thông báo cần chú ý</li>
                    <li><strong>Thành công:</strong> Thông báo tích cực</li>
                    <li><strong>Quan trọng:</strong> Thông báo khẩn cấp</li>
                </ul>
                
                <h4 class="mt-3">Lưu ý:</h4>
                <ul>
                    <li>Tiêu đề không quá 200 ký tự</li>
                    <li>Nội dung nên rõ ràng, dễ hiểu</li>
                    <li>Chọn loại thông báo phù hợp với nội dung</li>
                    <li>Kiểm tra kỹ trước khi gửi</li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h3>👥 Danh sách thủ thư</h3>
            </div>
            <div class="card-body">
                <?php if (empty($librarians)): ?>
                    <p class="text-muted">Chưa có thủ thư nào</p>
                <?php else: ?>
                    <div class="librarian-list">
                        <?php foreach ($librarians as $librarian): ?>
                            <div class="librarian-item">
                                <i class="fas fa-user"></i>
                                <div>
                                    <strong><?= htmlspecialchars($librarian['full_name']) ?></strong>
                                    <small class="text-muted d-block"><?= htmlspecialchars($librarian['email']) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p class="text-muted mt-2">
                        <small>Tổng số: <strong><?= count($librarians) ?></strong> thủ thư</small>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 8px 8px 0 0;
}

.card-header h3 {
    margin: 0;
    font-size: 1.2rem;
}

.card-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
    color: #333;
}

.required {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

textarea.form-control {
    resize: vertical;
    min-height: 150px;
}

.form-actions {
    margin-top: 30px;
    display: flex;
    gap: 10px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

.librarian-list {
    max-height: 300px;
    overflow-y: auto;
}

.librarian-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    border-bottom: 1px solid #f0f0f0;
}

.librarian-item:last-child {
    border-bottom: none;
}

.librarian-item i {
    color: #667eea;
    font-size: 20px;
}

.breadcrumb {
    display: flex;
    gap: 8px;
    align-items: center;
    margin-top: 10px;
}

.breadcrumb a {
    color: #667eea;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.text-muted {
    color: #6c757d;
}

.d-block {
    display: block;
}

.mt-2 {
    margin-top: 0.5rem;
}

.mt-3 {
    margin-top: 1rem;
}

h4 {
    font-size: 1rem;
    margin-bottom: 10px;
    color: #333;
}

ul {
    padding-left: 20px;
    margin: 10px 0;
}

ul li {
    margin-bottom: 8px;
    line-height: 1.5;
}
</style>

<script>
function toggleLibrarianSelect() {
    const recipient = document.getElementById('recipient').value;
    const librarianSelectGroup = document.getElementById('librarian-select-group');
    const librarianSelect = document.getElementById('librarian_id');
    
    if (recipient === 'specific') {
        librarianSelectGroup.style.display = 'block';
        librarianSelect.required = true;
    } else {
        librarianSelectGroup.style.display = 'none';
        librarianSelect.required = false;
        librarianSelect.value = '';
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
