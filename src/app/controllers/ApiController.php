<?php
/**
 * API Controller
 * Xử lý các API requests
 */

require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Borrow.php';

class ApiController {
    private $userModel;
    private $borrowModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->borrowModel = new Borrow();
    }
    
    /**
     * Lấy thông tin chi tiết sinh viên
     */
    public function student($id) {
        header('Content-Type: application/json');
        
        try {
            $student = $this->userModel->find($id);
            
            if (!$student || $student['role'] !== 'student') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Sinh viên không tồn tại'
                ]);
                return;
            }
            
            // Lấy lịch sử mượn sách của sinh viên
            $borrows = $this->borrowModel->getByUserId($id);
            
            // Tính số ngày quá hạn và format date cho mỗi phiếu mượn
            foreach ($borrows as &$borrow) {
                // Calculate overdue days first (before formatting)
                if ($borrow['status'] === 'borrowed') {
                    $dueDate = new DateTime($borrow['due_date']);
                    $today = new DateTime();
                    if ($today > $dueDate) {
                        $diff = $today->diff($dueDate);
                        $borrow['overdue_days'] = $diff->days;
                    } else {
                        $borrow['overdue_days'] = 0;
                    }
                } else {
                    $borrow['overdue_days'] = 0;
                }
                
                // Format dates after calculation
                $borrow['borrow_date'] = date('d/m/Y', strtotime($borrow['borrow_date']));
                $borrow['due_date'] = date('d/m/Y', strtotime($borrow['due_date']));
                if (!empty($borrow['return_date'])) {
                    $borrow['return_date'] = date('d/m/Y', strtotime($borrow['return_date']));
                }
            }
            
            echo json_encode([
                'success' => true,
                'student' => $student,
                'borrows' => $borrows
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
    }
}
