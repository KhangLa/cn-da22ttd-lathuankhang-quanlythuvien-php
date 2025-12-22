<?php
$pageTitle = 'Quản lý danh mục - Thủ thư';
require_once __DIR__ . '/../layouts/librarian_header.php';
?>

<div class="page-header">
    <h1>📂 Quản lý danh mục sách</h1>
    <a href="<?= BASE_URL ?>/librarian/add-category" class="btn btn-primary">➕ Thêm danh mục</a>
</div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Slug</th>
                            <th>Mô tả</th>
                            <th>Số sách</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['categories'] as $category): ?>
                        <tr>
                            <td><?= $category['id'] ?></td>
                            <td><strong><?= $category['name'] ?></strong></td>
                            <td><code><?= $category['slug'] ?></code></td>
                            <td><?= $category['description'] ?: '-' ?></td>
                            <td><?= $category['book_count'] ?></td>
                            <td><?= formatDate($category['created_at']) ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>/librarian/edit-category/<?= $category['id'] ?>" 
                                   class="btn btn-sm btn-warning">Sửa</a>
                                <?php if ($category['book_count'] == 0): ?>
                                <a href="<?= BASE_URL ?>/librarian/delete-category/<?= $category['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">Xóa</a>
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

<?php require_once __DIR__ . '/../layouts/librarian_footer.php'; ?>
