<?php
$pageTitle = $data['book']['title'] . ' - Chi tiết sách';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="container">
    <div class="page-header">
        <a href="javascript:history.back()" class="btn btn-secondary">← Quay lại</a>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <?php if (!empty($data['book']['cover_image'])): ?>
                        <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($data['book']['cover_image']) ?>" 
                             alt="<?= htmlspecialchars($data['book']['title']) ?>" 
                             class="img-fluid rounded shadow mb-3">
                    <?php else: ?>
                        <div class="book-placeholder">
                            <i class="book-icon">📚</i>
                            <p>Không có ảnh bìa</p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="book-status mt-3">
                        <?php if ($data['book']['available_quantity'] > 0): ?>
                            <span class="badge badge-success badge-lg">
                                ✅ Có sẵn (<?= $data['book']['available_quantity'] ?> cuốn)
                            </span>
                        <?php else: ?>
                            <span class="badge badge-danger badge-lg">
                                ❌ Hết sách
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2><?= $data['book']['title'] ?></h2>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Tác giả:</th>
                            <td><?= $data['book']['author'] ?></td>
                        </tr>
                        <?php if (!empty($data['book']['publisher'])): ?>
                        <tr>
                            <th>Nhà xuất bản:</th>
                            <td><?= $data['book']['publisher'] ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($data['book']['publish_year'])): ?>
                        <tr>
                            <th>Năm xuất bản:</th>
                            <td><?= $data['book']['publish_year'] ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($data['book']['isbn'])): ?>
                        <tr>
                            <th>ISBN:</th>
                            <td><code><?= $data['book']['isbn'] ?></code></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($data['book']['category_name'])): ?>
                        <tr>
                            <th>Danh mục:</th>
                            <td>
                                <span class="badge badge-primary"><?= $data['book']['category_name'] ?></span>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Tổng số lượng:</th>
                            <td><?= $data['book']['quantity'] ?> cuốn</td>
                        </tr>
                        <tr>
                            <th>Còn lại:</th>
                            <td><strong><?= $data['book']['available_quantity'] ?></strong> cuốn</td>
                        </tr>
                        <?php if (!empty($data['book']['location'])): ?>
                        <tr>
                            <th>Vị trí:</th>
                            <td>📍 <?= $data['book']['location'] ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                    
                    <?php if (!empty($data['book']['description'])): ?>
                    <hr>
                    <h4>Mô tả</h4>
                    <p class="text-justify"><?= nl2br($data['book']['description']) ?></p>
                    <?php endif; ?>
                    
                    <?php if ($data['book']['available_quantity'] > 0): ?>
                    <hr>
                    <div class="borrow-section">
                        <h4>📖 Mượn sách</h4>
                        <?php if (isLoggedIn() && isStudent()): ?>
                            <p class="text-muted">Thông tin mượn sách</p>
                            
                            <div class="borrow-info-table">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="150">Sách mượn:</th>
                                        <td><strong><?= htmlspecialchars($data['book']['title']) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Ngày mượn:</th>
                                        <td><?= date('d/m/Y') ?> (Hôm nay)</td>
                                    </tr>
                                    <tr>
                                        <th>Hạn trả:</th>
                                        <td><?= date('d/m/Y', strtotime('+' . MAX_BORROW_DAYS . ' days')) ?> 
                                            <small class="text-muted">(<?= MAX_BORROW_DAYS ?> ngày)</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Trạng thái:</th>
                                        <td><span class="badge badge-info">Chờ xác nhận</span></td>
                                    </tr>
                                    <tr>
                                        <th>Lưu ý:</th>
                                        <td class="text-danger">
                                            <small>
                                                • Vui lòng đến thư viện để nhận sách<br>
                                                • Trả sách đúng hạn tránh bị phạt<br>
                                                • Phí phạt: <?= formatMoney(OVERDUE_FINE_PER_DAY) ?>/ngày
                                            </small>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <form method="POST" action="<?= BASE_URL ?>/student/request-borrow" 
                                  onsubmit="return confirm('Xác nhận mượn sách \'<?= htmlspecialchars($data['book']['title']) ?>\'?')">
                                <input type="hidden" name="book_id" value="<?= $data['book']['id'] ?>">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    ✅ Xác nhận mượn sách
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <strong>ℹ️ Bạn cần đăng nhập để mượn sách</strong><br>
                                <p class="mb-0">Vui lòng đăng nhập với tài khoản sinh viên để có thể mượn sách.</p>
                            </div>
                            <a href="<?= BASE_URL ?>/auth/login?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-primary btn-lg w-100">
                                🔐 Đăng nhập để mượn sách
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php elseif ($data['book']['available_quantity'] <= 0): ?>
                    <hr>
                    <div class="alert alert-warning">
                        <strong>⚠️ Sách hiện đang hết</strong><br>
                        Vui lòng quay lại sau hoặc liên hệ thủ thư để biết thêm thông tin.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Sách liên quan -->
            <?php if (isset($data['related_books']) && count($data['related_books']) > 0): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Sách cùng danh mục</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($data['related_books'] as $related): ?>
                            <?php if ($related['id'] != $data['book']['id']): ?>
                            <div class="col-md-6 mb-3">
                                <div class="book-item-small">
                                    <h5>
                                        <a href="<?= BASE_URL ?>/book/detail/<?= $related['id'] ?>">
                                            <?= $related['title'] ?>
                                        </a>
                                    </h5>
                                    <p class="text-muted small"><?= $related['author'] ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.book-placeholder {
    background: #f8f9fa;
    padding: 60px 20px;
    border-radius: 8px;
}
.book-icon {
    font-size: 80px;
}
.badge-lg {
    font-size: 16px;
    padding: 10px 20px;
}
.book-item-small h5 {
    font-size: 16px;
    margin-bottom: 5px;
}
.book-item-small h5 a {
    color: #333;
    text-decoration: none;
}
.book-item-small h5 a:hover {
    color: #007bff;
}
.borrow-section {
    background: #f0f9ff;
    padding: 1.5rem;
    border-radius: 0.5rem;
    border: 2px solid #3b82f6;
}
.borrow-section h4 {
    color: #1e40af;
    margin-bottom: 0.5rem;
}
.borrow-section .btn {
    margin-top: 1rem;
}
.borrow-info-table {
    background: white;
    padding: 1rem;
    border-radius: 0.5rem;
    margin: 1rem 0;
}
.borrow-info-table .table {
    margin-bottom: 0;
}
.borrow-info-table th {
    font-weight: 600;
    color: #64748b;
}
.w-100 {
    width: 100%;
}
.d-block {
    display: block;
}
</style>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
