<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Thư viện Đại học Trà Vinh' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/admin.css">
</head>
<body>
    <!-- Top Header -->
    <header class="admin-header" style="height: 80px !important; min-height: 80px !important; background: white !important;">
        <div class="header-left">
            <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
            <div class="logo">
                <a href="<?= BASE_URL ?>/admin/dashboard" style="display: flex; align-items: center; gap: 0.5rem; color: #2563eb;">
                    <img src="<?= BASE_URL ?>/public/images/logo.png" alt="Logo" style="height: 65px; width: auto;">
                    Quản trị viên
                </a>
            </div>
        </div>
        
        <div class="header-right">
            <div class="user-info">
                <a href="<?= BASE_URL ?>/admin/notifications" class="notification-icon">
                    🔔 <span class="badge">0</span>
                </a>
                <a href="#" class="user-profile-link">
                    <?php 
                    $currentUser = getCurrentUser();
                    if (!empty($currentUser['avatar'])): 
                    ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($currentUser['avatar']) ?>" 
                             alt="Avatar" 
                             class="user-avatar">
                    <?php else: ?>
                        <span class="user-avatar-placeholder">👤</span>
                    <?php endif; ?>
                    <span style="color: #000;"><?= $currentUser['full_name'] ?: $currentUser['username'] ?></span>
                </a>
                <a href="<?= BASE_URL ?>/auth/logout" class="btn btn-sm btn-danger">Đăng xuất</a>
            </div>
        </div>
    </header>
    
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <nav class="sidebar-nav">
            <a href="<?= BASE_URL ?>/admin/dashboard" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Dashboard</span>
            </a>
            
            <div class="nav-section">Quản lý người dùng</div>
            <a href="<?= BASE_URL ?>/admin/students" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'students') !== false ? 'active' : '' ?>">
                <span class="nav-icon">👨‍🎓</span>
                <span class="nav-text">Sinh viên</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/librarians" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'librarians') !== false ? 'active' : '' ?>">
                <span class="nav-icon">👨‍💼</span>
                <span class="nav-text">Thủ thư</span>
            </a>
            
            <div class="nav-section">Báo cáo & Thống kê</div>
            <a href="<?= BASE_URL ?>/admin/reports" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'reports') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📈</span>
                <span class="nav-text">Báo cáo</span>
            </a>
            
            <div class="nav-section">Thông báo</div>
            <a href="<?= BASE_URL ?>/admin/notifications" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'admin/notifications') !== false || strpos($_SERVER['REQUEST_URI'], 'admin/sendNotification') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📢</span>
                <span class="nav-text">Gửi thông báo</span>
            </a>
            
            <div class="nav-section">Hệ thống</div>
            <a href="<?= BASE_URL ?>/admin/settings" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'settings') !== false ? 'active' : '' ?>">
                <span class="nav-icon">⚙️</span>
                <span class="nav-text">Cài đặt</span>
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
