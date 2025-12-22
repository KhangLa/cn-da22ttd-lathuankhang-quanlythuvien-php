<?php
$pageTitle = 'Quản lý phiếu phạt - Thủ thư';
require_once __DIR__ . '/../layouts/librarian_header.php';

$fines = $data['fines'] ?? [];
$pagination = $data['pagination'] ?? '';
$selectedStatus = $data['selected_status'] ?? '';
$unpaidCount = $data['unpaid_count'] ?? 0;
$paidCount = $data['paid_count'] ?? 0;

// Helper function
function getStatusBadge($status) {
    $badges = [
        'unpaid' => '<span class="badge badge-danger">Chưa thanh toán</span>',
        'paid' => '<span class="badge badge-success">Đã thanh toán</span>',
        'waived' => '<span class="badge badge-info">Đã miễn</span>'
    ];
    return $badges[$status] ?? '<span class="badge badge-secondary">Không rõ</span>';
}

function getFineTypeText($type) {
    $types = [
        'overdue' => 'Trả trễ',
        'damaged' => 'Sách hư hỏng',
        'lost' => 'Mất sách',
        'other' => 'Khác'
    ];
    return $types[$type] ?? $type;
}
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>💰 Quản lý phiếu phạt</h2>
            <p class="text-muted">Quản lý các khoản phạt của sinh viên</p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/librarian/create-fine" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tạo phiếu phạt mới
            </a>
        </div>
    </div>
    
    <!-- Stats -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title"><?= $unpaidCount ?></h5>
                    <p class="card-text">Chưa thanh toán</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title"><?= $paidCount ?></h5>
                    <p class="card-text">Đã thanh toán</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="<?= BASE_URL ?>/librarian/fines" class="form-inline">
                <label class="mr-2">Lọc theo trạng thái:</label>
                <select name="status" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="">Tất cả</option>
                    <option value="unpaid" <?= $selectedStatus === 'unpaid' ? 'selected' : '' ?>>Chưa thanh toán</option>
                    <option value="paid" <?= $selectedStatus === 'paid' ? 'selected' : '' ?>>Đã thanh toán</option>
                    <option value="waived" <?= $selectedStatus === 'waived' ? 'selected' : '' ?>>Đã miễn</option>
                </select>
                <?php if ($selectedStatus): ?>
                    <a href="<?= BASE_URL ?>/librarian/fines" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Xóa lọc
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <!-- Fines List -->
    <?php if (empty($fines)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Chưa có phiếu phạt nào
            <?= $selectedStatus ? 'với trạng thái này' : '' ?>.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Sinh viên</th>
                                <th>Loại phạt</th>
                                <th>Số tiền</th>
                                <th>Lý do</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th width="150">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fines as $index => $fine): ?>
                                <tr class="<?= $fine['status'] === 'unpaid' ? 'table-danger' : '' ?>">
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($fine['student_name']) ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($fine['student_code']) ?></small>
                                    </td>
                                    <td><?= getFineTypeText($fine['fine_type']) ?></td>
                                    <td>
                                        <strong class="text-danger"><?= number_format($fine['amount']) ?> VNĐ</strong>
                                    </td>
                                    <td>
                                        <small><?= nl2br(htmlspecialchars(substr($fine['reason'], 0, 50))) ?>
                                        <?php if (strlen($fine['reason']) > 50): ?>...<?php endif; ?>
                                        </small>
                                    </td>
                                    <td><?= getStatusBadge($fine['status']) ?></td>
                                    <td>
                                        <?= date('d/m/Y', strtotime($fine['created_at'])) ?><br>
                                        <small class="text-muted"><?= date('H:i', strtotime($fine['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/librarian/view-fine/<?= $fine['id'] ?>" 
                                           class="btn btn-sm btn-primary" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <?php if ($pagination && $pagination['total_pages'] > 1): ?>
            <div class="mt-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($pagination['has_previous']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $pagination['current_page'] - 1 ?><?= $selectedStatus ? '&status=' . $selectedStatus : '' ?>">
                                    « Trước
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                            <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?><?= $selectedStatus ? '&status=' . $selectedStatus : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['has_next']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $pagination['current_page'] + 1 ?><?= $selectedStatus ? '&status=' . $selectedStatus : '' ?>">
                                    Sau »
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/librarian_footer.php'; ?>
