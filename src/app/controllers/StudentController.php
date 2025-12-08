<?php
/**
 * StudentController - Các chức năng dành cho sinh viên
 */

require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Borrow.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/BookReport.php';
require_once __DIR__ . '/../models/Fine.php';
require_once __DIR__ . '/../../includes/auth_middleware.php';

class StudentController {
    private $bookModel;
    private $borrowModel;
    private $categoryModel;
    private $notificationModel;
    private $userModel;
    private $bookReportModel;
    private $fineModel;
    
    public function __construct() {
        $this->bookModel = new Book();
        $this->borrowModel = new Borrow();
        $this->categoryModel = new Category();
        $this->notificationModel = new Notification();
        $this->userModel = new User();
        $this->bookReportModel = new BookReport();
        $this->fineModel = new Fine();
    }
    
    /**
     * Trang chủ sinh viên - Không yêu cầu đăng nhập
     */
    public function home() {
        $data = [];
        $data['latest_books'] = $this->bookModel->getLatest(8);
        $data['popular_books'] = $this->bookModel->getPopular(8);
        $data['categories'] = $this->categoryModel->getAll();
        
        require_once __DIR__ . '/../views/student/home.php';
    }
    
    /**
     * Danh sách sách - Không yêu cầu đăng nhập
     */
    public function books() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 12;
        $keyword = isset($_GET['search']) ? clean($_GET['search']) : '';
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        
        // Tìm kiếm hoặc lấy tất cả
        if ($keyword) {
            $books = $this->bookModel->search($keyword, $categoryId, $perPage, ($page - 1) * $perPage);
            $totalBooks = count($this->bookModel->search($keyword, $categoryId));
        } elseif ($categoryId) {
            $books = $this->bookModel->getByCategory($categoryId, $perPage, ($page - 1) * $perPage);
            $totalBooks = $this->bookModel->countByCategory($categoryId);
        } else {
            $books = $this->bookModel->getAll($perPage, ($page - 1) * $perPage);
            $totalBooks = $this->bookModel->countAll();
        }
        
        $data = [];
        $data['books'] = $books;
        $data['categories'] = $this->categoryModel->getAll();
        $data['pagination'] = paginate($totalBooks, $perPage, $page);
        $data['search'] = $keyword;
        $data['selected_category'] = $categoryId;
        
