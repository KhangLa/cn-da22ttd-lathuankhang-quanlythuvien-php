<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Thư viện Đại học Trà Vinh' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/librarian.css">
</head>
<body>
    <!-- Top Header -->
    <header class="admin-header" style="height: 80px !important; min-height: 80px !important;">
        <div class="header-left">
            <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
            <div class="logo">
                <a href="<?= BASE_URL ?>/librarian/dashboard" style="display: flex; align-items: center; gap: 0.5rem;">
                    <img src="<?= BASE_URL ?>/public/images/logo.png" alt="Logo" style="height: 65px; width: auto;">
                    Thủ thư
                </a>
            </div>
        </div>
        
        <div class="header-right">
            <div class="user-info">
                <?php 
                require_once __DIR__ . '/../../models/Notification.php';
                $currentUser = getCurrentUser();
                $notificationModel = new Notification();
                $unreadCount = $notificationModel->countUnreadByUser($currentUser['id']);
                ?>
                <a href="<?= BASE_URL ?>/librarian/myNotifications" class="notification-icon">
                    🔔 
                    <?php if ($unreadCount > 0): ?>
                        <span class="badge"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </a>
                <a href="#" class="user-profile-link">
                    <?php 
                    if (!empty($currentUser['avatar'])): 
                    ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($currentUser['avatar']) ?>" 
                             alt="Avatar" 
                             class="user-avatar">
                    <?php else: ?>
                        <span class="user-avatar-placeholder">👤</span>
                    <?php endif; ?>
                    <span><?= $currentUser['full_name'] ?: $currentUser['username'] ?></span>
                </a>
                <a href="<?= BASE_URL ?>/auth/logout" class="btn btn-sm btn-danger">Đăng xuất</a>
            </div>
        </div>
    </header>
    
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <nav class="sidebar-nav">
            <a href="<?= BASE_URL ?>/librarian/dashboard" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Dashboard</span>
            </a>
            
            <div class="nav-section">Quản lý sách</div>
            <a href="<?= BASE_URL ?>/librarian/books" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'books') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📚</span>
                <span class="nav-text">Danh sách sách</span>
            </a>
            <a href="<?= BASE_URL ?>/librarian/add-book" class="nav-item">
                <span class="nav-icon">➕</span>
                <span class="nav-text">Thêm sách mới</span>
            </a>
            <a href="<?= BASE_URL ?>/librarian/categories" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'categories') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📂</span>
                <span class="nav-text">Danh mục</span>
            </a>
            
            <div class="nav-section">Quản lý mượn/trả</div>
            <a href="<?= BASE_URL ?>/librarian/borrowRequests" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'borrowRequests') !== false || strpos($_SERVER['REQUEST_URI'], 'approveRequest') !== false || strpos($_SERVER['REQUEST_URI'], 'rejectRequest') !== false ? 'active' : '' ?>">
                <span class="nav-icon">⏳</span>
                <span class="nav-text">Yêu cầu mượn sách</span>
                <?php
                require_once __DIR__ . '/../../models/Borrow.php';
                $borrowModel = new Borrow();
                $pendingCount = $borrowModel->countPendingRequests();
                if ($pendingCount > 0):
                ?>
                    <span class="badge badge-danger" style="margin-left: auto;"><?= $pendingCount ?></span>
                <?php endif; ?>
            </a>
            <a href="<?= BASE_URL ?>/librarian/borrows" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'borrows') !== false && strpos($_SERVER['REQUEST_URI'], 'borrowRequests') === false ? 'active' : '' ?>">
                <span class="nav-icon">📖</span>
                <span class="nav-text">Phiếu mượn</span>
            </a>
            <a href="<?= BASE_URL ?>/librarian/create-borrow" class="nav-item">
                <span class="nav-icon">📝</span>
                <span class="nav-text">Tạo phiếu mượn</span>
            </a>
            
            <div class="nav-section">Quản lý phạt</div>
            <a href="<?= BASE_URL ?>/librarian/fines" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/fines') !== false || strpos($_SERVER['REQUEST_URI'], 'view-fine') !== false || strpos($_SERVER['REQUEST_URI'], 'create-fine') !== false ? 'active' : '' ?>">
                <span class="nav-icon">💰</span>
                <span class="nav-text">Phiếu phạt</span>
                <?php
                require_once __DIR__ . '/../../models/Fine.php';
                $fineModelMenu = new Fine();
                $unpaidFines = $fineModelMenu->countByStatus('unpaid');
                if ($unpaidFines > 0):
                ?>
                    <span class="badge badge-danger" style="margin-left: auto;"><?= $unpaidFines ?></span>
                <?php endif; ?>
            </a>
            <a href="<?= BASE_URL ?>/librarian/create-fine" class="nav-item">
                <span class="nav-icon">➕</span>
                <span class="nav-text">Tạo phiếu phạt</span>
            </a>
            
            <div class="nav-section">Báo cáo & Thông báo</div>
            <a href="<?= BASE_URL ?>/librarian/book-reports" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'book-reports') !== false || strpos($_SERVER['REQUEST_URI'], 'view-report') !== false || strpos($_SERVER['REQUEST_URI'], 'update-report-status') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📋</span>
                <span class="nav-text">Báo cáo sách hư hỏng</span>
                <?php
                require_once __DIR__ . '/../../models/BookReport.php';
                $bookReportModel = new BookReport();
                $pendingReports = $bookReportModel->countByStatus('pending');
                if ($pendingReports > 0):
                ?>
                    <span class="badge badge-warning" style="margin-left: auto;"><?= $pendingReports ?></span>
                <?php endif; ?>
            </a>
            <a href="<?= BASE_URL ?>/librarian/myNotifications" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'myNotifications') !== false || strpos($_SERVER['REQUEST_URI'], 'markNotificationRead') !== false || strpos($_SERVER['REQUEST_URI'], 'markAllNotificationsRead') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📬</span>
                <span class="nav-text">Thông báo của tôi</span>
            </a>
            <a href="<?= BASE_URL ?>/librarian/notifications" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'librarian/notifications') !== false && strpos($_SERVER['REQUEST_URI'], 'myNotifications') === false ? 'active' : '' ?>">
                <span class="nav-icon">📢</span>
                <span class="nav-text">Gửi thông báo SV</span>
            </a>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="admin-main">
        <?php 
        $flash = getFlash();
        if ($flash): 
        ?>
            <div class="alert alert-<?= $flash['type'] ?> alert-dismissible">
                <?= $flash['message'] ?>
                <button type="button" class="btn-close" onclick="this.parentElement.remove()">×</button>
            </div>
        <?php endif; ?>
