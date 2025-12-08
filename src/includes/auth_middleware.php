<?php
/**
 * Middleware xác thực và phân quyền
 */

class AuthMiddleware {
    
    /**
     * Kiểm tra xác thực
     */
    public static function authenticate() {
        if (!isLoggedIn()) {
            setFlash('error', 'Vui lòng đăng nhập để tiếp tục');
            redirect('auth/login');
        }
    }
    
    /**
     * Kiểm tra quyền admin
     */
    public static function adminOnly() {
        self::authenticate();
        if (!isAdmin()) {
            setFlash('error', 'Bạn không có quyền truy cập trang này');
            self::redirectByRole();
        }
    }
    
    /**
     * Kiểm tra quyền librarian hoặc admin
     */
    public static function librarianOrAdmin() {
        self::authenticate();
        if (!isLibrarian() && !isAdmin()) {
            setFlash('error', 'Bạn không có quyền truy cập trang này');
            self::redirectByRole();
        }
    }
    
    /**
     * Kiểm tra quyền student
     */
    public static function studentOnly() {
        self::authenticate();
        if (!isStudent()) {
            setFlash('error', 'Bạn không có quyền truy cập trang này');
            self::redirectByRole();
        }
    }
    
    /**
     * Kiểm tra guest (chưa đăng nhập)
     */
    public static function guestOnly() {
        if (isLoggedIn()) {
            self::redirectByRole();
        }
    }
    
    /**
     * Redirect theo role
     */
    public static function redirectByRole() {
        if (isAdmin()) {
            redirect('admin/dashboard');
        } elseif (isLibrarian()) {
            redirect('librarian/dashboard');
        } elseif (isStudent()) {
            redirect('student/home');
        } else {
            redirect('auth/login');
        }
    }
}
