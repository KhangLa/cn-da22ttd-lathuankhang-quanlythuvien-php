<?php
$pageTitle = 'Dashboard Admin - Thư viện TVU';
require_once __DIR__ . '/../layouts/admin_header.php';
?>

<div class="page-header">
    <h1>📊 Dashboard Admin</h1>
    <p>Chào mừng, <strong><?= getCurrentUser()['full_name'] ?></strong>!</p>
</div>

<!-- Thống kê tổng quan -->
<div class="dashboard-stats">
        <div class="stat-card stat-primary">
            <div class="stat-icon">📚</div>
            <div class="stat-info">
                <h3><?= number_format($data['total_books']) ?></h3>
                <p>Tổng số sách</p>
            </div>
        </div>
        
        <div class="stat-card stat-success">
            <div class="stat-icon">✅</div>
            <div class="stat-info">
                <h3><?= number_format($data['available_books']) ?></h3>
                <p>Sách có sẵn</p>
            </div>
        </div>
        
        <div class="stat-card stat-primary">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3><?= number_format($data['total_students']) ?></h3>
                <p>Sinh viên</p>
            </div>
        </div>
        
        <div class="stat-card stat-success">
            <div class="stat-icon">📖</div>
            <div class="stat-info">
                <h3><?= number_format($data['active_borrows']) ?></h3>
                <p>Đang mượn</p>
            </div>
        </div>
        
        <div class="stat-card stat-warning">
            <div class="stat-icon">⚠️</div>
            <div class="stat-info">
                <h3><?= number_format($data['overdue_borrows']) ?></h3>
                <p>Quá hạn</p>
            </div>
        </div>
        
        <div class="stat-card stat-primary">
            <div class="stat-icon">📑</div>
            <div class="stat-info">
                <h3><?= number_format($data['total_categories']) ?></h3>
                <p>Danh mục</p>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <!-- Phiếu mượn gần đây -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3>Phiếu mượn gần đây</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mã SV</th>
                                    <th>Sinh viên</th>
                                    <th>Sách</th>
                                    <th>Ngày mượn</th>
                                    <th>Hạn trả</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['recent_borrows'] as $borrow): ?>
                                <tr>
                                    <td><?= $borrow['student_code'] ?></td>
                                    <td><?= $borrow['user_name'] ?></td>
                                    <td><?= $borrow['book_title'] ?></td>
                                    <td><?= formatDate($borrow['borrow_date']) ?></td>
                                    <td><?= formatDate($borrow['due_date']) ?></td>
                                    <td>
                                        <?php if ($borrow['status'] === 'borrowed'): ?>
                                            <span class="badge badge-info">Đang mượn</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Đã trả</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sách quá hạn -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header alert-warning">
                    <h3>⚠️ Sách quá hạn (<?= count($data['overdue_books']) ?>)</h3>
                </div>
                <div class="card-body">
                    <?php if (count($data['overdue_books']) > 0): ?>
                        <div class="overdue-simple-list">
                            <?php 
                            // Group by user_id to avoid duplicates
                            $overdueByUser = [];
                            foreach ($data['overdue_books'] as $overdue) {
                                $userId = $overdue['user_id'];
                                if (!isset($overdueByUser[$userId])) {
                                    $overdueByUser[$userId] = [
                                        'user_id' => $userId,
                                        'user_name' => $overdue['user_name'],
                                        'student_code' => $overdue['student_code'],
                                        'count' => 0
                                    ];
                                }
                                $overdueByUser[$userId]['count']++;
                            }
                            
                            // Display up to 10 students
                            $count = 0;
                            foreach (array_slice($overdueByUser, 0, 10) as $student): 
                                $count++;
                            ?>
                            <div class="overdue-simple-item" onclick="showStudentDetail(<?= $student['user_id'] ?>)">
                                <div class="student-info-row">
                                    <div class="student-basic">
                                        <span class="student-number"><?= $count ?></span>
                                        <div>
                                            <strong><?= $student['user_name'] ?></strong>
                                            <?php if (!empty($student['student_code'])): ?>
                                                <br><small class="text-muted"><?= $student['student_code'] ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="overdue-badge">
                                        <span class="badge badge-danger"><?= $student['count'] ?> sách</span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($overdueByUser) > 10): ?>
                            <div class="mt-3 text-center">
                                <a href="<?= BASE_URL ?>/librarian/borrows?status=overdue" class="btn btn-sm btn-warning">
                                    Xem tất cả (<?= count($overdueByUser) ?> sinh viên)
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <div class="mb-2" style="font-size: 3rem;">🎉</div>
                            <p class="text-muted">Không có sách quá hạn</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Sách phổ biến -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3>Sách phổ biến</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <?php foreach ($data['popular_books'] as $book): ?>
                        <li class="mb-2">
                            <strong><?= $book['title'] ?></strong><br>
                            <small class="text-muted">
                                <?= $book['author'] ?> - 
                                Mượn: <?= $book['borrow_count'] ?? 0 ?> lần
                            </small>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3>Truy cập nhanh</h3>
        <div class="action-buttons">
            <a href="<?= BASE_URL ?>/admin/students" class="btn btn-primary">
                👥 Quản lý sinh viên
            </a>
            <a href="<?= BASE_URL ?>/admin/librarians" class="btn btn-primary">
                👨‍💼 Quản lý thủ thư
            </a>
            <a href="<?= BASE_URL ?>/librarian/books" class="btn btn-primary">
                📚 Quản lý sách
            </a>
            <a href="<?= BASE_URL ?>/librarian/borrows" class="btn btn-primary">
                📖 Quản lý mượn/trả
            </a>
            <a href="<?= BASE_URL ?>/admin/notifications" class="btn btn-warning">
                📢 Gửi thông báo thủ thư
            </a>
            <a href="<?= BASE_URL ?>/admin/reports" class="btn btn-info">
                📊 Báo cáo thống kê
            </a>
        </div>
    </div>

