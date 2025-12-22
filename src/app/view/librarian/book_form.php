<?php
$pageTitle = (isset($data['book']) ? 'Sửa' : 'Thêm') . ' sách - Thủ thư';
$isEdit = isset($data['book']);
require_once __DIR__ . '/../layouts/librarian_header.php';
?>

<div class="page-header">
    <h1>📚 <?= $isEdit ? 'Sửa thông tin' : 'Thêm' ?> sách</h1>
    <a href="<?= BASE_URL ?>/librarian/books" class="btn btn-secondary">← Quay lại</a>
</div>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Tên sách <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" 
                                   value="<?= $data['book']['title'] ?? '' ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tác giả <span class="text-danger">*</span></label>
                                    <input type="text" name="author" class="form-control" 
                                           value="<?= $data['book']['author'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nhà xuất bản</label>
                                    <input type="text" name="publisher" class="form-control" 
                                           value="<?= $data['book']['publisher'] ?? '' ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Năm xuất bản</label>
                                    <input type="number" name="publish_year" class="form-control" 
                                           value="<?= $data['book']['publish_year'] ?? '' ?>" 
                                           min="1900" max="<?= date('Y') ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>ISBN</label>
                                    <input type="text" name="isbn" class="form-control" 
                                           value="<?= $data['book']['isbn'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Danh mục</label>
                                    <select name="category_id" class="form-control">
                                        <option value="">-- Chọn danh mục --</option>
                                        <?php foreach ($data['categories'] as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" 
                                            <?= isset($data['book']) && $data['book']['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                            <?= $cat['name'] ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Số lượng <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" class="form-control" 
                                           value="<?= $data['book']['quantity'] ?? 0 ?>" 
                                           min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Vị trí</label>
                                    <input type="text" name="location" class="form-control" 
                                           value="<?= $data['book']['location'] ?? '' ?>" 
                                           placeholder="Ví dụ: Kệ A1, Tầng 2">
                                </div>
                            </div>
                            <?php if ($isEdit): ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select name="status" class="form-control">
                                        <option value="available" <?= $data['book']['status'] === 'available' ? 'selected' : '' ?>>
                                            Có sẵn
                                        </option>
                                        <option value="unavailable" <?= $data['book']['status'] === 'unavailable' ? 'selected' : '' ?>>
                                            Không có
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea name="description" class="form-control" rows="4"><?= $data['book']['description'] ?? '' ?></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Ảnh bìa</label>
                            <?php if ($isEdit && !empty($data['book']['cover_image'])): ?>
                            <div class="mb-2">
                                <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($data['book']['cover_image']) ?>" 
                                     alt="Cover" class="img-fluid rounded" style="max-width: 100%; height: auto;">
                            </div>
                            <?php endif; ?>
                            <input type="file" name="cover_image" class="form-control" accept="image/*">
                            <small class="form-text text-muted">Định dạng: JPG, PNG. Tối đa 5MB</small>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">
                        💾 <?= $isEdit ? 'Cập nhật' : 'Thêm mới' ?>
                    </button>
                    <a href="<?= BASE_URL ?>/librarian/books" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/librarian_footer.php'; ?>
