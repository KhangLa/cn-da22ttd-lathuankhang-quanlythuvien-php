<?php
/**
 * LibrarianController - Quản lý sách và mượn/trả dành cho Thủ thư
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Borrow.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/BookReport.php';
require_once __DIR__ . '/../models/Fine.php';
require_once __DIR__ . '/../../includes/auth_middleware.php';

class LibrarianController {
    private $userModel;
    private $bookModel;
    private $borrowModel;
    private $categoryModel;
    private $notificationModel;
    private $bookReportModel;
    private $fineModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->bookModel = new Book();
        $this->borrowModel = new Borrow();
        $this->categoryModel = new Category();
        $this->notificationModel = new Notification();
        $this->bookReportModel = new BookReport();
        $this->fineModel = new Fine();
        
        // Yêu cầu quyền librarian hoặc admin
        requireLibrarian();
    }
    
    /**
     * Dashboard thủ thư
     */
    public function dashboard() {
        $data = [];
        
        // Thống kê
        $data['total_books'] = $this->bookModel->countAll();
        $data['available_books'] = $this->bookModel->countAvailable();
        $data['active_borrows'] = $this->borrowModel->countByStatus('borrowed');
        $data['overdue_borrows'] = count($this->borrowModel->getOverdueBooks());
        $data['total_categories'] = count($this->categoryModel->getAll());
        
        // Thống kê sinh viên
        $allUsers = $this->userModel->getAll();
        $students = array_filter($allUsers, function($user) {
            return $user['role'] === 'student';
        });
        $data['total_students'] = count($students);
        
        // Dữ liệu gần đây
        $data['recent_borrows'] = $this->borrowModel->getRecent(10);
        $data['overdue_books'] = $this->borrowModel->getOverdueBooks();
        $data['popular_books'] = $this->bookModel->getPopular(5);
        
        // Thống kê theo tháng cho biểu đồ
        $data['monthly_stats'] = $this->borrowModel->getMonthlyStats();
        
        // Thống kê danh mục cho biểu đồ tròn
        $data['top_categories'] = $this->categoryModel->getTopCategories(8);
        
        // Thống kê phiếu phạt/mượn
        $data['total_fines'] = $this->fineModel->countAll();
        $data['total_borrows_all'] = $this->borrowModel->countAll();
        
        require_once __DIR__ . '/../views/librarian/dashboard.php';
    }
    
    /**
     * Quản lý sách
     */
    public function books() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 20;
        $keyword = isset($_GET['search']) ? clean($_GET['search']) : '';
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        
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
        
        require_once __DIR__ . '/../views/librarian/books.php';
    }
    
    /**
     * Thêm sách
     */
    public function addBook() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = clean($_POST['title'] ?? '');
            $author = clean($_POST['author'] ?? '');
            $publisher = clean($_POST['publisher'] ?? '');
            $publishYear = clean($_POST['publish_year'] ?? '');
            $isbn = clean($_POST['isbn'] ?? '');
            $categoryId = clean($_POST['category_id'] ?? null);
            $quantity = (int)($_POST['quantity'] ?? 0);
            $description = clean($_POST['description'] ?? '');
            $location = clean($_POST['location'] ?? '');
            
            $errors = [];
            
            if (empty($title)) {
                $errors[] = 'Vui lòng nhập tên sách';
            }
            
            if (empty($author)) {
                $errors[] = 'Vui lòng nhập tác giả';
            }
            
            if (!empty($isbn) && $this->bookModel->isbnExists($isbn)) {
                $errors[] = 'ISBN đã tồn tại';
            }
            
            if ($quantity < 0) {
                $errors[] = 'Số lượng không hợp lệ';
            }
            
            if (empty($errors)) {
                $bookData = [
                    'title' => $title,
                    'author' => $author,
                    'publisher' => $publisher,
                    'publish_year' => $publishYear,
                    'isbn' => $isbn,
                    'category_id' => $categoryId,
                    'quantity' => $quantity,
                    'description' => $description,
                    'location' => $location
                ];
                
                // Upload cover image
                if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                    $upload = uploadFile($_FILES['cover_image'], 'book_covers/');
                    if ($upload['success']) {
                        $bookData['cover_image'] = $upload['path'];
                    }
                }
                
                $bookId = $this->bookModel->create($bookData);
                
                if ($bookId) {
                    setFlash('success', 'Thêm sách thành công');
                    redirect('librarian/books');
                } else {
                    setFlash('error', 'Có lỗi xảy ra');
                }
            } else {
                setFlash('error', implode('<br>', $errors));
            }
        }
        
        $data = [];
        $data['categories'] = $this->categoryModel->getAll();
        
        require_once __DIR__ . '/../views/librarian/book_form.php';
    }
    
    /**
     * Sửa sách
     */
    public function editBook($id) {
        $book = $this->bookModel->getById($id);
        
        if (!$book) {
            setFlash('error', 'Không tìm thấy sách');
            redirect('librarian/books');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = clean($_POST['title'] ?? '');
            $author = clean($_POST['author'] ?? '');
            $publisher = clean($_POST['publisher'] ?? '');
            $publishYear = clean($_POST['publish_year'] ?? '');
            $isbn = clean($_POST['isbn'] ?? '');
            $categoryId = clean($_POST['category_id'] ?? null);
            $quantity = (int)($_POST['quantity'] ?? 0);
            $description = clean($_POST['description'] ?? '');
            $location = clean($_POST['location'] ?? '');
            $status = clean($_POST['status'] ?? 'available');
            
            $errors = [];
            
            if (empty($title)) {
                $errors[] = 'Vui lòng nhập tên sách';
            }
            
            if (empty($author)) {
                $errors[] = 'Vui lòng nhập tác giả';
            }
            
            if (!empty($isbn) && $this->bookModel->isbnExists($isbn, $id)) {
                $errors[] = 'ISBN đã tồn tại';
            }
            
            if ($quantity < 0) {
                $errors[] = 'Số lượng không hợp lệ';
            }
            
            if (empty($errors)) {
                $updateData = [
                    'title' => $title,
                    'author' => $author,
                    'publisher' => $publisher,
                    'publish_year' => $publishYear,
                    'isbn' => $isbn,
                    'category_id' => $categoryId,
                    'quantity' => $quantity,
                    'description' => $description,
                    'location' => $location,
                    'status' => $status
                ];
                
                // Upload cover image mới nếu có
                if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                    $upload = uploadFile($_FILES['cover_image'], 'book_covers/');
                    if ($upload['success']) {
                        // Xóa ảnh cũ nếu có
                        if (!empty($book['cover_image'])) {
                            deleteFile($book['cover_image']);
                        }
                        $updateData['cover_image'] = $upload['path'];
                    }
                }
                
                if ($this->bookModel->update($id, $updateData)) {
                    setFlash('success', 'Cập nhật sách thành công');
                    redirect('librarian/books');
                } else {
                    setFlash('error', 'Có lỗi xảy ra');
                }
            } else {
                setFlash('error', implode('<br>', $errors));
            }
        }
        
        $data = [];
        $data['book'] = $book;
        $data['categories'] = $this->categoryModel->getAll();
        
        require_once __DIR__ . '/../views/librarian/book_form.php';
    }
    
    /**
     * Xóa sách
     */
    public function deleteBook($id) {
        $book = $this->bookModel->getById($id);
        
        if (!$book) {
            setFlash('error', 'Không tìm thấy sách');
            redirect('librarian/books');
        }
        
        // Kiểm tra xem có ai đang mượn sách này không
        $db = new Database();
        $activeBorrows = $db->count('borrows', 'book_id = :book_id AND status = :status', 
            ['book_id' => $id, 'status' => 'borrowed']);
        
        if ($activeBorrows > 0) {
            setFlash('error', 'Không thể xóa sách đang được mượn');
            redirect('librarian/books');
        }
        
        // Xóa ảnh nếu có
        if (!empty($book['cover_image'])) {
            deleteFile($book['cover_image']);
        }
        
        if ($this->bookModel->delete($id)) {
            setFlash('success', 'Xóa sách thành công');
        } else {
            setFlash('error', 'Có lỗi xảy ra');
        }
        
        redirect('librarian/books');
    }
    
    /**
     * Quản lý danh mục
     */
    public function categories() {
        $categories = $this->categoryModel->getAll();
        
        $data = [];
        $data['categories'] = $categories;
        
        require_once __DIR__ . '/../views/librarian/categories.php';
    }
    
    /**
     * Thêm danh mục
     */
    public function addCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = clean($_POST['name'] ?? '');
            $description = clean($_POST['description'] ?? '');
            
            $errors = [];
            
            if (empty($name)) {
                $errors[] = 'Vui lòng nhập tên danh mục';
            } elseif ($this->categoryModel->nameExists($name)) {
                $errors[] = 'Tên danh mục đã tồn tại';
            }
            
            if (empty($errors)) {
                $categoryId = $this->categoryModel->create([
                    'name' => $name,
                    'description' => $description
                ]);
                
                if ($categoryId) {
                    setFlash('success', 'Thêm danh mục thành công');
                    redirect('librarian/categories');
                } else {
                    setFlash('error', 'Có lỗi xảy ra');
                }
            } else {
                setFlash('error', implode('<br>', $errors));
            }
        }
        
        require_once __DIR__ . '/../views/librarian/category_form.php';
    }
    
    /**
     * Sửa danh mục
     */
    public function editCategory($id) {
        $category = $this->categoryModel->getById($id);
        
        if (!$category) {
            setFlash('error', 'Không tìm thấy danh mục');
            redirect('librarian/categories');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = clean($_POST['name'] ?? '');
            $description = clean($_POST['description'] ?? '');
            
            $errors = [];
            
            if (empty($name)) {
                $errors[] = 'Vui lòng nhập tên danh mục';
            } elseif ($this->categoryModel->nameExists($name, $id)) {
                $errors[] = 'Tên danh mục đã tồn tại';
            }
            
            if (empty($errors)) {
                if ($this->categoryModel->update($id, [
                    'name' => $name,
                    'description' => $description
                ])) {
                    setFlash('success', 'Cập nhật danh mục thành công');
                    redirect('librarian/categories');
                } else {
                    setFlash('error', 'Có lỗi xảy ra');
                }
            } else {
                setFlash('error', implode('<br>', $errors));
            }
        }
        
        $data = [];
        $data['category'] = $category;
        
        require_once __DIR__ . '/../views/librarian/category_form.php';
    }
    
    /**
     * Xóa danh mục
     */
    public function deleteCategory($id) {
        if ($this->categoryModel->delete($id)) {
            setFlash('success', 'Xóa danh mục thành công');
        } else {
            setFlash('error', 'Không thể xóa danh mục có sách');
        }
        
        redirect('librarian/categories');
    }
    
    /**
     * Quản lý mượn/trả sách
     */
    public function borrows() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 20;
        $status = isset($_GET['status']) ? clean($_GET['status']) : '';
        
        $db = new Database();
        
        if ($status) {
            $sql = "SELECT br.*, u.full_name as user_name, u.student_code, 
                           b.title as book_title, b.author as book_author
                    FROM borrows br
                    JOIN users u ON br.user_id = u.id
                    JOIN books b ON br.book_id = b.id
                    WHERE br.status = :status
                    ORDER BY br.created_at DESC
                    LIMIT {$perPage} OFFSET " . (($page - 1) * $perPage);
            
            $borrows = $db->fetchAll($sql, ['status' => $status]);
            $totalBorrows = $this->borrowModel->countByStatus($status);
        } else {
            $borrows = $this->borrowModel->getAll($perPage, ($page - 1) * $perPage);
            $totalBorrows = $this->borrowModel->countAll();
        }
        
        $data = [];
        $data['borrows'] = $borrows;
        $data['pagination'] = paginate($totalBorrows, $perPage, $page);
        $data['selected_status'] = $status;
        
        require_once __DIR__ . '/../views/librarian/borrows.php';
    }
    
    /**
     * Tạo phiếu mượn mới
     */
    public function createBorrow() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentCode = clean($_POST['student_code'] ?? '');
            $bookId = (int)($_POST['book_id'] ?? 0);
            $notes = clean($_POST['notes'] ?? '');
            
            $errors = [];
            
            // Tìm sinh viên theo mã
            $db = new Database();
            $student = $db->fetchOne("SELECT * FROM users WHERE student_code = :code AND role = 'student'", 
                ['code' => $studentCode]);
            
            if (!$student) {
                $errors[] = 'Không tìm thấy sinh viên với mã: ' . $studentCode;
            } elseif ($student['status'] !== 'active') {
                $errors[] = 'Tài khoản sinh viên không hoạt động';
            }
            
            // Kiểm tra sách
            $book = $this->bookModel->getById($bookId);
            if (!$book) {
                $errors[] = 'Không tìm thấy sách';
            } elseif ($book['available_quantity'] <= 0) {
                $errors[] = 'Sách đã hết';
            }
            
            // Kiểm tra số lượng sách đang mượn
            if ($student && !$this->borrowModel->canUserBorrow($student['id'])) {
                $errors[] = 'Sinh viên đã mượn tối đa ' . MAX_BOOKS_PER_USER . ' cuốn sách';
            }
            
            // Kiểm tra đã mượn sách này chưa trả
            if ($student && $book && $this->borrowModel->hasActiveBorrowForBook($student['id'], $bookId)) {
                $errors[] = 'Sinh viên đang mượn sách này';
            }
            
            if (empty($errors)) {
                $db->beginTransaction();
                
                try {
                    // Tạo phiếu mượn
                    $borrowId = $this->borrowModel->create([
                        'user_id' => $student['id'],
                        'book_id' => $bookId,
                        'notes' => $notes
                    ]);
                    
                    // Giảm số lượng sách
                    $this->bookModel->updateAvailableQuantity($bookId, -1);
                    
                    // Gửi thông báo
                    $this->notificationModel->create([
                        'user_id' => $student['id'],
                        'title' => 'Mượn sách thành công',
                        'message' => "Bạn đã mượn sách '{$book['title']}'. Vui lòng trả sách trước ngày " . 
                                    formatDate(date('Y-m-d', strtotime('+' . MAX_BORROW_DAYS . ' days'))),
                        'type' => 'success'
                    ]);
                    
                    $db->commit();
                    
                    setFlash('success', 'Tạo phiếu mượn thành công');
                    redirect('librarian/borrows');
                } catch (Exception $e) {
                    $db->rollback();
                    setFlash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
                }
            } else {
                setFlash('error', implode('<br>', $errors));
            }
        }
        
        $data = [];
        $data['books'] = $this->bookModel->getAvailableBooks();
        
        require_once __DIR__ . '/../views/librarian/borrow_form.php';
    }
    
    /**
     * Trả sách
     */
    public function returnBook($id) {
        $borrow = $this->borrowModel->getById($id);
        
        if (!$borrow) {
            setFlash('error', 'Không tìm thấy phiếu mượn');
            redirect('librarian/borrows');
        }
        
        if ($borrow['status'] !== 'borrowed') {
            setFlash('error', 'Sách đã được trả');
            redirect('librarian/borrows');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $notes = clean($_POST['notes'] ?? '');
            $fine = (int)($_POST['fine_amount'] ?? 0);
            
            $db = new Database();
            $db->beginTransaction();
            
            try {
                // Cập nhật phiếu mượn
                $this->borrowModel->returnBook($id, $notes);
                
                // Cập nhật tiền phạt nếu có
                if ($fine > 0) {
                    $this->borrowModel->updateFine($id, $fine);
                }
                
                // Tăng số lượng sách
                $this->bookModel->updateAvailableQuantity($borrow['book_id'], 1);
                
                // Gửi thông báo
                $this->notificationModel->create([
                    'user_id' => $borrow['user_id'],
                    'title' => 'Trả sách thành công',
                    'message' => "Bạn đã trả sách '{$borrow['book_title']}'" . 
                                ($fine > 0 ? ". Tiền phạt: " . formatMoney($fine) : ""),
                    'type' => 'success'
                ]);
                
                $db->commit();
                
                setFlash('success', 'Trả sách thành công');
                redirect('librarian/borrows');
            } catch (Exception $e) {
                $db->rollback();
                setFlash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            }
        }
        
        // Tính tiền phạt tự động
        $overdueDays = calculateOverdueDays($borrow['due_date']);
        $calculatedFine = $overdueDays > 0 ? calculateFine($overdueDays) : 0;
        
        $data = [];
        $data['borrow'] = $borrow;
        $data['overdue_days'] = $overdueDays;
        $data['calculated_fine'] = $calculatedFine;
        
        require_once __DIR__ . '/../views/librarian/return_form.php';
    }
    
    /**
     * Gửi thông báo
     */
    public function notifications() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = clean($_POST['title'] ?? '');
            $message = clean($_POST['message'] ?? '');
            $type = clean($_POST['type'] ?? 'info');
            
            if (empty($title) || empty($message)) {
                setFlash('error', 'Vui lòng nhập đầy đủ thông tin');
            } else {
                $count = $this->notificationModel->sendToAllStudents($title, $message, $type);
                setFlash('success', "Đã gửi thông báo đến {$count} sinh viên");
            }
            
            redirect('librarian/notifications');
        }
        
        require_once __DIR__ . '/../views/librarian/notifications.php';
    }
    
    /**
     * Xem thông báo của thủ thư (nhận từ admin)
     */
    public function myNotifications() {
        $currentUser = getCurrentUser();
        $notifications = $this->notificationModel->getByUserId($currentUser['id'], 50);
        
        $data = [];
        $data['notifications'] = $notifications;
        
        require_once __DIR__ . '/../views/librarian/my_notifications.php';
    }
    
    /**
     * Đánh dấu thông báo đã đọc
     */
    public function markNotificationRead($id) {
        $this->notificationModel->markAsRead($id);
        redirect('librarian/myNotifications');
    }
    
    /**
     * Đánh dấu tất cả thông báo đã đọc
     */
    public function markAllNotificationsRead() {
        $currentUser = getCurrentUser();
        $this->notificationModel->markAllAsReadByUser($currentUser['id']);
        setFlash('success', 'Đã đánh dấu tất cả thông báo là đã đọc');
        redirect('librarian/myNotifications');
    }
    
    /**
     * Danh sách yêu cầu mượn sách chờ duyệt
     */
    public function borrowRequests() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 20;
        
        $requests = $this->borrowModel->getPendingRequests($perPage, ($page - 1) * $perPage);
        $totalRequests = $this->borrowModel->countPendingRequests();
        
        $data = [];
        $data['requests'] = $requests;
        $data['pagination'] = paginate($totalRequests, $perPage, $page);
        
        require_once __DIR__ . '/../views/librarian/borrow_requests.php';
    }
    
    /**
     * Duyệt yêu cầu mượn sách
     */
    public function approveRequest($id) {
        $request = $this->borrowModel->getById($id);
        
        if (!$request || $request['status'] !== 'pending') {
            setFlash('error', 'Yêu cầu không hợp lệ');
            redirect('librarian/borrowRequests');
        }
        
        $currentUser = getCurrentUser();
        $db = new Database();
        $db->beginTransaction();
        
        try {
            // Duyệt yêu cầu
            $this->borrowModel->approveRequest($id, $currentUser['id']);
            
            // Giảm số lượng sách có sẵn
            $this->bookModel->updateAvailableQuantity($request['book_id'], -1);
            
            // Gửi thông báo cho sinh viên
            $this->notificationModel->create([
                'user_id' => $request['user_id'],
                'title' => 'Yêu cầu mượn sách được chấp nhận',
                'message' => "Yêu cầu mượn sách '{$request['book_title']}' đã được duyệt. Vui lòng đến thư viện để nhận sách trong vòng 3 ngày.",
                'type' => 'success'
            ]);
            
            $db->commit();
            setFlash('success', 'Đã duyệt yêu cầu mượn sách');
        } catch (Exception $e) {
            $db->rollback();
            setFlash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
        
        redirect('librarian/borrowRequests');
    }
    
    /**
     * Từ chối yêu cầu mượn sách
     */
    public function rejectRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('librarian/borrowRequests');
        }
        
        $request = $this->borrowModel->getById($id);
        
        if (!$request || $request['status'] !== 'pending') {
            setFlash('error', 'Yêu cầu không hợp lệ');
            redirect('librarian/borrowRequests');
        }
        
        $reason = clean($_POST['reason'] ?? 'Không đủ điều kiện mượn sách');
        $currentUser = getCurrentUser();
        
        // Từ chối yêu cầu
        $this->borrowModel->rejectRequest($id, $currentUser['id'], $reason);
        
        // Gửi thông báo cho sinh viên
        $this->notificationModel->create([
            'user_id' => $request['user_id'],
            'title' => 'Yêu cầu mượn sách bị từ chối',
            'message' => "Yêu cầu mượn sách '{$request['book_title']}' đã bị từ chối. Lý do: {$reason}",
            'type' => 'warning'
        ]);
        
        setFlash('success', 'Đã từ chối yêu cầu mượn sách');
        redirect('librarian/borrowRequests');
    }
    
    /**
     * Danh sách báo cáo tình trạng sách
     */
    public function bookReports() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 20;
        $status = isset($_GET['status']) ? clean($_GET['status']) : '';
        
        $data = [];
        
        if ($status) {
            $reports = $this->bookReportModel->getByStatus($status, $perPage);
            $totalReports = $this->bookReportModel->countByStatus($status);
        } else {
            $reports = $this->bookReportModel->getAll($perPage, ($page - 1) * $perPage);
            $totalReports = $this->bookReportModel->countAll();
        }
        
        $data['reports'] = $reports;
        $data['pagination'] = paginate($totalReports, $perPage, $page);
        $data['selected_status'] = $status;
        $data['pending_count'] = $this->bookReportModel->countByStatus('pending');
        $data['reviewed_count'] = $this->bookReportModel->countByStatus('reviewed');
        
        require_once __DIR__ . '/../views/librarian/book_reports.php';
    }
    
    /**
     * Chi tiết báo cáo và xử lý
     */
    public function viewReport($id) {
        $report = $this->bookReportModel->getById($id);
        
        if (!$report) {
            setFlash('error', 'Không tìm thấy báo cáo');
            redirect('librarian/book-reports');
        }
        
        $data = [];
        $data['report'] = $report;
        $data['book'] = $this->bookModel->getById($report['book_id']);
        
        if ($report['borrow_id']) {
            $data['borrow'] = $this->borrowModel->getById($report['borrow_id']);
        }
        
        require_once __DIR__ . '/../views/librarian/report_detail.php';
    }
    
    /**
     * Cập nhật trạng thái báo cáo
     */
    public function updateReportStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('librarian/book-reports');
        }
        
        $status = clean($_POST['status'] ?? '');
        $librarianNote = clean($_POST['librarian_note'] ?? '');
        
        if (!in_array($status, ['reviewed', 'resolved', 'rejected'])) {
            setFlash('error', 'Trạng thái không hợp lệ');
            redirect('librarian/view-report/' . $id);
        }
        
        $report = $this->bookReportModel->getById($id);
        if (!$report) {
            setFlash('error', 'Không tìm thấy báo cáo');
            redirect('librarian/book-reports');
        }
        
        $currentUser = getCurrentUser();
        
        $db = new Database();
        $db->beginTransaction();
        
        try {
            // Cập nhật trạng thái
            $this->bookReportModel->updateStatus($id, $status, $currentUser['id'], $librarianNote);
            
            // Gửi thông báo cho sinh viên
            $statusText = [
                'reviewed' => 'đang được xem xét',
                'resolved' => 'đã được giải quyết',
                'rejected' => 'bị từ chối'
            ];
            
            $this->notificationModel->create([
                'user_id' => $report['user_id'],
                'title' => 'Cập nhật báo cáo tình trạng sách',
                'message' => "Báo cáo của bạn về sách '{$report['book_title']}' {$statusText[$status]}. " . 
                            ($librarianNote ? "Ghi chú: {$librarianNote}" : ""),
                'type' => $status === 'resolved' ? 'success' : 'info'
            ]);
            
            $db->commit();
            
            setFlash('success', 'Đã cập nhật trạng thái báo cáo');
            redirect('librarian/book-reports');
        } catch (Exception $e) {
            $db->rollback();
            setFlash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            redirect('librarian/view-report/' . $id);
        }
    }
    
    /**
     * Danh sách phiếu phạt
     */
    public function fines() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 20;
        $status = isset($_GET['status']) ? clean($_GET['status']) : '';
        
        $data = [];
        
        if ($status) {
            $fines = $this->fineModel->getByStatus($status, $perPage);
            $totalFines = $this->fineModel->countByStatus($status);
        } else {
            $fines = $this->fineModel->getAll($perPage, ($page - 1) * $perPage);
            $totalFines = $this->fineModel->countAll();
        }
        
        $data['fines'] = $fines;
        $data['pagination'] = paginate($totalFines, $perPage, $page);
        $data['selected_status'] = $status;
        $data['unpaid_count'] = $this->fineModel->countByStatus('unpaid');
        $data['paid_count'] = $this->fineModel->countByStatus('paid');
        
        require_once __DIR__ . '/../views/librarian/fines.php';
    }
    
    /**
     * Tạo phiếu phạt
     */
    public function createFine() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = (int)($_POST['user_id'] ?? 0);
            $borrowId = !empty($_POST['borrow_id']) ? (int)$_POST['borrow_id'] : null;
            $fineType = clean($_POST['fine_type'] ?? '');
            $amount = (float)($_POST['amount'] ?? 0);
            $reason = clean($_POST['reason'] ?? '');
            
            $errors = [];
            
            if (empty($userId)) {
                $errors[] = 'Vui lòng chọn sinh viên';
            }
            
            if (empty($fineType)) {
                $errors[] = 'Vui lòng chọn loại phạt';
            }
            
            if ($amount <= 0) {
                $errors[] = 'Số tiền phạt phải lớn hơn 0';
            }
            
            if (empty($reason) || strlen($reason) < 10) {
                $errors[] = 'Lý do phạt phải có ít nhất 10 ký tự';
            }
            
            if (!empty($errors)) {
                setFlash('error', implode('<br>', $errors));
                redirect('librarian/create-fine');
            }
            
            $currentUser = getCurrentUser();
            
            $db = new Database();
            $db->beginTransaction();
            
            try {
                // Tạo phiếu phạt
                $fineId = $this->fineModel->create([
                    'user_id' => $userId,
                    'borrow_id' => $borrowId,
                    'fine_type' => $fineType,
                    'amount' => $amount,
                    'reason' => $reason,
                    'created_by' => $currentUser['id']
                ]);
                
                // Lấy thông tin sinh viên
                $student = $this->userModel->getById($userId);
                
                // Gửi thông báo cho sinh viên
                $this->notificationModel->create([
                    'user_id' => $userId,
                    'title' => 'Bạn có phiếu phạt mới',
                    'message' => "Bạn có phiếu phạt mới với số tiền: " . number_format($amount) . " VNĐ. Lý do: {$reason}",
                    'type' => 'warning'
                ]);
                
                $db->commit();
                
                setFlash('success', 'Đã tạo phiếu phạt thành công');
                redirect('librarian/fines');
            } catch (Exception $e) {
                $db->rollback();
                setFlash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
                redirect('librarian/create-fine');
            }
        }
        
        // Lấy danh sách sinh viên
        $data = [];
        $data['students'] = $this->userModel->getByRole('student');
        
        require_once __DIR__ . '/../views/librarian/fine_form.php';
    }
    
    /**
     * Xem chi tiết phiếu phạt
     */
    public function viewFine($id) {
        $fine = $this->fineModel->getById($id);
        
        if (!$fine) {
            setFlash('error', 'Không tìm thấy phiếu phạt');
            redirect('librarian/fines');
        }
        
        $data = [];
        $data['fine'] = $fine;
        
        require_once __DIR__ . '/../views/librarian/fine_detail.php';
    }
    
    /**
     * Xác nhận thanh toán phiếu phạt
     */
    public function markFinePaid($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('librarian/fines');
        }
        
        $paidAmount = (float)($_POST['paid_amount'] ?? 0);
        $paymentNote = clean($_POST['payment_note'] ?? '');
        
        $fine = $this->fineModel->getById($id);
        if (!$fine) {
            setFlash('error', 'Không tìm thấy phiếu phạt');
            redirect('librarian/fines');
        }
        
        $db = new Database();
        $db->beginTransaction();
        
        try {
            // Đánh dấu đã thanh toán
            $this->fineModel->markAsPaid($id, $paidAmount, $paymentNote);
            
            // Gửi thông báo cho sinh viên
            $this->notificationModel->create([
                'user_id' => $fine['user_id'],
                'title' => 'Phiếu phạt đã được xác nhận thanh toán',
                'message' => "Phiếu phạt số #{$id} đã được xác nhận thanh toán với số tiền: " . number_format($paidAmount) . " VNĐ.",
                'type' => 'success'
            ]);
            
            $db->commit();
            
            setFlash('success', 'Đã xác nhận thanh toán phiếu phạt');
            redirect('librarian/fines');
        } catch (Exception $e) {
            $db->rollback();
            setFlash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            redirect('librarian/view-fine/' . $id);
        }
    }
    
    /**
     * Miễn phạt
     */
    public function waiveFine($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('librarian/fines');
        }
        
        $note = clean($_POST['waive_note'] ?? '');
        
        $fine = $this->fineModel->getById($id);
        if (!$fine) {
            setFlash('error', 'Không tìm thấy phiếu phạt');
            redirect('librarian/fines');
        }
        
        $db = new Database();
        $db->beginTransaction();
        
        try {
            // Miễn phạt
            $this->fineModel->waive($id, $note);
            
            // Gửi thông báo cho sinh viên
            $this->notificationModel->create([
                'user_id' => $fine['user_id'],
                'title' => 'Phiếu phạt đã được miễn',
                'message' => "Phiếu phạt số #{$id} đã được miễn. " . ($note ? "Ghi chú: {$note}" : ""),
                'type' => 'success'
            ]);
            
            $db->commit();
            
            setFlash('success', 'Đã miễn phạt');
            redirect('librarian/fines');
        } catch (Exception $e) {
            $db->rollback();
            setFlash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            redirect('librarian/view-fine/' . $id);
        }
    }
}
