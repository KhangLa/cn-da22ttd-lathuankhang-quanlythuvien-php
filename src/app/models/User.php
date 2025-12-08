<?php
/**
 * Model User - Quản lý người dùng (Sinh viên)
 */

require_once __DIR__ . '/Database.php';

class User {
    private $db;
    private $table = 'users';
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Lấy tất cả sinh viên
     */
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} WHERE role = 'student' ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy user theo ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    /**
     * Lấy user theo username
     */
    public function getByUsername($username) {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username";
        return $this->db->fetchOne($sql, ['username' => $username]);
    }
    
    /**
     * Lấy user theo email
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        return $this->db->fetchOne($sql, ['email' => $email]);
    }
    
    /**
     * Tạo user mới
     */
    public function create($data) {
        $insertData = [
            'username' => $data['username'],
            'password' => hashPassword($data['password']),
            'email' => $data['email'],
            'full_name' => $data['full_name'] ?? '',
            'student_code' => $data['student_code'] ?? '',
            'phone' => $data['phone'] ?? '',
            'address' => $data['address'] ?? '',
            'role' => 'student',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert($this->table, $insertData);
    }
    
    /**
     * Cập nhật thông tin user
     */
    public function update($id, $data) {
        $updateData = [];
        
        $allowedFields = ['full_name', 'email', 'phone', 'address', 'student_code', 'class_code', 'avatar', 'status'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        if (isset($data['password']) && !empty($data['password'])) {
            $updateData['password'] = hashPassword($data['password']);
        }
        
        $updateData['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->db->update($this->table, $updateData, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Xóa user
     */
    public function delete($id) {
        return $this->db->delete($this->table, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Đổi mật khẩu
     */
    public function changePassword($id, $newPassword) {
        $data = [
            'password' => hashPassword($newPassword),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        return $this->db->update($this->table, $data, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Tìm kiếm sinh viên
     */
    public function search($keyword, $limit = null, $offset = 0) {
        $searchTerm = "%{$keyword}%";
        $sql = "SELECT * FROM {$this->table} 
                WHERE role = 'student' 
                AND (username LIKE :keyword1 
                    OR email LIKE :keyword2 
                    OR full_name LIKE :keyword3 
                    OR student_code LIKE :keyword4)
                ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql, [
            'keyword1' => $searchTerm,
            'keyword2' => $searchTerm,
            'keyword3' => $searchTerm,
            'keyword4' => $searchTerm
        ]);
    }
    
    /**
     * Đếm tổng sinh viên
     */
    public function countAll() {
        return $this->db->count($this->table, "role = 'student'");
    }
    
    /**
     * Đếm sinh viên theo trạng thái
     */
    public function countByStatus($status) {
        return $this->db->count($this->table, "role = 'student' AND status = :status", ['status' => $status]);
    }
    
    /**
     * Kiểm tra username đã tồn tại
     */
    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE username = :username";
        $params = ['username' => $username];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        
        return $this->db->fetchColumn($sql, $params) > 0;
    }
    
    /**
     * Kiểm tra email đã tồn tại
     */
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email";
        $params = ['email' => $email];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        
        return $this->db->fetchColumn($sql, $params) > 0;
    }
    
    /**
     * Đăng nhập
     */
    public function login($username, $password) {
        $user = $this->getByUsername($username);
        
        if ($user && verifyPassword($password, $user['password'])) {
            if ($user['status'] === 'active') {
                return $user;
            }
        }
        
        return false;
    }
    
    /**
     * Lấy danh sách user theo role
     */
    public function getByRole($role, $limit = null, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} WHERE role = :role AND status = 'active' ORDER BY full_name ASC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }
        
        return $this->db->fetchAll($sql, ['role' => $role]);
    }
}
