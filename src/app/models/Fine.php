<?php
/**
 * Model Fine - Quản lý phiếu phạt
 */

require_once __DIR__ . '/Database.php';

class Fine {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Tạo phiếu phạt mới
     */
    public function create($data) {
        $query = "INSERT INTO fines (user_id, borrow_id, fine_type, amount, reason, created_by) 
                  VALUES (:user_id, :borrow_id, :fine_type, :amount, :reason, :created_by)";
        
        $conn = $this->db->connect();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            'user_id' => $data['user_id'],
            'borrow_id' => $data['borrow_id'] ?? null,
            'fine_type' => $data['fine_type'],
            'amount' => $data['amount'],
            'reason' => $data['reason'],
            'created_by' => $data['created_by']
        ]);
        
        return $conn->lastInsertId();
    }
    
    /**
     * Lấy tất cả phiếu phạt
     */
    public function getAll($limit = null, $offset = 0) {
        $query = "SELECT f.*, 
                         u.full_name as student_name, u.student_code, u.email as student_email,
                         creator.full_name as creator_name,
                         b.book_id, bk.title as book_title
                  FROM fines f
                  JOIN users u ON f.user_id = u.id
                  JOIN users creator ON f.created_by = creator.id
                  LEFT JOIN borrows b ON f.borrow_id = b.id
                  LEFT JOIN books bk ON b.book_id = bk.id
                  ORDER BY f.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }
        
        return $this->db->fetchAll($query);
    }
    
    /**
     * Lấy phiếu phạt theo ID
     */
    public function getById($id) {
        $query = "SELECT f.*, 
                         u.full_name as student_name, u.student_code, u.email as student_email,
                         u.phone as student_phone, u.address as student_address,
                         creator.full_name as creator_name,
                         b.book_id, bk.title as book_title, bk.author
                  FROM fines f
                  JOIN users u ON f.user_id = u.id
                  JOIN users creator ON f.created_by = creator.id
                  LEFT JOIN borrows b ON f.borrow_id = b.id
                  LEFT JOIN books bk ON b.book_id = bk.id
                  WHERE f.id = :id";
        
        return $this->db->fetchOne($query, ['id' => $id]);
    }
    
    /**
     * Lấy phiếu phạt theo sinh viên
     */
    public function getByUser($userId, $limit = null) {
        $query = "SELECT f.*, 
                         creator.full_name as creator_name,
                         b.book_id, bk.title as book_title, bk.author
                  FROM fines f
                  JOIN users creator ON f.created_by = creator.id
                  LEFT JOIN borrows b ON f.borrow_id = b.id
                  LEFT JOIN books bk ON b.book_id = bk.id
                  WHERE f.user_id = :user_id
                  ORDER BY f.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT " . (int)$limit;
        }
        
        return $this->db->fetchAll($query, ['user_id' => $userId]);
    }
    
    /**
     * Lấy phiếu phạt theo trạng thái
     */
    public function getByStatus($status, $limit = null) {
        $query = "SELECT f.*, 
                         u.full_name as student_name, u.student_code,
                         creator.full_name as creator_name,
                         b.book_id, bk.title as book_title
                  FROM fines f
                  JOIN users u ON f.user_id = u.id
                  JOIN users creator ON f.created_by = creator.id
                  LEFT JOIN borrows b ON f.borrow_id = b.id
                  LEFT JOIN books bk ON b.book_id = bk.id
                  WHERE f.status = :status
                  ORDER BY f.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT " . (int)$limit;
        }
        
        return $this->db->fetchAll($query, ['status' => $status]);
    }
    
    /**
     * Đánh dấu phiếu phạt đã thanh toán
     */
    public function markAsPaid($id, $paidAmount, $paymentNote = null) {
        $query = "UPDATE fines 
                  SET status = 'paid', paid_date = NOW(), paid_amount = :paid_amount, payment_note = :payment_note
                  WHERE id = :id";
        
        $conn = $this->db->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute([
            'paid_amount' => $paidAmount,
            'payment_note' => $paymentNote,
            'id' => $id
        ]);
    }
    
    /**
     * Miễn phạt
     */
    public function waive($id, $note = null) {
        $query = "UPDATE fines 
                  SET status = 'waived', payment_note = :note
                  WHERE id = :id";
        
        $conn = $this->db->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute([
            'note' => $note,
            'id' => $id
        ]);
    }
    
    /**
     * Đếm phiếu phạt theo trạng thái
     */
    public function countByStatus($status) {
        $query = "SELECT COUNT(*) as count FROM fines WHERE status = :status";
        $result = $this->db->fetchOne($query, ['status' => $status]);
        return $result['count'] ?? 0;
    }
    
    /**
     * Đếm tất cả phiếu phạt
     */
    public function countAll() {
        $query = "SELECT COUNT(*) as count FROM fines";
        $result = $this->db->fetchOne($query);
        return $result['count'] ?? 0;
    }
    
    /**
     * Tính tổng số tiền phạt chưa thanh toán của sinh viên
     */
    public function getTotalUnpaidByUser($userId) {
        $query = "SELECT SUM(amount) as total FROM fines WHERE user_id = :user_id AND status = 'unpaid'";
        $result = $this->db->fetchOne($query, ['user_id' => $userId]);
        return $result['total'] ?? 0;
    }
    
    /**
     * Kiểm tra sinh viên có phạt chưa thanh toán không
     */
    public function hasUnpaidFines($userId) {
        $total = $this->getTotalUnpaidByUser($userId);
        return $total > 0;
    }
    
    /**
     * Xóa phiếu phạt
     */
    public function delete($id) {
        $query = "DELETE FROM fines WHERE id = :id";
        $conn = $this->db->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Cập nhật phiếu phạt
     */
    public function update($id, $data) {
        $query = "UPDATE fines 
                  SET fine_type = :fine_type, amount = :amount, reason = :reason
                  WHERE id = :id";
        
        $conn = $this->db->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute([
            'fine_type' => $data['fine_type'],
            'amount' => $data['amount'],
            'reason' => $data['reason'],
            'id' => $id
        ]);
    }
}
