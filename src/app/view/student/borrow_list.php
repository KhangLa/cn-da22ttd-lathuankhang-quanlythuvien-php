<?php 
$pageTitle = 'Lịch sử mượn sách - Thư viện TVU';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container" style="padding: 2rem 0;">
    <div class="d-flex justify-between align-center mb-4">
        <h1>Lịch sử mượn sách</h1>
    </div>
    
    <?php if (!empty($data['borrows'])): ?>
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Sách</th>
                    <th>Ngày mượn</th>
                    <th>Hạn trả</th>
                    <th>Ngày trả</th>
                    <th>Trạng thái</th>
                    <th>Phạt</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['borrows'] as $index => $borrow): ?>
                <tr>
                    <td><?= ($data['pagination']['current_page'] - 1) * 10 + $index + 1 ?></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <?php if ($borrow['cover_image']): ?>
                            <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($borrow['cover_image']) ?>" 
                                 alt="Cover" style="width: 50px; height: 70px; object-fit: cover; border-radius: 4px;">
                            <?php endif; ?>
                            <div>
                                <strong><?= htmlspecialchars($borrow['book_title']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($borrow['book_author'] ?: 'Không rõ tác giả') ?></small>
                            </div>
                        </div>
                    </td>
                    <td><?= formatDate($borrow['borrow_date']) ?></td>
                    <td>
                        <?= formatDate($borrow['due_date']) ?>
                        <?php
                        // Kiểm tra quá hạn
                        if ($borrow['status'] === 'borrowed' && strtotime($borrow['due_date']) < time()) {
                            $overdueDays = floor((time() - strtotime($borrow['due_date'])) / 86400);
                            echo "<br><small class='text-danger'>Quá hạn {$overdueDays} ngày</small>";
                        }
                        ?>
                    </td>
                    <td><?= $borrow['return_date'] ? formatDate($borrow['return_date']) : '-' ?></td>
                    <td>
                        <?php
                        $statusClass = 'info';
                        $statusText = 'Đang mượn';
                        
                        if ($borrow['status'] === 'pending') {
                            $statusClass = 'warning';
                            $statusText = 'Chờ duyệt';
                        } elseif ($borrow['status'] === 'rejected') {
                            $statusClass = 'danger';
                            $statusText = 'Từ chối';
                        } elseif ($borrow['status'] === 'returned') {
                            $statusClass = 'success';
                            $statusText = 'Đã trả';
                        } elseif ($borrow['status'] === 'overdue') {
                            $statusClass = 'danger';
                            $statusText = 'Quá hạn';
                        } elseif ($borrow['status'] === 'borrowed' && strtotime($borrow['due_date']) < time()) {
                            $statusClass = 'danger';
                            $statusText = 'Quá hạn';
                        }
                        ?>
                        <span class="badge badge-<?= $statusClass ?>">
                            <?= $statusText ?>
                        </span>
                        <?php if ($borrow['status'] === 'rejected' && !empty($borrow['rejection_reason'])): ?>
                            <br>
                            <button type="button" 
                                    class="btn-link-small" 
                                    onclick="showRejectionReason('<?= htmlspecialchars(addslashes($borrow['rejection_reason']), ENT_QUOTES) ?>')">
                                📄 Xem lý do
                            </button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($borrow['fine_amount'] > 0): ?>
                            <span class="text-danger"><strong><?= formatMoney($borrow['fine_amount']) ?></strong></span>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($borrow['status'] === 'borrowed'): ?>
                        <a href="<?= BASE_URL ?>/student/cancel-borrow/<?= $borrow['id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Bạn có chắc muốn hủy mượn sách này?')">
                            Hủy mượn
                        </a>
                        <?php else: ?>
                            -
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
        <ul class="pagination">
            <?php if ($data['pagination']['has_previous']): ?>
            <li><a href="?page=<?= $data['pagination']['current_page'] - 1 ?>">← Trước</a></li>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
            <li class="<?= $i == $data['pagination']['current_page'] ? 'active' : '' ?>">
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
            
            <?php if ($data['pagination']['has_next']): ?>
            <li><a href="?page=<?= $data['pagination']['current_page'] + 1 ?>">Sau →</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
    
    <?php else: ?>
    <div class="card">
        <div class="card-body text-center p-4">
            <p style="font-size: 3rem;">📚</p>
            <h3>Chưa có lịch sử mượn sách</h3>
            <p class="text-muted">Bạn chưa mượn sách nào</p>
            <a href="<?= BASE_URL ?>/student/books" class="btn btn-primary mt-2">
                Khám phá sách
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal hiển thị lý do từ chối -->
<div id="rejectionModal" class="rejection-modal-custom" style="display: none !important; visibility: hidden; opacity: 0;">
    <div class="rejection-modal-content" style="max-width: 500px;" onclick="event.stopPropagation();">
        <div class="modal-header">
            <h3>❌ Lý do từ chối</h3>
            <button type="button" class="close" onclick="closeRejectionModal(event); return false;">&times;</button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <p id="rejectionReasonText" style="margin: 0; white-space: pre-wrap;"></p>
            </div>
            <p class="text-muted" style="margin-top: 1rem; font-size: 0.9rem;">
                <i>💡 Bạn có thể mượn lại sách này sau khi khắc phục vấn đề hoặc liên hệ thủ thư để biết thêm chi tiết.</i>
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeRejectionModal(event); return false;">Đóng</button>
        </div>
    </div>
</div>

<style>
.btn-sm {
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
}
.text-danger {
    color: #dc3545;
}
.text-muted {
    color: #6c757d;
}

.btn-link-small {
    background: none;
    border: none;
    color: #007bff;
    cursor: pointer;
    font-size: 0.85rem;
    text-decoration: underline;
    padding: 0;
    transition: color 0.2s;
}

.btn-link-small:hover {
    color: #0056b3;
}

/* Modal custom cho rejection reason - sử dụng class riêng để tránh conflict */
.rejection-modal-custom {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    background: rgba(0, 0, 0, 0.5) !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    z-index: 99999 !important;
    animation: fadeIn 0.3s !important;
}

.rejection-modal-content {
    background: white !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
    width: 90% !important;
    max-height: 90vh !important;
    overflow-y: auto !important;
    animation: slideDown 0.3s !important;
    position: relative !important;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #dc3545;
}

.modal-header .close {
    background: none;
    border: none;
    font-size: 2rem;
    color: #6c757d;
    cursor: pointer;
    line-height: 1;
    padding: 0;
    width: 30px;
    height: 30px;
    transition: color 0.2s;
}

.modal-header .close:hover {
    color: #000;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.alert {
    padding: 1rem;
    border-radius: 4px;
    border: 1px solid transparent;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<script>
// Biến để lưu trạng thái modal
let rejectionModalOpen = false;

function showRejectionReason(reason) {
    // Ngăn event lan truyền và hành động mặc định
    if (window.event) {
        window.event.preventDefault();
        window.event.stopPropagation();
        window.event.stopImmediatePropagation();
    }
    
    const modal = document.getElementById('rejectionModal');
    const reasonText = document.getElementById('rejectionReasonText');
    
    if (modal && reasonText) {
        reasonText.textContent = reason;
        modal.style.display = 'flex';
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
        document.body.style.overflow = 'hidden';
        rejectionModalOpen = true;
        
        console.log('Modal opened:', reason); // Debug
    }
    
    return false;
}

function closeRejectionModal(e) {
    // Ngăn event lan truyền
    if (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
    }
    
    const modal = document.getElementById('rejectionModal');
    if (modal && rejectionModalOpen) {
        modal.style.display = 'none';
        modal.style.visibility = 'hidden';
        modal.style.opacity = '0';
        document.body.style.overflow = 'auto';
        rejectionModalOpen = false;
        
        console.log('Modal closed'); // Debug
    }
    
    return false;
}

// Setup event listeners khi DOM đã load - Chỉ chạy 1 lần
(function() {
    let initialized = false;
    
    function initRejectionModal() {
        if (initialized) return;
        initialized = true;
        
        const modal = document.getElementById('rejectionModal');
        
        if (modal) {
            console.log('Rejection modal initialized'); // Debug
            
            // Ngăn click vào modal content đóng modal
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.addEventListener('click', function(e) {
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                }, true);
            }
            
            // Đóng modal khi click vào overlay (phần tối bên ngoài)
            modal.addEventListener('click', function(e) {
                if (e.target === modal && rejectionModalOpen) {
                    closeRejectionModal(e);
                }
            }, true);
            
            // Đóng modal khi nhấn ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && rejectionModalOpen) {
                    closeRejectionModal(e);
                }
            }, true);
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRejectionModal);
    } else {
        initRejectionModal();
    }
})();
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
