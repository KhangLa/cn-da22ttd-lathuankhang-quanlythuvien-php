<?php
/**
 * Model Borrow - Quản lý mượn/trả sách
 */

require_once __DIR__ . '/Database.php';

class Borrow {
    private $db;
    private $table = 'borrows';
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Lấy tất cả phiếu mượn
     */
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT br.*, u.full_name as user_name, u.student_code, 
                       b.title as book_title, b.author as book_author
                FROM {$this->table} br
                JOIN users u ON br.user_id = u.id
                JOIN books b ON br.book_id = b.id
                ORDER BY br.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy phiếu mượn theo ID
     */
    public function getById($id) {
        $sql = "SELECT br.*, u.full_name as user_name, u.student_code, u.email, u.phone,
                       b.title as book_title, b.author as book_author, b.isbn
                FROM {$this->table} br
                JOIN users u ON br.user_id = u.id
                JOIN books b ON br.book_id = b.id
                WHERE br.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    /**
     * Lấy lịch sử mượn của user
     */
    public function getByUserId($userId, $limit = null, $offset = 0) {
        $sql = "SELECT br.*, b.title as book_title, b.author as book_author, b.cover_image
                FROM {$this->table} br
                JOIN books b ON br.book_id = b.id
                WHERE br.user_id = :user_id
                ORDER BY br.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }
    
    /**
     * Lấy sách đang mượn của user
     */
    public function getActiveBorrowsByUser($userId) {
        $sql = "SELECT br.*, b.title as book_title, b.author as book_author, b.cover_image
                FROM {$this->table} br
                JOIN books b ON br.book_id = b.id
                WHERE br.user_id = :user_id AND br.status IN ('borrowed', 'overdue')
                ORDER BY br.borrow_date DESC";
        
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }
    
    /**
     * Tạo phiếu mượn mới
     */
    public function create($data) {
        $insertData = [
            'user_id' => $data['user_id'],
            'book_id' => $data['book_id'],
            'borrow_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+' . MAX_BORROW_DAYS . ' days')),
            'status' => 'borrowed',
            'notes' => $data['notes'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert($this->table, $insertData);
    }
    
    /**
     * Trả sách
     */
    public function returnBook($id, $notes = '') {
        $updateData = [
            'return_date' => date('Y-m-d'),
            'status' => 'returned',
            'notes' => $notes,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->update($this->table, $updateData, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Tính tiền phạt quá hạn
     */
    public function calculateFine($id) {
        $borrow = $this->getById($id);
        
        if ($borrow && $borrow['status'] === 'borrowed') {
            $overdueDays = calculateOverdueDays($borrow['due_date']);
            if ($overdueDays > 0) {
                return calculateFine($overdueDays);
            }
        }
        
        return 0;
    }
    
    /**
     * Cập nhật tiền phạt
     */
    public function updateFine($id, $fine) {
        $updateData = [
            'fine_amount' => $fine,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->update($this->table, $updateData, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Lấy sách quá hạn
     */
    public function getOverdueBooks() {
        $sql = "SELECT br.*, u.full_name as user_name, u.student_code, u.email, u.phone,
                       b.title as book_title, b.author as book_author,
                       DATEDIFF(CURDATE(), br.due_date) as overdue_days
                FROM {$this->table} br
                JOIN users u ON br.user_id = u.id
                JOIN books b ON br.book_id = b.id
                WHERE br.status = 'borrowed' AND br.due_date < CURDATE()
                ORDER BY overdue_days DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Đếm số sách đang mượn của user
     */
    public function countActiveBorrowsByUser($userId) {
        return $this->db->count($this->table, 
            "user_id = :user_id AND status = 'borrowed'", 
            ['user_id' => $userId]
        );
    }
    
    /**
     * Kiểm tra user có thể mượn sách không
     */
    public function canUserBorrow($userId) {
        $activeCount = $this->countActiveBorrowsByUser($userId);
        return $activeCount < MAX_BOOKS_PER_USER;
    }
    
    /**
     * Kiểm tra user đã mượn sách này chưa trả
     */
    public function hasActiveBorrowForBook($userId, $bookId) {
        $count = $this->db->count($this->table, 
            "user_id = :user_id AND book_id = :book_id AND status = 'borrowed'", 
            ['user_id' => $userId, 'book_id' => $bookId]
        );
        return $count > 0;
    }
    
    /**
     * Đếm tổng phiếu mượn
     */
    public function countAll() {
        return $this->db->count($this->table);
    }
    
    /**
     * Đếm theo trạng thái
     */
    public function countByStatus($status) {
        return $this->db->count($this->table, 'status = :status', ['status' => $status]);
    }
    
    /**
     * Thống kê mượn sách theo tháng
     */
    public function getMonthlyStats($year = null) {
        if (!$year) {
            $year = date('Y');
        }
        
        $sql = "SELECT MONTH(borrow_date) as month, COUNT(*) as total
                FROM {$this->table}
                WHERE YEAR(borrow_date) = :year
                GROUP BY MONTH(borrow_date)
                ORDER BY month";
        
        return $this->db->fetchAll($sql, ['year' => $year]);
    }
    
    /**
     * Lấy phiếu mượn gần đây
     */
    public function getRecent($limit = 10) {
        $sql = "SELECT br.*, u.full_name as user_name, u.student_code,
                       b.title as book_title, b.author as book_author
                FROM {$this->table} br
                JOIN users u ON br.user_id = u.id
                JOIN books b ON br.book_id = b.id
                ORDER BY br.created_at DESC
                LIMIT {$limit}";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Hủy phiếu mượn (chỉ cho phép hủy khi mới tạo và chưa nhận sách)
     */
    public function cancel($id, $userId = null) {
        $borrow = $this->getById($id);
        
        if (!$borrow) {
            return false;
        }
        
        // Kiểm tra quyền hủy (nếu có userId)
        if ($userId && $borrow['user_id'] != $userId) {
            return false;
        }
        
        // Chỉ cho phép hủy khi trạng thái là 'borrowed' và mới tạo trong vòng 24h
        if ($borrow['status'] !== 'borrowed') {
            return false;
        }
        
        // Xóa phiếu mượn
        return $this->db->delete($this->table, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Kiểm tra có thể hủy phiếu mượn không
     */
    public function canCancel($id, $userId) {
        $borrow = $this->getById($id);
        
        if (!$borrow || $borrow['user_id'] != $userId) {
            return false;
        }
        
        // Chỉ cho phép hủy khi đang mượn
        return $borrow['status'] === 'borrowed';
    }
    
    /**
     * Tạo yêu cầu mượn sách (trạng thái pending)
     */
    public function createRequest($data) {
        $insertData = [
            'user_id' => $data['user_id'],
            'book_id' => $data['book_id'],
            'borrow_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+' . MAX_BORROW_DAYS . ' days')),
            'status' => 'pending',
            'notes' => $data['notes'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert($this->table, $insertData);
    }
    
    /**
     * Lấy danh sách yêu cầu chờ duyệt
     */
    public function getPendingRequests($limit = null, $offset = 0) {
        $sql = "SELECT br.*, u.full_name as user_name, u.student_code, u.email,
                       b.title as book_title, b.author as book_author, b.available_quantity
                FROM {$this->table} br
                JOIN users u ON br.user_id = u.id
                JOIN books b ON br.book_id = b.id
                WHERE br.status = 'pending'
                ORDER BY br.created_at ASC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Duyệt yêu cầu mượn sách
     */
    public function approveRequest($id, $librarianId) {
        $updateData = [
            'status' => 'borrowed',
            'approved_by' => $librarianId,
            'approved_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->update($this->table, $updateData, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Từ chối yêu cầu mượn sách
     */
    public function rejectRequest($id, $librarianId, $reason = '') {
        $updateData = [
            'status' => 'rejected',
            'approved_by' => $librarianId,
            'approved_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $reason,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->update($this->table, $updateData, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Đếm số yêu cầu chờ duyệt
     */
    public function countPendingRequests() {
        return $this->db->count($this->table, 'status = :status', ['status' => 'pending']);
    }
    
    /**
     * Lấy yêu cầu mượn của user (bao gồm pending và rejected)
     */
    public function getUserRequests($userId, $limit = null, $offset = 0) {
        $sql = "SELECT br.*, b.title as book_title, b.author as book_author, b.cover_image,
                       approver.full_name as approved_by_name
                FROM {$this->table} br
                JOIN books b ON br.book_id = b.id
                LEFT JOIN users approver ON br.approved_by = approver.id
                WHERE br.user_id = :user_id
                ORDER BY br.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }
}
