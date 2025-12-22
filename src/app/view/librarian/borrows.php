<?php
$pageTitle = 'Quản lý mượn/trả sách - Thủ thư';
require_once __DIR__ . '/../layouts/librarian_header.php';
?>

<div class="page-header">
    <h1>📖 Quản lý mượn/trả sách</h1>
    <a href="<?= BASE_URL ?>/librarian/create-borrow" class="btn btn-primary">➕ Tạo phiếu mượn</a>
</div>
    
    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <select name="status" class="form-control">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="borrowed" <?= isset($data['selected_status']) && $data['selected_status'] === 'borrowed' ? 'selected' : '' ?>>
                                Đang mượn
                            </option>
                            <option value="returned" <?= isset($data['selected_status']) && $data['selected_status'] === 'returned' ? 'selected' : '' ?>>
                                Đã trả
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Lọc</button>
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
                            <th>Sinh viên</th>
                            <th>Sách</th>
                            <th>Ngày mượn</th>
                            <th>Hạn trả</th>
                            <th>Ngày trả</th>
                            <th>Phạt</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['borrows'] as $borrow): ?>
                        <?php
                            $isOverdue = $borrow['status'] === 'borrowed' && strtotime($borrow['due_date']) < time();
                            $overdueDays = $isOverdue ? calculateOverdueDays($borrow['due_date']) : 0;
                        ?>
                        <tr class="<?= $isOverdue ? 'table-warning' : '' ?>">
                            <td><?= $borrow['id'] ?></td>
                            <td><?= $borrow['student_code'] ?></td>
                            <td><?= $borrow['user_name'] ?></td>
                            <td><?= $borrow['book_title'] ?></td>
                            <td><?= formatDate($borrow['borrow_date']) ?></td>
                            <td><?= formatDate($borrow['due_date']) ?></td>
                            <td><?= $borrow['return_date'] ? formatDate($borrow['return_date']) : '-' ?></td>
                            <td>
                                <?php if ($borrow['fine_amount'] > 0): ?>
                                    <span class="text-danger"><?= formatMoney($borrow['fine_amount']) ?></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($borrow['status'] === 'borrowed'): ?>
                                    <?php if ($isOverdue): ?>
                                        <span class="badge badge-danger">Quá hạn <?= $overdueDays ?> ngày</span>
                                    <?php else: ?>
                                        <span class="badge badge-info">Đang mượn</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-success">Đã trả</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($borrow['status'] === 'borrowed'): ?>
                                    <a href="<?= BASE_URL ?>/librarian/return-book/<?= $borrow['id'] ?>" 
                                       class="btn btn-sm btn-success">Trả sách</a>
                                <?php endif; ?>
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
                        <a class="page-link" href="?page=<?= $data['pagination']['current_page'] - 1 ?><?= $data['selected_status'] ? '&status=' . $data['selected_status'] : '' ?>">
                            Trước
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= min(10, $data['pagination']['total_pages']); $i++): ?>
                    <li class="page-item <?= $i === $data['pagination']['current_page'] ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?><?= $data['selected_status'] ? '&status=' . $data['selected_status'] : '' ?>">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if ($data['pagination']['has_next']): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $data['pagination']['current_page'] + 1 ?><?= $data['selected_status'] ? '&status=' . $data['selected_status'] : '' ?>">
                            Sau
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/librarian_footer.php'; ?>
