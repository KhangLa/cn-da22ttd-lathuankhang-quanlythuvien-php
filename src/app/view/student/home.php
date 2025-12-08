<?php 
$pageTitle = 'Trang chủ - Thư viện TVU';
include __DIR__ . '/../layouts/header.php';
?>

<style>
.banner-slider {
    position: relative;
    width: 100%;
    height: 400px;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.banner-slide {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

.banner-slide.active {
    opacity: 1;
}

.banner-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.banner-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    color: white;
    padding: 2rem;
}

.banner-overlay h2 {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.banner-overlay p {
    font-size: 1.1rem;
    margin: 0;
}

.slider-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.3);
    color: white;
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    font-size: 1.5rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(5px);
    z-index: 10;
}

.slider-btn:hover {
    background: rgba(255,255,255,0.5);
    transform: translateY(-50%) scale(1.1);
}

.slider-btn.prev {
    left: 20px;
}

.slider-btn.next {
    right: 20px;
}

.slider-dots {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 10;
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.5);
    cursor: pointer;
    transition: all 0.3s;
}

.dot.active {
    background: white;
    width: 30px;
    border-radius: 6px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}

.stat-card-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 0.3rem 0;
    color: #1f2937;
}

.stat-card-content p {
    margin: 0;
    color: #6b7280;
    font-size: 0.95rem;
    font-weight: 500;
}

.stat-card-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
}

.stat-card-icon.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-card-icon.success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.stat-card-icon.warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.stat-card-icon.info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.8rem;
    border-bottom: 2px solid #e5e7eb;
}

.section-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.book-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.5rem;
}

.category-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    border: 2px solid #e5e7eb;
    text-decoration: none;
    display: block;
}

.category-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    border-color: #667eea;
}

.category-card h3 {
    color: #1f2937;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
}

.category-card p {
    color: #6b7280;
    font-size: 0.9rem;
    margin: 0;
}
</style>

