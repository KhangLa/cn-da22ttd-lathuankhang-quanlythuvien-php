<?php
/**
 * Model Notification - Quản lý thông báo
 */

require_once __DIR__ . '/Database.php';

class Notification {
    private $db;
    private $table = 'notifications';
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Lấy tất cả thông báo
     */
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy thông báo theo ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    /**
     * Lấy thông báo theo user
     */
    public function getByUserId($userId, $limit = null, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = :user_id OR user_id IS NULL
                ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }
    
    /**
     * Lấy thông báo chưa đọc của user
     */
    public function getUnreadByUser($userId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE (user_id = :user_id OR user_id IS NULL) 
                AND is_read = 0
                ORDER BY created_at DESC";
        
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }
    
    /**
     * Tạo thông báo mới
     */
    public function create($data) {
        $insertData = [
            'user_id' => $data['user_id'] ?? null,
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'] ?? 'info',
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert($this->table, $insertData);
    }
    
    /**
     * Gửi thông báo cho tất cả sinh viên
     */
    public function sendToAllStudents($title, $message, $type = 'info') {
        $sql = "SELECT id FROM users WHERE role = 'student' AND status = 'active'";
        $students = $this->db->fetchAll($sql);
        
        foreach ($students as $student) {
            $this->create([
                'user_id' => $student['id'],
                'title' => $title,
                'message' => $message,
                'type' => $type
            ]);
        }
        
        return count($students);
    }
    
    /**
     * Gửi thông báo cho tất cả thủ thư
     */
    public function sendToAllLibrarians($title, $message, $type = 'info') {
        $sql = "SELECT id FROM users WHERE role = 'librarian' AND status = 'active'";
        $librarians = $this->db->fetchAll($sql);
        
        foreach ($librarians as $librarian) {
            $this->create([
                'user_id' => $librarian['id'],
                'title' => $title,
                'message' => $message,
                'type' => $type
            ]);
        }
        
        return count($librarians);
    }
    
    /**
     * Gửi thông báo cho một thủ thư cụ thể
     */
    public function sendToLibrarian($librarianId, $title, $message, $type = 'info') {
        return $this->create([
            'user_id' => $librarianId,
            'title' => $title,
            'message' => $message,
            'type' => $type
        ]);
    }
    
    /**
     * Gửi thông báo quá hạn
     */
    public function sendOverdueNotification($userId, $bookTitle, $overdueDays) {
        $title = "Thông báo quá hạn trả sách";
        $message = "Bạn đã quá hạn trả sách '{$bookTitle}' {$overdueDays} ngày. Vui lòng trả sách sớm để tránh bị phạt.";
        
        return $this->create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => 'warning'
        ]);
    }
    
    /**
     * Gửi thông báo nhắc trả sách
     */
    public function sendDueDateReminder($userId, $bookTitle, $dueDate) {
        $title = "Nhắc nhở trả sách";
        $message = "Sách '{$bookTitle}' của bạn sẽ đến hạn trả vào ngày " . formatDate($dueDate) . ". Vui lòng chuẩn bị trả sách đúng hạn.";
        
        return $this->create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => 'info'
        ]);
    }
    
    /**
     * Đánh dấu đã đọc
     */
    public function markAsRead($id) {
        $updateData = [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->update($this->table, $updateData, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Đánh dấu tất cả đã đọc của user
     */
    public function markAllAsReadByUser($userId) {
        $updateData = [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->update($this->table, $updateData, 
            'user_id = :user_id AND is_read = 0', 
            ['user_id' => $userId]
        );
    }
    
    /**
     * Xóa thông báo
     */
    public function delete($id) {
        return $this->db->delete($this->table, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Đếm thông báo chưa đọc của user
     */
    public function countUnreadByUser($userId) {
        return $this->db->count($this->table, 
            '(user_id = :user_id OR user_id IS NULL) AND is_read = 0', 
            ['user_id' => $userId]
        );
    }
    
    /**
     * Xóa thông báo cũ (hơn 30 ngày)
     */
    public function deleteOldNotifications($days = 30) {
        $sql = "DELETE FROM {$this->table} 
                WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)";
        return $this->db->query($sql, ['days' => $days])->rowCount();
    }
}
