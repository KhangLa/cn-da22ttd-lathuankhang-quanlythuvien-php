<?php
/**
 * HomeController - Trang chủ và điều hướng
 */

class HomeController {
    
    /**
     * Trang chủ - Redirect theo role
     */
    public function index() {
        if (isLoggedIn()) {
            // Redirect theo role của user
            if (isAdmin()) {
                redirect('admin/dashboard');
            } elseif (isLibrarian()) {
                redirect('librarian/dashboard');
            } elseif (isStudent()) {
                redirect('student/home');
            }
        } else {
            // Chưa đăng nhập -> redirect đến trang home sinh viên
            redirect('student/home');
        }
    }
}
