<?php
/**
 * Model BookReport - Quản lý báo cáo tình trạng sách
 */

require_once __DIR__ . '/Database.php';

class BookReport {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Tạo báo cáo mới
     */
    public function create($data) {
        $query = "INSERT INTO book_reports (user_id, book_id, borrow_id, report_type, description) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $conn = $this->db->connect();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            $data['user_id'],
            $data['book_id'],
            $data['borrow_id'] ?? null,
            $data['report_type'],
            $data['description']
        ]);
        
        return $conn->lastInsertId();
    }
    
    /**
     * Lấy tất cả báo cáo
     */
    public function getAll($limit = null, $offset = 0) {
        $query = "SELECT br.*, 
                         u.full_name as student_name, u.student_code,
                         b.title as book_title, b.author,
                         reviewer.full_name as reviewer_name
                  FROM book_reports br
                  JOIN users u ON br.user_id = u.id
                  JOIN books b ON br.book_id = b.id
                  LEFT JOIN users reviewer ON br.reviewed_by = reviewer.id
                  ORDER BY br.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }
        
        return $this->db->fetchAll($query);
    }
    
    /**
     * Lấy báo cáo theo ID
     */
    public function getById($id) {
        $query = "SELECT br.*, 
                         u.full_name as student_name, u.student_code, u.email as student_email,
                         b.title as book_title, b.author, b.isbn,
                         reviewer.full_name as reviewer_name
                  FROM book_reports br
                  JOIN users u ON br.user_id = u.id
                  JOIN books b ON br.book_id = b.id
                  LEFT JOIN users reviewer ON br.reviewed_by = reviewer.id
                  WHERE br.id = :id";
        
        return $this->db->fetchOne($query, ['id' => $id]);
    }
    
    /**
     * Lấy báo cáo theo sinh viên
     */
    public function getByUser($userId, $limit = null) {
        $query = "SELECT br.*, 
                         b.title as book_title, b.author,
                         reviewer.full_name as reviewer_name
                  FROM book_reports br
                  JOIN books b ON br.book_id = b.id
                  LEFT JOIN users reviewer ON br.reviewed_by = reviewer.id
                  WHERE br.user_id = :user_id
                  ORDER BY br.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT " . (int)$limit;
        }
        
        return $this->db->fetchAll($query, ['user_id' => $userId]);
    }
    
    /**
     * Lấy báo cáo theo trạng thái
     */
    public function getByStatus($status, $limit = null) {
        $query = "SELECT br.*, 
                         u.full_name as student_name, u.student_code,
                         b.title as book_title, b.author
                  FROM book_reports br
                  JOIN users u ON br.user_id = u.id
                  JOIN books b ON br.book_id = b.id
                  WHERE br.status = :status
                  ORDER BY br.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT " . (int)$limit;
        }
        
        return $this->db->fetchAll($query, ['status' => $status]);
    }
    
    /**
     * Cập nhật trạng thái báo cáo
     */
    public function updateStatus($id, $status, $reviewerId, $librarianNote = null) {
        $query = "UPDATE book_reports 
                  SET status = :status, reviewed_by = :reviewed_by, reviewed_at = NOW(), librarian_note = :librarian_note
                  WHERE id = :id";
        
        $conn = $this->db->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute([
            'status' => $status,
            'reviewed_by' => $reviewerId,
            'librarian_note' => $librarianNote,
            'id' => $id
        ]);
    }
    
    /**
     * Đếm số báo cáo theo trạng thái
     */
    public function countByStatus($status) {
        $query = "SELECT COUNT(*) as count FROM book_reports WHERE status = :status";
        $result = $this->db->fetchOne($query, ['status' => $status]);
        return $result['count'] ?? 0;
    }
    
    /**
     * Đếm tất cả báo cáo
     */
    public function countAll() {
        $query = "SELECT COUNT(*) as count FROM book_reports";
        $result = $this->db->fetchOne($query);
        return $result['count'] ?? 0;
    }
    
    /**
     * Lấy báo cáo gần đây
     */
    public function getRecent($limit = 10) {
        return $this->getAll($limit);
    }
    
    /**
     * Xóa báo cáo
     */
    public function deleteReport($id) {
        $query = "DELETE FROM book_reports WHERE id = :id";
        $conn = $this->db->connect();
        $stmt = $conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Lấy báo cáo theo sách
     */
    public function getByBook($bookId) {
        $query = "SELECT br.*, 
                         u.full_name as student_name, u.student_code
                  FROM book_reports br
                  JOIN users u ON br.user_id = u.id
                  WHERE br.book_id = :book_id
                  ORDER BY br.created_at DESC";
        
        return $this->db->fetchAll($query, ['book_id' => $bookId]);
    }
}