<!-- Modal chi tiết sinh viên -->
<div class="modal" id="studentDetailModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📋 Thông tin chi tiết sinh viên quá hạn</h3>
                <button type="button" class="btn-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="studentDetailContent">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Đóng</button>
            </div>
        </div>
    </div>
</div>

<?php
// Prepare student overdue data for JavaScript
$studentOverdueData = [];
foreach ($data['overdue_books'] as $overdue) {
    $userId = $overdue['user_id'];
    if (!isset($studentOverdueData[$userId])) {
        $studentOverdueData[$userId] = [
            'user_id' => $userId,
            'user_name' => $overdue['user_name'],
            'student_code' => $overdue['student_code'] ?? '',
            'email' => $overdue['email'] ?? '',
            'phone' => $overdue['phone'] ?? '',
            'books' => []
        ];
    }
    $studentOverdueData[$userId]['books'][] = [
        'book_title' => $overdue['book_title'],
        'borrow_date' => formatDate($overdue['borrow_date']),
        'due_date' => formatDate($overdue['due_date']),
        'overdue_days' => $overdue['overdue_days']
    ];
}
?>

<script>
// Embed PHP data into JavaScript
const studentOverdueData = <?= json_encode(array_values($studentOverdueData)) ?>;

function showStudentDetail(userId) {
    const modal = document.getElementById('studentDetailModal');
    const content = document.getElementById('studentDetailContent');
    
    // Show modal
    modal.style.display = 'flex';
    
    // Find student data
    const student = studentOverdueData.find(s => s.user_id == userId);
    
    if (!student) {
        content.innerHTML = '<div class="alert alert-danger">Không tìm thấy thông tin sinh viên</div>';
        return;
    }
    
    // Build books table HTML
    let booksHtml = '';
    if (student.books && student.books.length > 0) {
        booksHtml = `
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên sách</th>
                        <th>Ngày mượn</th>
                        <th>Hạn trả</th>
                        <th>Quá hạn</th>
                    </tr>
                </thead>
                <tbody>`;
        student.books.forEach((book, index) => {
            booksHtml += `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${book.book_title}</strong></td>
                    <td>${book.borrow_date}</td>
                    <td>${book.due_date}</td>
                    <td><span class="badge badge-danger">${book.overdue_days} ngày</span></td>
                </tr>`;
        });
        booksHtml += `
                </tbody>
            </table>
            <div class="alert alert-warning">
                <strong>⚠️ Tổng số sách quá hạn:</strong> ${student.books.length} cuốn
            </div>
        `;
    }
    
    // Display student information
    content.innerHTML = `
        <div class="student-detail-view">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="student-info-header">
                        <div class="student-avatar-placeholder-large">👤</div>
                        <div class="student-main-info">
                            <h4>${student.user_name}</h4>
                            <p class="mb-1"><strong>Mã SV:</strong> <span class="badge badge-primary">${student.student_code}</span></p>
                            <p class="mb-1"><strong>📧 Email:</strong> <a href="mailto:${student.email}">${student.email}</a></p>
                            <p class="mb-0"><strong>📞 Số điện thoại:</strong> <a href="tel:${student.phone}">${student.phone || 'Chưa cập nhật'}</a></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <h5 class="mb-3">📚 Danh sách sách quá hạn</h5>
            ${booksHtml}
        </div>
    `;
}

function closeModal() {
    document.getElementById('studentDetailModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('studentDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
