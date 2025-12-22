<?php
/**
 * Helper functions cho Session
 */

/**
 * Kiểm tra user đã đăng nhập chưa
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']);
}

/**
 * Lấy thông tin user hiện tại
 */
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'] ?? '',
            'email' => $_SESSION['email'] ?? '',
            'role' => $_SESSION['role'],
            'full_name' => $_SESSION['full_name'] ?? '',
            'avatar' => $_SESSION['avatar'] ?? ''
        ];
    }
    return null;
}

/**
 * Đăng nhập user
 */
function login($userId, $username, $email, $role, $fullName = '', $avatar = '') {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['role'] = $role;
    $_SESSION['full_name'] = $fullName;
    $_SESSION['avatar'] = $avatar;
    $_SESSION['last_activity'] = time();
}

/**
 * Đăng xuất
 */
function logout() {
    session_unset();
    session_destroy();
    redirect('auth/login');
}

/**
 * Kiểm tra role của user
 */
function hasRole($role) {
    return isLoggedIn() && $_SESSION['role'] === $role;
}

/**
 * Kiểm tra quyền admin
 */
function isAdmin() {
    return hasRole(ROLE_ADMIN);
}

/**
 * Kiểm tra quyền librarian
 */
function isLibrarian() {
    return hasRole(ROLE_LIBRARIAN);
}

/**
 * Kiểm tra quyền student
 */
function isStudent() {
    return hasRole(ROLE_STUDENT);
}

/**
 * Yêu cầu đăng nhập
 */
function requireLogin() {
    if (!isLoggedIn()) {
        setFlash('error', 'Vui lòng đăng nhập để tiếp tục');
        redirect('auth/login');
    }
}

/**
 * Yêu cầu role cụ thể
 */
function requireRole($role) {
    requireLogin();
    if (!hasRole($role)) {
        setFlash('error', 'Bạn không có quyền truy cập trang này');
        redirect('home');
    }
}

/**
 * Yêu cầu admin
 */
function requireAdmin() {
    requireRole(ROLE_ADMIN);
}

/**
 * Yêu cầu librarian
 */
function requireLibrarian() {
    requireLogin();
    if (!isLibrarian() && !isAdmin()) {
        setFlash('error', 'Bạn không có quyền truy cập trang này');
        redirect('home');
    }
}

/**
 * Kiểm tra session timeout
 */
function checkSessionTimeout() {
    if (isLoggedIn()) {
        if (isset($_SESSION['last_activity']) && 
            (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
            logout();
        }
        $_SESSION['last_activity'] = time();
    }
}

// Gọi kiểm tra timeout mỗi lần load trang
checkSessionTimeout();
