<?php
/**
 * File cấu hình chính của hệ thống
 */

// Cấu hình Database (MySQL - XAMPP)
define('DB_HOST', 'localhost');
define('DB_NAME', 'library_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // Mặc định XAMPP không có password
define('DB_CHARSET', 'utf8mb4');

// Cấu hình URL
define('BASE_URL', 'http://localhost/QLTV');
define('PUBLIC_PATH', __DIR__ . '/../public');

// Cấu hình session
define('SESSION_NAME', 'library_session');
define('SESSION_LIFETIME', 7200); // 2 giờ

// Cấu hình upload
define('UPLOAD_PATH', __DIR__ . '/../public/uploads');
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/jpg']);

// Cấu hình phân quyền
define('ROLE_ADMIN', 'admin');
define('ROLE_LIBRARIAN', 'librarian');
define('ROLE_STUDENT', 'student');

// Cấu hình mượn sách
define('MAX_BORROW_DAYS', 14); // Số ngày mượn tối đa
define('MAX_BOOKS_PER_USER', 5); // Số sách mượn tối đa mỗi lần
define('OVERDUE_FINE_PER_DAY', 5000); // Phí phạt quá hạn (VNĐ/ngày)

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Báo lỗi (Tắt khi production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Thông tin hệ thống
define('SYSTEM_NAME', 'Hệ thống Quản lý Thư viện');
define('UNIVERSITY_NAME', 'Đại học Trà Vinh');
define('SYSTEM_VERSION', '1.0.0');
