<?php
/**
 * Model Admin - Quản lý admin
 */

require_once __DIR__ . '/Database.php';

class Admin {
    private $db;
    private $table = 'users';
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Lấy tất cả admin
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} WHERE role = 'admin' ORDER BY created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy admin theo ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id AND role = 'admin'";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    /**
     * Tạo admin mới
     */
    public function create($data) {
        $insertData = [
            'username' => $data['username'],
            'password' => hashPassword($data['password']),
            'email' => $data['email'],
            'full_name' => $data['full_name'] ?? '',
            'phone' => $data['phone'] ?? '',
            'role' => 'admin',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert($this->table, $insertData);
    }
    
    /**
     * Cập nhật thông tin admin
     */
    public function update($id, $data) {
        $updateData = [];
        
        $allowedFields = ['full_name', 'email', 'phone', 'address', 'avatar', 'status'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        if (isset($data['password']) && !empty($data['password'])) {
            $updateData['password'] = hashPassword($data['password']);
        }
        
        $updateData['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->db->update($this->table, $updateData, 
            'id = :id AND role = :role', 
            ['id' => $id, 'role' => 'admin']
        );
    }
    
    /**
     * Lấy thống kê tổng quan hệ thống
     */
    public function getDashboardStats() {
        $stats = [];
        
        // Tổng số sinh viên
        $stats['total_students'] = $this->db->count('users', "role = 'student'");
        
        // Tổng số thủ thư
        $stats['total_librarians'] = $this->db->count('users', "role = 'librarian'");
        
        // Tổng số sách
        $stats['total_books'] = $this->db->count('books');
        
        // Tổng số sách có sẵn
        $stats['available_books'] = $this->db->count('books', "available_quantity > 0 AND status = 'available'");
        
        // Tổng số phiếu mượn
        $stats['total_borrows'] = $this->db->count('borrows');
        
        // Số sách đang được mượn
        $stats['active_borrows'] = $this->db->count('borrows', "status = 'borrowed'");
        
        // Số sách quá hạn
        $stats['overdue_books'] = $this->db->count('borrows', "status = 'borrowed' AND due_date < CURDATE()");
        
        // Tổng số danh mục
        $stats['total_categories'] = $this->db->count('categories');
        
        return $stats;
    }
    
    /**
     * Lấy hoạt động gần đây
     */
    public function getRecentActivities($limit = 10) {
        $sql = "SELECT 
                    'borrow' as type,
                    br.id,
                    br.created_at,
                    u.full_name as user_name,
                    b.title as book_title,
                    br.status
                FROM borrows br
                JOIN users u ON br.user_id = u.id
                JOIN books b ON br.book_id = b.id
                ORDER BY br.created_at DESC
                LIMIT {$limit}";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Đếm tổng admin
     */
    public function countAll() {
        return $this->db->count($this->table, "role = 'admin'");
    }
}
