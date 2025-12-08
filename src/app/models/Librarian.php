<?php
/**
 * Model Librarian - Quản lý thủ thư
 */

require_once __DIR__ . '/Database.php';

class Librarian {
    private $db;
    private $table = 'users';
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Lấy tất cả thủ thư
     */
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} WHERE role = 'librarian' ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy thủ thư theo ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id AND role = 'librarian'";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    /**
     * Tạo thủ thư mới
     */
    public function create($data) {
        $insertData = [
            'username' => $data['username'],
            'password' => hashPassword($data['password']),
            'email' => $data['email'],
            'full_name' => $data['full_name'] ?? '',
            'phone' => $data['phone'] ?? '',
            'address' => $data['address'] ?? '',
            'role' => 'librarian',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert($this->table, $insertData);
    }
    
    /**
     * Cập nhật thông tin thủ thư
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
            ['id' => $id, 'role' => 'librarian']
        );
    }
    
    /**
     * Xóa thủ thư
     */
    public function delete($id) {
        return $this->db->delete($this->table, 
            'id = :id AND role = :role', 
            ['id' => $id, 'role' => 'librarian']
        );
    }
    
    /**
     * Tìm kiếm thủ thư
     */
    public function search($keyword) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE role = 'librarian' 
                AND (username LIKE :keyword 
                    OR email LIKE :keyword 
                    OR full_name LIKE :keyword)
                ORDER BY created_at DESC";
        
        return $this->db->fetchAll($sql, ['keyword' => "%{$keyword}%"]);
    }
    
    /**
     * Đếm tổng thủ thư
     */
    public function countAll() {
        return $this->db->count($this->table, "role = 'librarian'");
    }
    
    /**
     * Đếm thủ thư theo trạng thái
     */
    public function countByStatus($status) {
        return $this->db->count($this->table, 
            "role = 'librarian' AND status = :status", 
            ['status' => $status]
        );
    }
}
