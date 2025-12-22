<?php
$pageTitle = 'Báo cáo tình trạng sách - Sinh viên';
require_once __DIR__ . '/../layouts/header.php';

$book = $data['book'] ?? null;
$borrow = $data['borrow'] ?? null;
$borrowed_books = $data['borrowed_books'] ?? [];
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3>Báo cáo tình trạng sách</h3>
                    <p class="mb-0">Báo cáo sách bị hư hỏng, mất trang, hoặc các vấn đề khác</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>/student/submit-report">
                        
                        <div class="form-group">
                            <label for="book_id">Chọn sách cần báo cáo <span class="text-danger">*</span></label>
                            <?php if ($book): ?>
                                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                <?php if ($borrow): ?>
                                    <input type="hidden" name="borrow_id" value="<?= $borrow['id'] ?>">
                                <?php endif; ?>
                                <div class="alert alert-info">
                                    <strong><?= htmlspecialchars($book['title']) ?></strong><br>
                                    <small>Tác giả: <?= htmlspecialchars($book['author']) ?></small>
                                </div>
                            <?php else: ?>
                                <select class="form-control" id="book_id" name="book_id" required>
                                    <option value="">-- Chọn sách --</option>
                                    <?php if (!empty($borrowed_books)): ?>
                                        <?php foreach ($borrowed_books as $item): ?>
                                            <option value="<?= $item['book_id'] ?>" data-borrow-id="<?= $item['id'] ?>">
                                                <?= htmlspecialchars($item['book_title']) ?> - Tác giả: <?= htmlspecialchars($item['book_author']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>Bạn chưa mượn sách nào</option>
                                    <?php endif; ?>
                                </select>
                                <input type="hidden" name="borrow_id" id="borrow_id">
                                <small class="form-text text-muted">Chỉ hiển thị các sách bạn đang mượn</small>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="report_type">Loại vấn đề <span class="text-danger">*</span></label>
                            <select class="form-control" id="report_type" name="report_type" required>
                                <option value="">-- Chọn loại vấn đề --</option>
                                <option value="damaged">Sách bị hư hỏng</option>
                                <option value="missing_pages">Thiếu trang</option>
                                <option value="torn">Rách, xé</option>
                                <option value="stained">Bị dơ, ố</option>
                                <option value="lost">Mất sách</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Mô tả chi tiết <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="6" 
                                      placeholder="Mô tả chi tiết tình trạng sách (ít nhất 10 ký tự)..." required></textarea>
                            <small class="form-text text-muted">
                                Ví dụ: Sách bị rách trang 45, thiếu trang 78-80, bìa bị gãy góc,...
                            </small>
                        </div>
                        
                        <div class="alert alert-warning">
                            <strong>⚠️ Lưu ý:</strong>
                            <ul class="mb-0">
                                <li>Vui lòng mô tả chính xác tình trạng sách</li>
                                <li>Thủ thư sẽ xem xét và phản hồi trong vòng 1-2 ngày</li>
                                <li>Bạn có thể theo dõi trạng thái báo cáo tại mục "Báo cáo của tôi"</li>
                            </ul>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Gửi báo cáo
                            </button>
                            <a href="<?= BASE_URL ?>/student/borrow-list" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-3">
                <a href="<?= BASE_URL ?>/student/my-reports" class="btn btn-outline-primary">
                    <i class="fas fa-list"></i> Xem báo cáo của tôi
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Auto fill borrow_id when book is selected
document.getElementById('book_id')?.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const borrowId = selectedOption.getAttribute('data-borrow-id');
    document.getElementById('borrow_id').value = borrowId || '';
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
