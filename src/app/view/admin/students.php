<?php
$pageTitle = 'Quản lý sinh viên - Admin';
require_once __DIR__ . '/../layouts/admin_header.php';
?>

<div class="page-header">
        <h1>Quản lý sinh viên</h1>
        <div>
            <a href="<?= BASE_URL ?>/admin/add-student" class="btn btn-primary">
                ➕ Thêm sinh viên
            </a>
        </div>
    </div>
    
    <!-- Search -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="<?= BASE_URL ?>/admin/students">
                <div class="row">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Tìm kiếm theo tên, mã SV, email..." 
                               value="<?= $data['search'] ?? '' ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mã SV</th>
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
                        <?php foreach ($data['students'] as $student): ?>
                        <tr>
                            <td><?= $student['id'] ?></td>
                            <td><?= $student['student_code'] ?></td>
                            <td><?= $student['username'] ?></td>
                            <td><?= $student['full_name'] ?></td>
                            <td><?= $student['email'] ?></td>
                            <td><?= $student['phone'] ?? '-' ?></td>
                            <td>
                                <?php if ($student['status'] === 'active'): ?>
                                    <span class="badge badge-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Khóa</span>
                                <?php endif; ?>
                            </td>
                            <td><?= formatDate($student['created_at']) ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>/admin/edit-student/<?= $student['id'] ?>" 
                                   class="btn btn-sm btn-warning">Sửa</a>
                                <a href="<?= BASE_URL ?>/admin/delete-student/<?= $student['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Bạn có chắc muốn xóa sinh viên này?')">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($data['pagination']['total_pages'] > 1): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <?php if ($data['pagination']['has_previous']): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $data['pagination']['current_page'] - 1 ?><?= $data['search'] ? '&search=' . urlencode($data['search']) : '' ?>">
                            Trước
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                    <li class="page-item <?= $i === $data['pagination']['current_page'] ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?><?= $data['search'] ? '&search=' . urlencode($data['search']) : '' ?>">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if ($data['pagination']['has_next']): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $data['pagination']['current_page'] + 1 ?><?= $data['search'] ? '&search=' . urlencode($data['search']) : '' ?>">
                            Sau
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
