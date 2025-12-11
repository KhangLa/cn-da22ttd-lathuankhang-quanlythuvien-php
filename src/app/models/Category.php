<?php
/**
 * Model Category - Quản lý danh mục sách
 */

require_once __DIR__ . '/Database.php';

class Category {
    private $db;
    private $table = 'categories';
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Lấy tất cả danh mục
     */
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT c.*, COUNT(b.id) as book_count
                FROM {$this->table} c
                LEFT JOIN books b ON c.id = b.category_id
                GROUP BY c.id
                ORDER BY c.name ASC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy danh mục theo ID
     */
    public function getById($id) {
        $sql = "SELECT c.*, COUNT(b.id) as book_count
                FROM {$this->table} c
                LEFT JOIN books b ON c.id = b.category_id
                WHERE c.id = :id
                GROUP BY c.id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    /**
     * Tạo danh mục mới
     */
    public function create($data) {
        $insertData = [
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'slug' => createSlug($data['name']),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert($this->table, $insertData);
    }
    
    /**
     * Cập nhật danh mục
     */
    public function update($id, $data) {
        $updateData = [
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'slug' => createSlug($data['name']),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->update($this->table, $updateData, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Xóa danh mục
     */
    public function delete($id) {
        // Kiểm tra xem có sách nào thuộc danh mục này không
        $bookCount = $this->db->count('books', 'category_id = :id', ['id' => $id]);
        
        if ($bookCount > 0) {
            return false; // Không thể xóa danh mục còn sách
        }
        
        return $this->db->delete($this->table, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Tìm kiếm danh mục
     */
    public function search($keyword) {
        $sql = "SELECT c.*, COUNT(b.id) as book_count
                FROM {$this->table} c
                LEFT JOIN books b ON c.id = b.category_id
                WHERE c.name LIKE :keyword OR c.description LIKE :keyword
                GROUP BY c.id
                ORDER BY c.name ASC";
        
        return $this->db->fetchAll($sql, ['keyword' => "%{$keyword}%"]);
    }
    
    /**
     * Đếm tổng danh mục
     */
    public function countAll() {
        return $this->db->count($this->table);
    }
    
    /**
     * Kiểm tra tên danh mục đã tồn tại
     */
    public function nameExists($name, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE name = :name";
        $params = ['name' => $name];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        
        return $this->db->fetchColumn($sql, $params) > 0;
    }
    
    /**
     * Lấy danh mục có nhiều sách nhất
     */
    public function getTopCategories($limit = 5) {
        $sql = "SELECT c.*, COUNT(b.id) as book_count
                FROM {$this->table} c
                LEFT JOIN books b ON c.id = b.category_id
                GROUP BY c.id
                HAVING book_count > 0
                ORDER BY book_count DESC
                LIMIT {$limit}";
        
        return $this->db->fetchAll($sql);
    }
}