        require_once __DIR__ . '/../views/student/books.php';
    }
    
    /**
     * Danh sách mượn sách - Yêu cầu đăng nhập
     */
    public function borrowList() {
        requireLogin();
        requireRole('student');
        
        $userId = getCurrentUser()['id'];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        
        $borrows = $this->borrowModel->getByUserId($userId, $perPage, ($page - 1) * $perPage);
        $totalBorrows = count($this->borrowModel->getByUserId($userId));
        
        $data = [];
        $data['borrows'] = $borrows;
        $data['pagination'] = paginate($totalBorrows, $perPage, $page);
        
        require_once __DIR__ . '/../views/student/borrow_list.php';
    }
    
    /**
     * Thông tin cá nhân - Yêu cầu đăng nhập
     */
    public function profile() {
        requireLogin();
        requireRole('student');
        
        $userId = getCurrentUser()['id'];
        
        // Xử lý cập nhật profile
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = clean($_POST['full_name'] ?? '');
            $email = clean($_POST['email'] ?? '');
            $phone = clean($_POST['phone'] ?? '');
            $address = clean($_POST['address'] ?? '');
            $classCode = clean($_POST['class_code'] ?? '');
            
            $updateData = [
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'class_code' => $classCode
            ];
            
            // Upload avatar nếu có
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $upload = uploadFile($_FILES['avatar'], 'user_avatars/');
                if ($upload['success']) {
                    $updateData['avatar'] = $upload['path'];
                }
            }
            
            if ($this->userModel->update($userId, $updateData)) {
                setFlash('success', 'Cập nhật thông tin thành công');
            } else {
                setFlash('error', 'Có lỗi xảy ra');
            }
            
            redirect('student/profile');
        }
        
        $data = [];
        $data['user'] = $this->userModel->getById($userId);
        $data['active_borrows'] = $this->borrowModel->getActiveBorrowsByUser($userId);
        
        require_once __DIR__ . '/../views/student/profile.php';
    }
    
    /**
     * Thông báo - Yêu cầu đăng nhập
     */
    public function notifications() {
        requireLogin();
        requireRole('student');
        
        $userId = getCurrentUser()['id'];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 15;
        
        $notifications = $this->notificationModel->getByUserId($userId, $perPage, ($page - 1) * $perPage);
        $totalNotifications = count($this->notificationModel->getByUserId($userId));
        
        $data = [];
        $data['notifications'] = $notifications;
        $data['pagination'] = paginate($totalNotifications, $perPage, $page);
        
        require_once __DIR__ . '/../views/student/notifications.php';
    }
    
    /**
     * Yêu cầu mượn sách - Yêu cầu đăng nhập
     */
    public function requestBorrow() {
        requireLogin();
        requireRole('student');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('student/books');
        }
        
        $userId = getCurrentUser()['id'];
        $bookId = (int)($_POST['book_id'] ?? 0);
        
        // Kiểm tra sách
        $book = $this->bookModel->getById($bookId);
        if (!$book) {
            setFlash('error', 'Không tìm thấy sách');
            redirect('student/books');
        }
        
        if ($book['available_quantity'] <= 0) {
            setFlash('error', 'Sách đã hết, không thể mượn');
            redirect('book/detail/' . $bookId);
        }
        
        // Kiểm tra số lượng sách đang mượn
        if (!$this->borrowModel->canUserBorrow($userId)) {
            setFlash('error', 'Bạn đã mượn tối đa ' . MAX_BOOKS_PER_USER . ' cuốn sách. Vui lòng trả sách trước khi mượn thêm.');
            redirect('book/detail/' . $bookId);
        }
        
        // Kiểm tra đã mượn sách này chưa trả
        if ($this->borrowModel->hasActiveBorrowForBook($userId, $bookId)) {
            setFlash('error', 'Bạn đang mượn sách này. Vui lòng trả sách trước khi mượn lại.');
            redirect('book/detail/' . $bookId);
        }
        
        $db = new Database();
        $db->beginTransaction();
        
        try {
            // Tạo yêu cầu mượn sách (trạng thái pending)
            $borrowId = $this->borrowModel->createRequest([
                'user_id' => $userId,
                'book_id' => $bookId,
                'notes' => 'Yêu cầu mượn qua hệ thống online'
            ]);
            
            // Gửi thông báo cho sinh viên
            $this->notificationModel->create([
                'user_id' => $userId,
                'title' => 'Yêu cầu mượn sách đã được gửi',
                'message' => "Yêu cầu mượn sách '{$book['title']}' đã được gửi đến thủ thư. Vui lòng chờ xét duyệt.",
                'type' => 'info'
            ]);
            
            // Gửi thông báo cho tất cả thủ thư
            $this->notificationModel->sendToAllLibrarians(
                'Yêu cầu mượn sách mới',
                "Sinh viên có yêu cầu mượn sách '{$book['title']}'. Vui lòng xem và xét duyệt.",
                'info'
            );
            
            $db->commit();
            
            setFlash('success', 'Yêu cầu mượn sách đã được gửi! Vui lòng chờ thủ thư xét duyệt.');
            redirect('student/borrow-list');
        } catch (Exception $e) {
            $db->rollback();
            setFlash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            redirect('book/detail/' . $bookId);
        }
    }
    
    /**
     * Hủy mượn sách
     */
    public function cancelBorrow($id) {
        $userId = getCurrentUser()['id'];
        
        // Kiểm tra quyền hủy
        if (!$this->borrowModel->canCancel($id, $userId)) {
            setFlash('error', 'Không thể hủy phiếu mượn này');
            redirect('student/borrow-list');
        }
        
        $borrow = $this->borrowModel->getById($id);
        $bookId = $borrow['book_id'];
        
        $db = new Database();
        $db->beginTransaction();
        
        try {
            // Hủy phiếu mượn
            $this->borrowModel->cancel($id, $userId);
            
            // Tăng lại số lượng sách
            $this->bookModel->updateAvailableQuantity($bookId, 1);
            
            // Gửi thông báo
            $this->notificationModel->create([
                'user_id' => $userId,
                'title' => 'Hủy mượn sách',
                'message' => "Bạn đã hủy mượn sách '{$borrow['book_title']}' thành công.",
                'type' => 'info'
            ]);
            
            $db->commit();
            
            setFlash('success', 'Đã hủy mượn sách thành công');
        } catch (Exception $e) {
            $db->rollback();
            setFlash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
        
        redirect('student/borrow-list');
    }
    
    /**
     * Đánh dấu thông báo đã đọc
     */
    public function markNotificationRead($id) {
        $this->notificationModel->markAsRead($id);
        redirect('student/notifications');
    }
    
    /**
     * Form báo cáo tình trạng sách
     */
    public function reportBook() {
        requireLogin();
        requireRole('student');
        
        $bookId = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;
        $borrowId = isset($_GET['borrow_id']) ? (int)$_GET['borrow_id'] : 0;
        
        $data = [];
        
        if ($bookId) {
            $data['book'] = $this->bookModel->getById($bookId);
        }
        
        if ($borrowId) {
            $data['borrow'] = $this->borrowModel->getById($borrowId);
            if ($data['borrow']) {
                $data['book'] = $this->bookModel->getById($data['borrow']['book_id']);
            }
        }
        
        // Lấy danh sách sách đã mượn của sinh viên
        $userId = getCurrentUser()['id'];
        $data['borrowed_books'] = $this->borrowModel->getActiveBorrowsByUser($userId);
        
        require_once __DIR__ . '/../views/student/report_book.php';
    }
    
    /**
     * Xử lý gửi báo cáo
     */
    public function submitReport() {
        requireLogin();
        requireRole('student');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('student/report-book');
            return;
        }
        
        $userId = getCurrentUser()['id'];
        $bookId = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
        $borrowId = isset($_POST['borrow_id']) ? (int)$_POST['borrow_id'] : null;
        $reportType = clean($_POST['report_type'] ?? '');
        $description = clean($_POST['description'] ?? '');
        
        $errors = [];
        
        if (empty($bookId)) {
            $errors[] = 'Vui lòng chọn sách cần báo cáo';
        }
        
        if (empty($reportType)) {
            $errors[] = 'Vui lòng chọn loại báo cáo';
        }
        
        if (empty($description) || strlen($description) < 10) {
            $errors[] = 'Mô tả chi tiết phải có ít nhất 10 ký tự';
        }
        
        if (!empty($errors)) {
            setFlash('error', implode('<br>', $errors));
            redirect('student/report-book?book_id=' . $bookId . ($borrowId ? '&borrow_id=' . $borrowId : ''));
            return;
        }
        
        $db = new Database();
        $db->beginTransaction();
        
        try {
            // Tạo báo cáo
            $reportId = $this->bookReportModel->create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'borrow_id' => $borrowId,
                'report_type' => $reportType,
                'description' => $description
            ]);
            
            // Lấy thông tin sách
            $book = $this->bookModel->getById($bookId);
            
            // Gửi thông báo cho tất cả thủ thư
            $this->notificationModel->sendToAllLibrarians(
                'Báo cáo tình trạng sách mới',
                "Sinh viên báo cáo sách '{$book['title']}' có vấn đề: " . $this->getReportTypeText($reportType) . ". Vui lòng xem xét.",
                'warning'
            );
            
            $db->commit();
            
            setFlash('success', 'Đã gửi báo cáo thành công. Thủ thư sẽ xem xét và phản hồi sớm nhất.');
            redirect('student/my-reports');
        } catch (Exception $e) {
            $db->rollback();
            setFlash('error', 'Có lỗi xảy ra khi gửi báo cáo: ' . $e->getMessage());
            redirect('student/report-book');
        }
    }
    
    /**
     * Helper để chuyển đổi report_type thành text tiếng Việt
     */
    private function getReportTypeText($type) {
        $types = [
            'damaged' => 'Sách bị hư hỏng',
            'missing_pages' => 'Thiếu trang',
            'torn' => 'Rách, xé',
            'stained' => 'Bị dơ, ố',
            'lost' => 'Mất sách',
            'other' => 'Khác'
        ];
        return $types[$type] ?? $type;
    }
    
    /**
     * Xem danh sách báo cáo của mình
     */
    public function myReports() {
        requireLogin();
        requireRole('student');
        
        $userId = getCurrentUser()['id'];
        $data = [];
        $data['reports'] = $this->bookReportModel->getByUser($userId);
        
        require_once __DIR__ . '/../views/student/my_reports.php';
    }
    
    /**
     * Xem danh sách phiếu phạt của mình
     */
    public function myFines() {
        requireLogin();
        requireRole('student');
        
        $userId = getCurrentUser()['id'];
        $data = [];
        $data['fines'] = $this->fineModel->getByUser($userId);
        $data['total_unpaid'] = $this->fineModel->getTotalUnpaidByUser($userId);
        
        require_once __DIR__ . '/../views/student/my_fines.php';
    }
}
