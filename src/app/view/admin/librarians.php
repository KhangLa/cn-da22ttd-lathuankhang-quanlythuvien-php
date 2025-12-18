<?php
$pageTitle = 'Quản lý thủ thư - Admin';
require_once __DIR__ . '/../layouts/admin_header.php';
?>

<div class="page-header">
        <h1>Quản lý thủ thư</h1>
        <div>
            <a href="<?= BASE_URL ?>/admin/notifications" class="btn btn-warning">📢 Gửi thông báo</a>
            <a href="<?= BASE_URL ?>/admin/add-librarian" class="btn btn-primary">➕ Thêm thủ thư</a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên đăng nhập</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>SĐT</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['librarians'] as $librarian): ?>
                        <tr>
                            <td><?= $librarian['id'] ?></td>
                            <td><?= $librarian['username'] ?></td>
                            <td><?= $librarian['full_name'] ?></td>
                            <td><?= $librarian['email'] ?></td>
                            <td><?= $librarian['phone'] ?? '-' ?></td>
                            <td>
                                <?php if ($librarian['status'] === 'active'): ?>
                                    <span class="badge badge-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Khóa</span>
                                <?php endif; ?>
                            </td>
                            <td><?= formatDate($librarian['created_at']) ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>/admin/edit-librarian/<?= $librarian['id'] ?>" 
                                   class="btn btn-sm btn-warning">Sửa</a>
                                <a href="<?= BASE_URL ?>/admin/delete-librarian/<?= $librarian['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Bạn có chắc muốn xóa thủ thư này?')">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
