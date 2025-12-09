<?php
/**
 * BookController - Quản lý sách (dùng chung)
 */

require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Category.php';

class BookController {
    private $bookModel;
    private $categoryModel;
    
    public function __construct() {
        $this->bookModel = new Book();
        $this->categoryModel = new Category();
    }
    
    /**
     * Chi tiết sách
     */
    public function detail($id) {
        $book = $this->bookModel->getById($id);
        
        if (!$book) {
            setFlash('error', 'Không tìm thấy sách');
            redirect('student/books');
        }
        
        $data = [];
        $data['book'] = $book;
        
        // Lấy sách cùng danh mục
        if ($book['category_id']) {
            $data['related_books'] = $this->bookModel->getByCategory($book['category_id'], 4);
        }
        
        require_once __DIR__ . '/../views/book_detail.php';
    }
}