<div class="container" style="padding: 2rem 0;">
    <!-- Banner Slider Section -->
    <div class="banner-slider mb-4">
        <!-- Slide 1 -->
        <div class="banner-slide active">
            <img src="<?= BASE_URL ?>/public/images/anhbanner1.jpg" alt="Library Banner 1">
            <div class="banner-overlay">
                <h2>Chào mừng đến với Thư viện TVU</h2>
                <p>Khám phá kho tàng tri thức phong phú</p>
            </div>
        </div>
        
        <!-- Slide 2 -->
        <div class="banner-slide">
            <img src="<?= BASE_URL ?>/public/images/anhbanner2.jpg" alt="Library Banner 2">
            <div class="banner-overlay">
                <h2>Học tập không giới hạn</h2>
                <p>Hàng ngàn đầu sách đang chờ bạn khám phá</p>
            </div>
        </div>
        
        <!-- Slide 3 -->
        <div class="banner-slide">
            <img src="<?= BASE_URL ?>/public/images/anhbanner3.jpg" alt="Library Banner 3">
            <div class="banner-overlay">
                <h2>Đọc sách mỗi ngày</h2>
                <p>Nâng cao kiến thức và phát triển bản thân</p>
            </div>
        </div>
        
        <!-- Navigation Buttons -->
        <button class="slider-btn prev" onclick="changeSlide(-1)">❮</button>
        <button class="slider-btn next" onclick="changeSlide(1)">❯</button>
        
        <!-- Dots Navigation -->
        <div class="slider-dots">
            <span class="dot active" onclick="currentSlide(0)"></span>
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
        </div>
    </div>

    <script>
        let slideIndex = 0;
        let autoSlideTimer;

        function showSlide(n) {
            const slides = document.querySelectorAll('.banner-slide');
            const dots = document.querySelectorAll('.dot');
            
            if (n >= slides.length) { slideIndex = 0; }
            if (n < 0) { slideIndex = slides.length - 1; }
            
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            slides[slideIndex].classList.add('active');
            dots[slideIndex].classList.add('active');
        }

        function changeSlide(n) {
            clearInterval(autoSlideTimer);
            slideIndex += n;
            showSlide(slideIndex);
            startAutoSlide();
        }

        function currentSlide(n) {
            clearInterval(autoSlideTimer);
            slideIndex = n;
            showSlide(slideIndex);
            startAutoSlide();
        }

        function autoSlide() {
            slideIndex++;
            showSlide(slideIndex);
        }

        function startAutoSlide() {
            autoSlideTimer = setInterval(autoSlide, 5000);
        }

        // Start auto slide on page load
        startAutoSlide();
    </script>

    <!-- Search Bar -->
    <div class="card mb-4" style="border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px;">
        <div class="card-body" style="padding: 1.5rem;">
            <form action="<?= BASE_URL ?>/student/books" method="GET">
                <div class="d-flex gap-2">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="🔍 Tìm kiếm sách theo tên, tác giả, ISBN..."
                           style="border-radius: 10px; border: 2px solid #e5e7eb; padding: 0.75rem 1rem; font-size: 1rem;">
                    <button type="submit" class="btn btn-primary" style="border-radius: 10px; padding: 0.75rem 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; font-weight: 600;">
                        Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="grid grid-4 gap-2 mb-4">
        <div class="stat-card">
            <div class="stat-card-content">
                <h3><?= count($data['latest_books'] ?? []) ?></h3>
                <p>Sách mới nhất</p>
            </div>
            <div class="stat-card-icon primary">📚</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-content">
                <h3><?= count($data['categories'] ?? []) ?></h3>
                <p>Danh mục</p>
            </div>
            <div class="stat-card-icon success">📂</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-content">
                <h3><?= count($data['popular_books'] ?? []) ?></h3>
                <p>Sách phổ biến</p>
            </div>
            <div class="stat-card-icon warning">⭐</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-content">
                <h3>24/7</h3>
                <p>Hỗ trợ</p>
            </div>
            <div class="stat-card-icon info">🌐</div>
        </div>
    </div>
    
    <!-- Latest Books -->
    <?php if (!empty($data['latest_books'])): ?>
    <section class="mb-5">
        <div class="section-header">
            <h2>📖 Sách mới nhất</h2>
        </div>
        <div class="book-grid">
            <?php foreach ($data['latest_books'] as $book): ?>
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
    </section>
    <?php endif; ?>
    
    <!-- Popular Books -->
    <?php if (!empty($data['popular_books'])): ?>
    <section class="mb-5">
        <div class="section-header">
            <h2>⭐ Sách phổ biến</h2>
        </div>
        <div class="book-grid">
            <?php foreach ($data['popular_books'] as $book): ?>
            <div class="book-card">
                <img src="<?= $book['cover_image'] ? BASE_URL . '/public/' . htmlspecialchars($book['cover_image']) : BASE_URL . '/public/images/book-placeholder.png' ?>" 
                     alt="<?= htmlspecialchars($book['title']) ?>" 
                     class="book-card-image">
                <div class="book-card-body">
                    <h3 class="book-card-title"><?= htmlspecialchars($book['title']) ?></h3>
                    <p class="book-card-author">📝 <?= htmlspecialchars($book['author'] ?: 'Không rõ') ?></p>
                    <?php if (isset($book['borrow_count'])): ?>
                    <p class="text-muted" style="font-size: 0.875rem;">
                        👥 Đã mượn: <?= $book['borrow_count'] ?> lần
                    </p>
                    <?php endif; ?>
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
    </section>
    <?php endif; ?>
    
    <!-- Categories -->
    <?php if (!empty($data['categories'])): ?>
    <section class="mb-5">
        <div class="section-header">
            <h2>📂 Danh mục sách</h2>
        </div>
        <div class="grid grid-4 gap-2">
            <?php foreach ($data['categories'] as $category): ?>
            <a href="<?= BASE_URL ?>/student/books?category=<?= $category['id'] ?>" class="category-card">
                <h3><?= htmlspecialchars($category['name']) ?></h3>
                <p><?= $category['book_count'] ?? 0 ?> cuốn sách</p>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
