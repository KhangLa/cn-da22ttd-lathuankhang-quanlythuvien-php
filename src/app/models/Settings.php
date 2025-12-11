<?php
/**
 * Model Settings - Quản lý cài đặt hệ thống
 */

require_once __DIR__ . '/Database.php';

class Settings extends Database {
    
    /**
     * Lấy giá trị cài đặt theo key
     */
    public function get($key, $default = null) {
        $query = "SELECT key_value FROM settings WHERE key_name = ?";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([$key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['key_value'] : $default;
    }
    
    /**
     * Lấy tất cả cài đặt
     */
    public function getAll() {
        $query = "SELECT * FROM settings ORDER BY key_name";
        $stmt = $this->connect()->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Chuyển đổi thành array key => value
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['key_name']] = $row['key_value'];
        }
        
        return $settings;
    }
    
    /**
     * Cập nhật hoặc thêm mới cài đặt
     */
    public function set($key, $value, $description = null) {
        // Kiểm tra xem key đã tồn tại chưa
        $existing = $this->get($key);
        
        if ($existing !== null) {
            // Update
            $query = "UPDATE settings SET key_value = ?, updated_at = NOW()";
            $params = [$value];
            
            if ($description !== null) {
                $query .= ", description = ?";
                $params[] = $description;
            }
            
            $query .= " WHERE key_name = ?";
            $params[] = $key;
            
            $stmt = $this->connect()->prepare($query);
            return $stmt->execute($params);
        } else {
            // Insert
            $query = "INSERT INTO settings (key_name, key_value, description) VALUES (?, ?, ?)";
            $stmt = $this->connect()->prepare($query);
            return $stmt->execute([$key, $value, $description]);
        }
    }
    
    /**
     * Cập nhật nhiều cài đặt cùng lúc
     */
    public function updateMultiple($settings) {
        try {
            $this->connect()->beginTransaction();
            
            foreach ($settings as $key => $value) {
                $this->set($key, $value);
            }
            
            $this->connect()->commit();
            return true;
        } catch (Exception $e) {
            $this->connect()->rollBack();
            return false;
        }
    }
    
    /**
     * Xóa cài đặt
     */
    public function deleteSetting($key) {
        $query = "DELETE FROM settings WHERE key_name = ?";
        $stmt = $this->connect()->prepare($query);
        return $stmt->execute([$key]);
    }
    
    /**
     * Kiểm tra xem key có tồn tại không
     */
    public function exists($key) {
        $query = "SELECT COUNT(*) FROM settings WHERE key_name = ?";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([$key]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Lấy giá trị boolean
     */
    public function getBool($key, $default = false) {
        $value = $this->get($key);
        if ($value === null) {
            return $default;
        }
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
    
    /**
     * Lấy giá trị integer
     */
    public function getInt($key, $default = 0) {
        $value = $this->get($key);
        if ($value === null) {
            return $default;
        }
        return (int)$value;
    }
    
    /**
     * Lấy giá trị float
     */
    public function getFloat($key, $default = 0.0) {
        $value = $this->get($key);
        if ($value === null) {
            return $default;
        }
        return (float)$value;
    }
}
