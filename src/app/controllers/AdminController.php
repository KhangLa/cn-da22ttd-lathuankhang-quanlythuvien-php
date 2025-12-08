<?php
/**
 * AdminController - Quản lý hệ thống dành cho Admin
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Borrow.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/Settings.php';
require_once __DIR__ . '/../models/Librarian.php';
require_once __DIR__ . '/../../includes/auth_middleware.php';

class AdminController {
    private $userModel;
    private $bookModel;
    private $borrowModel;
    private $categoryModel;
    private $notificationModel;
    private $settingsModel;
    private $librarianModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->bookModel = new Book();
        $this->borrowModel = new Borrow();
        $this->categoryModel = new Category();
        $this->notificationModel = new Notification();
        $this->settingsModel = new Settings();
        $this->librarianModel = new Librarian();
        
        // Yêu cầu quyền admin
        requireAdmin();
    }
    
    /**
     * Dashboard admin
     */
    public function dashboard() {
        $data = [];
        
        // Thống kê tổng quan
        $data['total_books'] = $this->bookModel->countAll();
        $data['available_books'] = $this->bookModel->countAvailable();
        $data['total_students'] = $this->userModel->countAll();
        $data['active_borrows'] = $this->borrowModel->countByStatus('borrowed');
        $data['overdue_borrows'] = count($this->borrowModel->getOverdueBooks());
        $data['total_categories'] = $this->categoryModel->countAll();
        
        // Dữ liệu gần đây
        $data['recent_borrows'] = $this->borrowModel->getRecent(10);
        $data['overdue_books'] = $this->borrowModel->getOverdueBooks();
        $data['popular_books'] = $this->bookModel->getPopular(5);
        
        // Thống kê theo tháng
        $data['monthly_stats'] = $this->borrowModel->getMonthlyStats();
        
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }
    
    /**
     * Quản lý sinh viên
     */
    public function students() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 20;
        $keyword = isset($_GET['search']) ? clean($_GET['search']) : '';
        
        if ($keyword) {
            $students = $this->userModel->search($keyword, $perPage, ($page - 1) * $perPage);
            $totalStudents = count($this->userModel->search($keyword));
        } else {
            $students = $this->userModel->getAll($perPage, ($page - 1) * $perPage);
            $totalStudents = $this->userModel->countAll();
        }
        
        $data = [];
        $data['students'] = $students;
        $data['pagination'] = paginate($totalStudents, $perPage, $page);
        $data['search'] = $keyword;
        
        require_once __DIR__ . '/../views/admin/students.php';
    }
    
    /**
     * Thêm sinh viên
     */
    public function addStudent() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = clean($_POST['username'] ?? '');
            $email = clean($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $fullName = clean($_POST['full_name'] ?? '');
            $studentCode = clean($_POST['student_code'] ?? '');
            $classCode = clean($_POST['class_code'] ?? '');
            $phone = clean($_POST['phone'] ?? '');
            $address = clean($_POST['address'] ?? '');
            $status = clean($_POST['status'] ?? 'active');
            
            $errors = [];
            
            if (empty($username) || strlen($username) < 4) {
                $errors[] = 'Tên đăng nhập phải có ít nhất 4 ký tự';
            } elseif ($this->userModel->usernameExists($username)) {
                $errors[] = 'Tên đăng nhập đã tồn tại';
            }
            
            if (empty($email) || !isValidEmail($email)) {
                $errors[] = 'Email không hợp lệ';
            } elseif ($this->userModel->emailExists($email)) {
                $errors[] = 'Email đã được sử dụng';
            }
            
            if (empty($password) || strlen($password) < 6) {
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }
            
            if (empty($fullName)) {
                $errors[] = 'Vui lòng nhập họ tên';
            }
            
            if (empty($studentCode)) {
                $errors[] = 'Vui lòng nhập mã sinh viên';
            }
            
            if (empty($errors)) {
                $userId = $this->userModel->create([
                    'username' => $username,
                    'password' => $password,
                    'email' => $email,
                    'full_name' => $fullName,
                    'student_code' => $studentCode,
                    'class_code' => $classCode,
                    'phone' => $phone,
                    'address' => $address
                ]);
                
                if ($userId) {
                    // Cập nhật status nếu khác active
                    if ($status !== 'active') {
                        $this->userModel->update($userId, ['status' => $status]);
                    }
                    
                    setFlash('success', 'Thêm sinh viên thành công');
                    redirect('admin/students');
                } else {
                    setFlash('error', 'Có lỗi xảy ra');
                }
            } else {
                setFlash('error', implode('<br>', $errors));
            }
        }
        
        require_once __DIR__ . '/../views/admin/student_form.php';
    }
    
    /**
     * Sửa thông tin sinh viên
     */
    public function editStudent($id) {
        $student = $this->userModel->getById($id);
        
        if (!$student) {
            setFlash('error', 'Không tìm thấy sinh viên');
            redirect('admin/students');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = clean($_POST['email'] ?? '');
            $fullName = clean($_POST['full_name'] ?? '');
            $studentCode = clean($_POST['student_code'] ?? '');
            $classCode = clean($_POST['class_code'] ?? '');
            $phone = clean($_POST['phone'] ?? '');
            $address = clean($_POST['address'] ?? '');
            $status = clean($_POST['status'] ?? 'active');
            $password = $_POST['password'] ?? '';
            
            $errors = [];
            
            if (empty($email) || !isValidEmail($email)) {
                $errors[] = 'Email không hợp lệ';
            } elseif ($this->userModel->emailExists($email, $id)) {
                $errors[] = 'Email đã được sử dụng';
            }
            
            if (empty($fullName)) {
                $errors[] = 'Vui lòng nhập họ tên';
            }
            
            if (empty($errors)) {
                $updateData = [
                    'email' => $email,
                    'full_name' => $fullName,
                    'student_code' => $studentCode,
                    'class_code' => $classCode,
                    'phone' => $phone,
                    'address' => $address,
                    'status' => $status
                ];
                
                if (!empty($password) && strlen($password) >= 6) {
                    $updateData['password'] = $password;
                }
                
                if ($this->userModel->update($id, $updateData)) {
                    setFlash('success', 'Cập nhật thông tin thành công');
                    redirect('admin/students');
                } else {
                    setFlash('error', 'Có lỗi xảy ra');
                }
            } else {
                setFlash('error', implode('<br>', $errors));
            }
        }
        
        $data = [];
        $data['student'] = $student;
        
        require_once __DIR__ . '/../views/admin/student_form.php';
    }
    
    /**
     * Xóa sinh viên
     */
    public function deleteStudent($id) {
        // Kiểm tra xem sinh viên có sách đang mượn không
        if ($this->borrowModel->countActiveBorrowsByUser($id) > 0) {
            setFlash('error', 'Không thể xóa sinh viên đang mượn sách');
            redirect('admin/students');
        }
        
        if ($this->userModel->delete($id)) {
            setFlash('success', 'Xóa sinh viên thành công');
        } else {
            setFlash('error', 'Có lỗi xảy ra');
        }
        
        redirect('admin/students');
    }
    
    /**
     * Quản lý thủ thư
     */
    public function librarians() {
        $db = new Database();
        $librarians = $db->fetchAll("SELECT * FROM users WHERE role = 'librarian' ORDER BY created_at DESC");
        
        $data = [];
        $data['librarians'] = $librarians;
        
        require_once __DIR__ . '/../views/admin/librarians.php';
    }
    
    /**
     * Thêm thủ thư
     */
    public function addLibrarian() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = clean($_POST['username'] ?? '');
            $email = clean($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $fullName = clean($_POST['full_name'] ?? '');
            $phone = clean($_POST['phone'] ?? '');
            
            $errors = [];
            
            if (empty($username) || strlen($username) < 4) {
                $errors[] = 'Tên đăng nhập phải có ít nhất 4 ký tự';
            } elseif ($this->userModel->usernameExists($username)) {
                $errors[] = 'Tên đăng nhập đã tồn tại';
            }
            
            if (empty($email) || !isValidEmail($email)) {
                $errors[] = 'Email không hợp lệ';
            } elseif ($this->userModel->emailExists($email)) {
                $errors[] = 'Email đã được sử dụng';
            }
            
            if (empty($password) || strlen($password) < 6) {
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }
            
            if (empty($fullName)) {
                $errors[] = 'Vui lòng nhập họ tên';
            }
            
            if (empty($errors)) {
                $db = new Database();
                $userId = $db->insert('users', [
                    'username' => $username,
                    'password' => hashPassword($password),
                    'email' => $email,
                    'full_name' => $fullName,
                    'phone' => $phone,
                    'role' => 'librarian',
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                
                if ($userId) {
                    setFlash('success', 'Thêm thủ thư thành công');
                    redirect('admin/librarians');
                } else {
                    setFlash('error', 'Có lỗi xảy ra');
                }
            } else {
                setFlash('error', implode('<br>', $errors));
            }
        }
        
        require_once __DIR__ . '/../views/admin/librarian_form.php';
    }
    
    /**
     * Sửa thông tin thủ thư
     */
    public function editLibrarian($id) {
        $librarian = $this->userModel->getById($id);
        
        if (!$librarian || $librarian['role'] !== 'librarian') {
            setFlash('error', 'Không tìm thấy thủ thư');
            redirect('admin/librarians');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = clean($_POST['email'] ?? '');
            $fullName = clean($_POST['full_name'] ?? '');
            $phone = clean($_POST['phone'] ?? '');
            $status = clean($_POST['status'] ?? 'active');
            $password = $_POST['password'] ?? '';
            
            $errors = [];
            
            if (empty($email) || !isValidEmail($email)) {
                $errors[] = 'Email không hợp lệ';
            } elseif ($this->userModel->emailExists($email, $id)) {
                $errors[] = 'Email đã được sử dụng';
            }
            
            if (empty($fullName)) {
                $errors[] = 'Vui lòng nhập họ tên';
            }
            
            if (empty($errors)) {
                $updateData = [
                    'email' => $email,
                    'full_name' => $fullName,
                    'phone' => $phone,
                    'status' => $status
                ];
                
                if (!empty($password) && strlen($password) >= 6) {
                    $updateData['password'] = $password;
                }
                
                if ($this->userModel->update($id, $updateData)) {
                    setFlash('success', 'Cập nhật thông tin thành công');
                    redirect('admin/librarians');
                } else {
                    setFlash('error', 'Có lỗi xảy ra');
                }
            } else {
                setFlash('error', implode('<br>', $errors));
            }
        }
        
        $data = [];
        $data['librarian'] = $librarian;
        
        require_once __DIR__ . '/../views/admin/librarian_form.php';
    }
    
    /**
     * Xóa thủ thư
     */
    public function deleteLibrarian($id) {
        $librarian = $this->userModel->getById($id);
        
        if (!$librarian || $librarian['role'] !== 'librarian') {
            setFlash('error', 'Không tìm thấy thủ thư');
            redirect('admin/librarians');
        }
        
        if ($this->userModel->delete($id)) {
            setFlash('success', 'Xóa thủ thư thành công');
        } else {
            setFlash('error', 'Có lỗi xảy ra');
        }
        
        redirect('admin/librarians');
    }
    
    /**
     * Báo cáo thống kê
     */
    public function reports() {
        $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
        
        $data = [];
        $data['year'] = $year;
        $data['monthly_stats'] = $this->borrowModel->getMonthlyStats($year);
        $data['top_categories'] = $this->categoryModel->getTopCategories(10);
        $data['popular_books'] = $this->bookModel->getPopular(10);
        
        // Thống kê tổng quan
        $data['stats'] = [
            'total_books' => $this->bookModel->countAll(),
            'available_books' => $this->bookModel->countAvailable(),
            'total_students' => $this->userModel->countAll(),
            'total_librarians' => $this->librarianModel->countAll(),
            'total_borrows' => $this->borrowModel->countAll(),
            'active_borrows' => $this->borrowModel->countByStatus('borrowed'),
            'returned_borrows' => $this->borrowModel->countByStatus('returned'),
            'overdue_count' => count($this->borrowModel->getOverdueBooks())
        ];
        
        require_once __DIR__ . '/../views/admin/reports.php';
    }
    
    /**
     * Cài đặt hệ thống
     */
    public function settings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            
            // Lấy dữ liệu từ form
            $settings = [
                // Thông tin liên hệ
                'contact_email' => clean($_POST['contact_email'] ?? ''),
                'contact_phone' => clean($_POST['contact_phone'] ?? ''),
                'contact_address' => clean($_POST['contact_address'] ?? ''),
                
                // Định dạng ngày tháng
                'date_format' => clean($_POST['date_format'] ?? 'd/m/Y'),
                'datetime_format' => clean($_POST['datetime_format'] ?? 'd/m/Y H:i'),
                'time_format' => clean($_POST['time_format'] ?? 'H:i'),
                
                // Múi giờ
                'timezone' => clean($_POST['timezone'] ?? 'Asia/Ho_Chi_Minh'),
                
                // Chế độ bảo trì
                'maintenance_mode' => isset($_POST['maintenance_mode']) ? '1' : '0',
                'maintenance_message' => clean($_POST['maintenance_message'] ?? ''),
                
                // Gia hạn sách
                'allow_renewal' => isset($_POST['allow_renewal']) ? '1' : '0',
                'max_renewal_times' => (int)($_POST['max_renewal_times'] ?? 2),
                'renewal_days' => (int)($_POST['renewal_days'] ?? 7)
            ];
            
            // Validate
            if (!empty($settings['contact_email']) && !filter_var($settings['contact_email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email hỗ trợ không hợp lệ';
            }
            
            if ($settings['max_renewal_times'] < 0 || $settings['max_renewal_times'] > 10) {
                $errors[] = 'Số lần gia hạn tối đa phải từ 0 đến 10';
            }
            
            if ($settings['renewal_days'] < 1 || $settings['renewal_days'] > 30) {
                $errors[] = 'Số ngày gia hạn phải từ 1 đến 30';
            }
            
            if (empty($errors)) {
                if ($this->settingsModel->updateMultiple($settings)) {
                    setFlash('success', 'Cập nhật cài đặt thành công');
                } else {
                    setFlash('error', 'Có lỗi xảy ra khi cập nhật cài đặt');
                }
            } else {
                setFlash('error', implode('<br>', $errors));
            }
            
            redirect('admin/settings');
        }
        
        // Lấy tất cả cài đặt hiện tại
        $data = [];
        $data['settings'] = $this->settingsModel->getAll();
        
        require_once __DIR__ . '/../views/admin/settings.php';
    }
    
    /**
     * Trang gửi thông báo
     */
    public function notifications() {
        // Xử lý gửi thông báo nếu là POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recipient = clean($_POST['recipient'] ?? '');
            $librarianId = isset($_POST['librarian_id']) ? (int)$_POST['librarian_id'] : 0;
            $title = clean($_POST['title'] ?? '');
            $message = clean($_POST['message'] ?? '');
            $type = clean($_POST['type'] ?? 'info');
            
            $errors = [];
            
            if (empty($title)) {
                $errors[] = 'Vui lòng nhập tiêu đề thông báo';
            }
            
            if (empty($message)) {
                $errors[] = 'Vui lòng nhập nội dung thông báo';
            }
            
            if (empty($recipient)) {
                $errors[] = 'Vui lòng chọn người nhận';
            }
            
            if (empty($errors)) {
                $count = 0;
                
                if ($recipient === 'all') {
                    // Gửi cho tất cả thủ thư
                    $count = $this->notificationModel->sendToAllLibrarians($title, $message, $type);
                    setFlash('success', "Đã gửi thông báo đến {$count} thủ thư");
                } elseif ($recipient === 'specific' && $librarianId > 0) {
                    // Gửi cho thủ thư cụ thể
                    $librarian = $this->userModel->getById($librarianId);
                    if ($librarian && $librarian['role'] === 'librarian') {
                        $this->notificationModel->sendToLibrarian($librarianId, $title, $message, $type);
                        setFlash('success', "Đã gửi thông báo đến thủ thư {$librarian['full_name']}");
                    } else {
                        setFlash('error', 'Không tìm thấy thủ thư');
                    }
                } else {
                    $errors[] = 'Vui lòng chọn người nhận hợp lệ';
                }
            }
            
            if (!empty($errors)) {
                setFlash('error', implode('<br>', $errors));
            }
            
            redirect('admin/notifications');
            return;
        }
        
        // Hiển thị form
        require_once __DIR__ . '/../views/admin/notifications.php';
    }
}
