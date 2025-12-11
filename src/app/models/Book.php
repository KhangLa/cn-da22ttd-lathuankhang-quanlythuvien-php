<?php
/**
 * Model Book - Quản lý sách
 */

require_once __DIR__ . '/Database.php';

class Book {
    private $db;
    private $table = 'books';
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Lấy tất cả sách
     */
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT b.*, c.name as category_name 
                FROM {$this->table} b
                LEFT JOIN categories c ON b.category_id = c.id
                ORDER BY b.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy sách theo ID
     */
    public function getById($id) {
        $sql = "SELECT b.*, c.name as category_name 
                FROM {$this->table} b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    /**
     * Tạo sách mới
     */
    public function create($data) {
        $insertData = [
            'title' => $data['title'],
            'author' => $data['author'] ?? '',
            'publisher' => $data['publisher'] ?? '',
            'publish_year' => $data['publish_year'] ?? null,
            'isbn' => $data['isbn'] ?? '',
            'category_id' => $data['category_id'] ?? null,
            'quantity' => $data['quantity'] ?? 0,
            'available_quantity' => $data['quantity'] ?? 0,
            'description' => $data['description'] ?? '',
            'cover_image' => $data['cover_image'] ?? null,
            'location' => $data['location'] ?? '',
            'status' => 'available',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert($this->table, $insertData);
    }
    
    /**
     * Cập nhật sách
     */
    public function update($id, $data) {
        $updateData = [];
        
        $allowedFields = ['title', 'author', 'publisher', 'publish_year', 'isbn', 
                         'category_id', 'quantity', 'description', 'cover_image', 
                         'location', 'status'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        $updateData['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->db->update($this->table, $updateData, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Xóa sách
     */
    public function delete($id) {
        return $this->db->delete($this->table, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Tìm kiếm sách
     */
    public function search($keyword, $categoryId = null, $limit = null, $offset = 0) {
        $keywordParam = "%{$keyword}%";
        
        $sql = "SELECT b.*, c.name as category_name 
                FROM {$this->table} b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE (b.title LIKE ? 
                    OR b.author LIKE ? 
                    OR b.isbn LIKE ? 
                    OR b.publisher LIKE ?)";
        
        $params = [$keywordParam, $keywordParam, $keywordParam, $keywordParam];
        
        if ($categoryId && $categoryId > 0) {
            $sql .= " AND b.category_id = ?";
            $params[] = $categoryId;
        }
        
        $sql .= " ORDER BY b.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Lấy sách theo danh mục
     */
    public function getByCategory($categoryId, $limit = null, $offset = 0) {
        $sql = "SELECT b.*, c.name as category_name 
                FROM {$this->table} b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.category_id = :category_id
                ORDER BY b.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql, ['category_id' => $categoryId]);
    }
    
    /**
     * Lấy sách có sẵn (còn để mượn)
     */
    public function getAvailableBooks($limit = null, $offset = 0) {
        $sql = "SELECT b.*, c.name as category_name 
                FROM {$this->table} b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.available_quantity > 0 AND b.status = 'available'
                ORDER BY b.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Cập nhật số lượng sách có sẵn
     */
    public function updateAvailableQuantity($id, $change) {
        $sql = "UPDATE {$this->table} 
                SET available_quantity = available_quantity + :change,
                    updated_at = :updated_at
                WHERE id = :id";
        
        $params = [
            'change' => $change,
            'updated_at' => date('Y-m-d H:i:s'),
            'id' => $id
        ];
        
        return $this->db->query($sql, $params)->rowCount();
    }
    
    /**
     * Đếm tổng sách
     */
    public function countAll() {
        return $this->db->count($this->table);
    }
    
    /**
     * Đếm sách theo danh mục
     */
    public function countByCategory($categoryId) {
        return $this->db->count($this->table, 'category_id = :category_id', ['category_id' => $categoryId]);
    }
    
    /**
     * Đếm sách có sẵn
     */
    public function countAvailable() {
        return $this->db->count($this->table, "available_quantity > 0 AND status = 'available'");
    }
    
    /**
     * Lấy sách mới nhất
     */
    public function getLatest($limit = 10) {
        $sql = "SELECT b.*, c.name as category_name 
                FROM {$this->table} b
                LEFT JOIN categories c ON b.category_id = c.id
                ORDER BY b.created_at DESC
                LIMIT {$limit}";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy sách phổ biến (được mượn nhiều)
     */
    public function getPopular($limit = 10) {
        $sql = "SELECT b.*, c.name as category_name, COUNT(br.id) as borrow_count
                FROM {$this->table} b
                LEFT JOIN categories c ON b.category_id = c.id
                LEFT JOIN borrows br ON b.id = br.book_id
                GROUP BY b.id
                ORDER BY borrow_count DESC
                LIMIT {$limit}";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Kiểm tra ISBN đã tồn tại
     */
    public function isbnExists($isbn, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE isbn = :isbn";
        $params = ['isbn' => $isbn];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        
        return $this->db->fetchColumn($sql, $params) > 0;
    }
}
