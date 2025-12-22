<?php
$pageTitle = 'Quản lý sách - Thủ thư';
require_once __DIR__ . '/../layouts/librarian_header.php';
?>

<div class="page-header">
    <h1>📚 Quản lý sách</h1>
    <a href="<?= BASE_URL ?>/librarian/add-book" class="btn btn-primary">➕ Thêm sách</a>
</div>
    
    <!-- Search & Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET">
                <div class="row">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Tìm kiếm sách..." 
                               value="<?= $data['search'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <select name="category" class="form-control">
                            <option value="">-- Tất cả danh mục --</option>
                            <?php foreach ($data['categories'] as $cat): ?>
                            <option value="<?= $cat['id'] ?>" 
                                <?= isset($data['selected_category']) && $data['selected_category'] == $cat['id'] ? 'selected' : '' ?>>
                                <?= $cat['name'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
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
                            <th>Tên sách</th>
                            <th>Tác giả</th>
                            <th>Danh mục</th>
                            <th>SL</th>
                            <th>Còn lại</th>
                            <th>Vị trí</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['books'] as $book): ?>
                        <tr>
                            <td><?= $book['id'] ?></td>
                            <td><strong><?= $book['title'] ?></strong></td>
                            <td><?= $book['author'] ?></td>
                            <td><?= $book['category_name'] ?? '-' ?></td>
                            <td><?= $book['quantity'] ?></td>
                            <td><?= $book['available_quantity'] ?></td>
                            <td><?= $book['location'] ?? '-' ?></td>
                            <td>
                                <?php if ($book['status'] === 'available'): ?>
                                    <span class="badge badge-success">Có sẵn</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Không có</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>/book/detail/<?= $book['id'] ?>" 
                                   class="btn btn-sm btn-info">Xem</a>
                                <a href="<?= BASE_URL ?>/librarian/edit-book/<?= $book['id'] ?>" 
                                   class="btn btn-sm btn-warning">Sửa</a>
                                <a href="<?= BASE_URL ?>/librarian/delete-book/<?= $book['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Bạn có chắc muốn xóa sách này?')">Xóa</a>
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
                        <a class="page-link" href="?page=<?= $data['pagination']['current_page'] - 1 ?><?= $data['search'] ? '&search=' . urlencode($data['search']) : '' ?><?= $data['selected_category'] ? '&category=' . $data['selected_category'] : '' ?>">
                            Trước
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= min(10, $data['pagination']['total_pages']); $i++): ?>
                    <li class="page-item <?= $i === $data['pagination']['current_page'] ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?><?= $data['search'] ? '&search=' . urlencode($data['search']) : '' ?><?= $data['selected_category'] ? '&category=' . $data['selected_category'] : '' ?>">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if ($data['pagination']['has_next']): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $data['pagination']['current_page'] + 1 ?><?= $data['search'] ? '&search=' . urlencode($data['search']) : '' ?><?= $data['selected_category'] ? '&category=' . $data['selected_category'] : '' ?>">
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
