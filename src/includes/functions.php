<?php
/**
 * Các hàm tiện ích dùng chung trong hệ thống
 */

/**
 * Redirect đến một URL
 */
function redirect($url) {
    header("Location: " . BASE_URL . '/' . ltrim($url, '/'));
    exit();
}

/**
 * Làm sạch dữ liệu đầu vào
 */
function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Validate email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Hash password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Format ngày tháng
 */
function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

/**
 * Format ngày giờ
 */
function formatDateTime($datetime, $format = 'd/m/Y H:i') {
    return date($format, strtotime($datetime));
}

/**
 * Format tiền tệ VNĐ
 */
function formatMoney($amount) {
    return number_format($amount, 0, ',', '.') . ' VNĐ';
}

/**
 * Upload file
 */
function uploadFile($file, $targetDir = 'uploads/', $allowedTypes = null) {
    if ($allowedTypes === null) {
        $allowedTypes = ALLOWED_IMAGE_TYPES;
    }
    
    // Kiểm tra lỗi upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Lỗi khi upload file'];
    }
    
    // Kiểm tra kích thước
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'File quá lớn. Tối đa 5MB'];
    }
    
    // Kiểm tra loại file
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Loại file không được phép'];
    }
    
    // Tạo tên file mới
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = uniqid() . '_' . time() . '.' . $extension;
    $uploadPath = UPLOAD_PATH . '/' . $targetDir;
    
    // Tạo thư mục nếu chưa tồn tại
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }
    
    $targetFile = $uploadPath . $newFileName;
    
    // Di chuyển file
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return ['success' => true, 'filename' => $newFileName, 'path' => 'uploads/' . $targetDir . $newFileName];
    }
    
    return ['success' => false, 'message' => 'Không thể upload file'];
}

/**
 * Xóa file
 */
function deleteFile($filePath) {
    $fullPath = UPLOAD_PATH . '/' . $filePath;
    if (file_exists($fullPath)) {
        return unlink($fullPath);
    }
    return false;
}

/**
 * Tạo slug từ tiếng Việt
 */
function createSlug($string) {
    $unicode = [
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
    ];
    
    foreach ($unicode as $nonUnicode => $uni) {
        $string = preg_replace("/($uni)/i", $nonUnicode, $string);
    }
    
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    $string = trim($string, '-');
    
    return $string;
}

/**
 * Tính số ngày quá hạn
 */
function calculateOverdueDays($returnDate) {
    $today = new DateTime();
    $dueDate = new DateTime($returnDate);
    
    if ($today > $dueDate) {
        $interval = $today->diff($dueDate);
        return $interval->days;
    }
    
    return 0;
}

/**
 * Tính tiền phạt
 */
function calculateFine($overdueDays) {
    return $overdueDays * OVERDUE_FINE_PER_DAY;
}

/**
 * Tạo mã ngẫu nhiên
 */
function generateCode($length = 8) {
    return strtoupper(substr(bin2hex(random_bytes($length)), 0, $length));
}

/**
 * Pagination
 */
function paginate($totalItems, $itemsPerPage = 10, $currentPage = 1) {
    $totalPages = ceil($totalItems / $itemsPerPage);
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $itemsPerPage;
    
    return [
        'total_items' => $totalItems,
        'items_per_page' => $itemsPerPage,
        'total_pages' => $totalPages,
        'current_page' => $currentPage,
        'offset' => $offset,
        'has_previous' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages
    ];
}

/**
 * Debug - dump and die
 */
function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

/**
 * Flash message
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get flash message
 */
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Convert timestamp to relative time (time ago)
 */
function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $difference = time() - $timestamp;
    
    if ($difference < 60) {
        return 'Vừa xong';
    } elseif ($difference < 3600) {
        $minutes = floor($difference / 60);
        return $minutes . ' phút trước';
    } elseif ($difference < 86400) {
        $hours = floor($difference / 3600);
        return $hours . ' giờ trước';
    } elseif ($difference < 604800) {
        $days = floor($difference / 86400);
        return $days . ' ngày trước';
    } elseif ($difference < 2592000) {
        $weeks = floor($difference / 604800);
        return $weeks . ' tuần trước';
    } elseif ($difference < 31536000) {
        $months = floor($difference / 2592000);
        return $months . ' tháng trước';
    } else {
        $years = floor($difference / 31536000);
        return $years . ' năm trước';
    }
}

/**
 * Lấy giá trị cài đặt hệ thống
 */
function getSetting($key, $default = null) {
    static $settingsModel = null;
    
    if ($settingsModel === null) {
        require_once __DIR__ . '/../app/models/Settings.php';
        $settingsModel = new Settings();
    }
    
    return $settingsModel->get($key, $default);
}

/**
 * Lấy giá trị cài đặt dạng boolean
 */
function getSettingBool($key, $default = false) {
    static $settingsModel = null;
    
    if ($settingsModel === null) {
        require_once __DIR__ . '/../app/models/Settings.php';
        $settingsModel = new Settings();
    }
    
    return $settingsModel->getBool($key, $default);
}

/**
 * Lấy giá trị cài đặt dạng integer
 */
function getSettingInt($key, $default = 0) {
    static $settingsModel = null;
    
    if ($settingsModel === null) {
        require_once __DIR__ . '/../app/models/Settings.php';
        $settingsModel = new Settings();
    }
    
    return $settingsModel->getInt($key, $default);
}

/**
 * Format ngày theo cài đặt hệ thống
 */
function formatDateSystem($date) {
    if (empty($date)) {
        return '';
    }
    
    $format = getSetting('date_format', 'd/m/Y');
    return date($format, strtotime($date));
}

/**
 * Format ngày giờ theo cài đặt hệ thống
 */
function formatDateTimeSystem($datetime) {
    if (empty($datetime)) {
        return '';
    }
    
    $format = getSetting('datetime_format', 'd/m/Y H:i');
    return date($format, strtotime($datetime));
}

/**
 * Format giờ theo cài đặt hệ thống
 */
function formatTimeSystem($time) {
    if (empty($time)) {
        return '';
    }
    
    $format = getSetting('time_format', 'H:i');
    return date($format, strtotime($time));
}
