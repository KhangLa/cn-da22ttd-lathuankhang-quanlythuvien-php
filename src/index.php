<?php
/**
 * Hệ thống Quản lý Thư viện - Đại học Trà Vinh
 * Trang khởi động chính
 */

// Bắt đầu session
session_start();

// Load các file cấu hình và helper
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/session_helper.php';

// Load routes
require_once __DIR__ . '/app/routes.php';
