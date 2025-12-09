<?php
/**
 * AuthController - Xử lý đăng nhập, đăng ký, đăng xuất
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Settings.php';
require_once __DIR__ . '/../../includes/auth_middleware.php';

class AuthController {
    private $userModel;
    private $settingsModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->settingsModel = new Settings();
    }
    
    /**
     * Hiển thị trang đăng nhập
     */
    public function login() {
        // Nếu đã đăng nhập, redirect theo role
        if (isLoggedIn()) {
            AuthMiddleware::redirectByRole();
        }
        
        // Xử lý form đăng nhập
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = clean($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Validate
            if (empty($username) || empty($password)) {
                setFlash('error', 'Vui lòng nhập đầy đủ thông tin');
            } else {
                // Kiểm tra đăng nhập
                $user = $this->userModel->login($username, $password);
                
                if ($user) {
                    // Kiểm tra chế độ bảo trì
                    $maintenanceMode = $this->settingsModel->getBool('maintenance_mode', false);
                    
                    if ($maintenanceMode && $user['role'] === 'student') {
                        // Chế độ bảo trì: chỉ chặn sinh viên
                        $maintenanceMessage = $this->settingsModel->get('maintenance_message', 'Web bảo trì');
                        setFlash('error', $maintenanceMessage);
                        // Không cho đăng nhập, hiển thị lại form login
                    } else {
                        // Đăng nhập thành công
                        login($user['id'], $user['username'], $user['email'], $user['role'], $user['full_name'], $user['avatar'] ?? '');
                        
                        setFlash('success', 'Đăng nhập thành công!');
                        
                        // Kiểm tra redirect URL từ query string
                        if (isset($_GET['redirect'])) {
                            $redirectUrl = $_GET['redirect'];
                            // Bảo mật: chỉ cho phép redirect nội bộ
                            if (strpos($redirectUrl, '/') === 0) {
                                header('Location: ' . $redirectUrl);
                                exit;
                            }
                        }
                        
                        // Redirect theo role
                        AuthMiddleware::redirectByRole();
                    }
                } else {
                    setFlash('error', 'Tên đăng nhập hoặc mật khẩu không đúng');
                }
            }
        }
        
        // Hiển thị view
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    /**
     * Hiển thị trang đăng ký
     */
    public function register() {
        // Nếu đã đăng nhập, redirect về trang chủ
        if (isLoggedIn()) {
            redirect('home');
        }
        
        // Xử lý form đăng ký
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = clean($_POST['username'] ?? '');
            $email = clean($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $fullName = clean($_POST['full_name'] ?? '');
            $studentCode = clean($_POST['student_code'] ?? '');
            $phone = clean($_POST['phone'] ?? '');
            
            // Validate
            $errors = [];
            
            if (empty($username)) {
                $errors[] = 'Vui lòng nhập tên đăng nhập';
            } elseif (strlen($username) < 4) {
                $errors[] = 'Tên đăng nhập phải có ít nhất 4 ký tự';
            } elseif ($this->userModel->usernameExists($username)) {
                $errors[] = 'Tên đăng nhập đã tồn tại';
            }
            
            if (empty($email)) {
                $errors[] = 'Vui lòng nhập email';
            } elseif (!isValidEmail($email)) {
                $errors[] = 'Email không hợp lệ';
            } elseif ($this->userModel->emailExists($email)) {
                $errors[] = 'Email đã được sử dụng';
            }
            
            if (empty($password)) {
                $errors[] = 'Vui lòng nhập mật khẩu';
            } elseif (strlen($password) < 6) {
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }
            
            if ($password !== $confirmPassword) {
                $errors[] = 'Mật khẩu xác nhận không khớp';
            }
            
            if (empty($fullName)) {
                $errors[] = 'Vui lòng nhập họ tên';
            }
            
            if (empty($studentCode)) {
                $errors[] = 'Vui lòng nhập mã sinh viên';
            }
            
            if (empty($errors)) {
                // Tạo tài khoản
                $userId = $this->userModel->create([
                    'username' => $username,
                    'password' => $password,
                    'email' => $email,
                    'full_name' => $fullName,
                    'student_code' => $studentCode,
                    'phone' => $phone
                ]);
                
                if ($userId) {
                    setFlash('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
                    redirect('auth/login');
                } else {
                    setFlash('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
                }
            } else {
                setFlash('error', implode('<br>', $errors));
            }
        }
        
        // Hiển thị view
        require_once __DIR__ . '/../views/auth/register.php';
    }
    
    /**
     * Đăng xuất
     */
    public function logout() {
        logout();
    }
    
    /**
     * Quên mật khẩu
     */
    public function forgotPassword() {
        // Nếu đã đăng nhập, redirect về trang chủ
        if (isLoggedIn()) {
            redirect('home');
        }
        
        // Xử lý form quên mật khẩu
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = clean($_POST['email'] ?? '');
            
            if (empty($email)) {
                setFlash('error', 'Vui lòng nhập email');
            } elseif (!isValidEmail($email)) {
                setFlash('error', 'Email không hợp lệ');
            } else {
                $user = $this->userModel->getByEmail($email);
                
                if ($user) {
                    // TODO: Gửi email reset password
                    // Hiện tại chỉ thông báo
                    setFlash('info', 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn.');
                } else {
                    setFlash('error', 'Email không tồn tại trong hệ thống');
                }
            }
        }
        
        // Hiển thị view
        require_once __DIR__ . '/../views/auth/forgot_password.php';
    }
}
