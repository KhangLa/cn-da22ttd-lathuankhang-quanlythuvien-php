<?php
$pageTitle = (isset($data['category']) ? 'Sửa' : 'Thêm') . ' danh mục - Thủ thư';
$isEdit = isset($data['category']);
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><?= $isEdit ? 'Sửa' : 'Thêm' ?> danh mục</h1>
        <a href="<?= BASE_URL ?>/librarian/categories" class="btn btn-secondary">← Quay lại</a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label>Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" 
                           value="<?= $data['category']['name'] ?? '' ?>" 
                           placeholder="Ví dụ: Khoa học máy tính, Văn học..." required>
                </div>
                
                <div class="form-group">
                    <label>Mô tả</label>
                    <textarea name="description" class="form-control" rows="3"><?= $data['category']['description'] ?? '' ?></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        💾 <?= $isEdit ? 'Cập nhật' : 'Thêm mới' ?>
                    </button>
                    <a href="<?= BASE_URL ?>/librarian/categories" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
