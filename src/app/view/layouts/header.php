<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Thư viện Đại học Trà Vinh' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/dashboard.css">
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <a href="<?= BASE_URL ?>">
                        <img src="<?= BASE_URL ?>/public/images/logo.png" alt="Logo TVU" class="logo-img">Thư Viện
                    </a>
                </div>
                
                <ul class="nav-menu">
                    <li><a href="<?= BASE_URL ?>/student/home">Trang chủ</a></li>
                    <li><a href="<?= BASE_URL ?>/student/books">Danh Sách</a></li>
                    <?php if (isLoggedIn()): ?>
                        <?php if (isStudent()): ?>
                            <li><a href="<?= BASE_URL ?>/student/borrow-list">Lịch sử mượn</a></li>
                            <li><a href="<?= BASE_URL ?>/student/my-fines">Vi phạm</a></li>
                            <li><a href="<?= BASE_URL ?>/student/report-book">Báo cáo</a></li>
                        <?php elseif (isLibrarian()): ?>
                            <li><a href="<?= BASE_URL ?>/librarian/dashboard">Dashboard</a></li>
                            <li><a href="<?= BASE_URL ?>/librarian/books">Quản lý sách</a></li>
                            <li><a href="<?= BASE_URL ?>/librarian/borrows">Quản lý mượn</a></li>
                        <?php elseif (isAdmin()): ?>
                            <li><a href="<?= BASE_URL ?>/admin/dashboard">Dashboard</a></li>
                            <li><a href="<?= BASE_URL ?>/admin/students">Sinh viên</a></li>
                            <li><a href="<?= BASE_URL ?>/admin/librarians">Thủ thư</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                
                <div class="user-info">
                    <?php if (isLoggedIn()): ?>
                        <a href="<?= isStudent() ? BASE_URL . '/student/notifications' : '#' ?>">
                            🔔 Thông báo
                        </a>
                        <a href="<?= isStudent() ? BASE_URL . '/student/profile' : '#' ?>" class="user-profile-link">
                            <?php 
                            $currentUser = getCurrentUser();
                            if (!empty($currentUser['avatar'])): 
                            ?>
                                <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($currentUser['avatar']) ?>" 
                                     alt="Avatar" 
                                     class="user-avatar"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                            <?php else: ?>
                                <span class="user-avatar-placeholder">👤</span>
                            <?php endif; ?>
                            <span><?= $currentUser['full_name'] ?: $currentUser['username'] ?></span>
                        </a>
                        <a href="<?= BASE_URL ?>/auth/logout" class="btn btn-sm btn-danger">Đăng xuất</a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/auth/login" class="btn btn-sm btn-primary">Đăng nhập</a>
                        <a href="<?= BASE_URL ?>/auth/register" class="btn btn-sm btn-success">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>
    
    <main>
        <?php 
        $flash = getFlash();
        if ($flash): 
        ?>
            <div class="container mt-3">
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            </div>
        <?php endif; ?>
