<?php 
$pageTitle = 'Danh sách sách - Thư viện TVU';
include __DIR__ . '/../layouts/header.php';
?>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.page-header h1 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
}

.search-filter-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border: none;
    margin-bottom: 2rem;
}

.search-bar {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.search-bar input[type="text"] {
    flex: 1;
    border-radius: 10px;
    border: 2px solid #e5e7eb;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s;
}

.search-bar input[type="text"]:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.search-bar select {
    min-width: 200px;
    border-radius: 10px;
    border: 2px solid #e5e7eb;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s;
}

.search-bar select:focus {
    border-color: #667eea;
    outline: none;
}

.search-bar button {
    border-radius: 10px;
    padding: 0.75rem 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    font-weight: 600;
    font-size: 1rem;
    white-space: nowrap;
}

.book-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    list-style: none;
    padding: 0;
    margin: 2rem 0;
}

.pagination li {
    margin: 0;
}

.pagination li a {
    display: block;
    padding: 0.6rem 1rem;
    border-radius: 8px;
    background: white;
    border: 2px solid #e5e7eb;
    color: #374151;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
}

.pagination li a:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
    transform: translateY(-2px);
}

.pagination li.active a {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
}

.empty-state {
    background: white;
    border-radius: 12px;
    padding: 3rem;
    text-align: center;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.empty-state .icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #6b7280;
}
</style>

<div class="container" style="padding: 2rem 0;">
    <div class="d-flex justify-between align-center mb-4">
        <h1>Danh sách </h1>
        <a href="<?= BASE_URL ?>/student/home" class="btn btn-secondary">← Quay lại</a>
    </div>
    
    <!-- Search and Filter -->
    <div class="search-filter-card">
        <form action="<?= BASE_URL ?>/student/books" method="GET" class="search-bar">
            <input type="text" 
                   name="search" 
                   placeholder="🔍 Tìm kiếm sách theo tên, tác giả..."
                   value="<?= htmlspecialchars($data['search'] ?? '') ?>">
            
            <select name="category">
                <option value="">Tất cả danh mục</option>
                <?php foreach ($data['categories'] as $category): ?>
                <option value="<?= $category['id'] ?>" 
                        <?= ($data['selected_category'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </form>
    </div>
    
    <!-- Books Grid -->
    <?php if (!empty($data['books'])): ?>
    <div class="book-grid mb-4">
        <?php foreach ($data['books'] as $book): ?>
        <div class="book-card">
            <img src="<?= $book['cover_image'] ? BASE_URL . '/public/' . htmlspecialchars($book['cover_image']) : BASE_URL . '/public/images/book-placeholder.png' ?>" 
                 alt="<?= htmlspecialchars($book['title']) ?>" 
                 class="book-card-image">
            <div class="book-card-body">
                <h3 class="book-card-title"><?= htmlspecialchars($book['title']) ?></h3>
                <p class="book-card-author">📝 <?= htmlspecialchars($book['author'] ?: 'Không rõ') ?></p>
                <p class="text-muted" style="font-size: 0.875rem;">
                    <?= $book['category_name'] ? '📂 ' . htmlspecialchars($book['category_name']) : '' ?>
                </p>
                <p class="text-muted" style="font-size: 0.875rem;">
                    📦 Còn lại: <?= $book['available_quantity'] ?>/<?= $book['quantity'] ?>
                </p>
            </div>
            <div class="book-card-footer">
                <span class="badge badge-<?= $book['available_quantity'] > 0 ? 'success' : 'danger' ?>">
                    <?= $book['available_quantity'] > 0 ? 'Còn sách' : 'Hết sách' ?>
                </span>
                <a href="<?= BASE_URL ?>/book/detail/<?= $book['id'] ?>" class="btn btn-sm btn-primary">
                    Chi tiết
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Pagination -->
    <?php if ($data['pagination']['total_pages'] > 1): ?>
    <nav>
        <ul class="pagination">
            <?php if ($data['pagination']['has_previous']): ?>
            <li>
                <a href="?page=<?= $data['pagination']['current_page'] - 1 ?><?= !empty($data['search']) ? '&search=' . urlencode($data['search']) : '' ?><?= !empty($data['selected_category']) ? '&category=' . $data['selected_category'] : '' ?>">
                    ← Trước
                </a>
            </li>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
            <li class="<?= $i == $data['pagination']['current_page'] ? 'active' : '' ?>">
                <a href="?page=<?= $i ?><?= !empty($data['search']) ? '&search=' . urlencode($data['search']) : '' ?><?= !empty($data['selected_category']) ? '&category=' . $data['selected_category'] : '' ?>">
                    <?= $i ?>
                </a>
            </li>
            <?php endfor; ?>
            
            <?php if ($data['pagination']['has_next']): ?>
            <li>
                <a href="?page=<?= $data['pagination']['current_page'] + 1 ?><?= !empty($data['search']) ? '&search=' . urlencode($data['search']) : '' ?><?= !empty($data['selected_category']) ? '&category=' . $data['selected_category'] : '' ?>">
                    Sau →
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
    
    <?php else: ?>
    <div class="empty-state">
        <div class="icon">📭</div>
        <h3>Không tìm thấy sách nào</h3>
        <p>Thử thay đổi từ khóa tìm kiếm hoặc bộ lọc</p>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
