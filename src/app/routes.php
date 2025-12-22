<?php
/**
 * File điều hướng - Router
 * Xử lý URL và điều hướng đến controller tương ứng
 */

// Lấy URL từ query string
$url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';

// Tách URL thành mảng
$urlParts = $url ? explode('/', $url) : ['home'];

// Xác định controller, action và params
$controllerName = !empty($urlParts[0]) ? ucfirst($urlParts[0]) . 'Controller' : 'HomeController';

// Convert kebab-case to camelCase (borrow-list -> borrowList)
$action = isset($urlParts[1]) ? $urlParts[1] : 'index';
$originalAction = $action;
if (strpos($action, '-') !== false) {
    $action = lcfirst(str_replace('-', '', ucwords($action, '-')));
}

// Debug
// echo "URL Parts: " . print_r($urlParts, true) . "<br>";
// echo "Original action: $originalAction<br>";
// echo "Converted action: $action<br>";
// echo "Params: " . print_r(array_slice($urlParts, 2), true) . "<br>";
// die();

$params = array_slice($urlParts, 2);

// Đường dẫn đến controller
$controllerPath = __DIR__ . '/controllers/' . $controllerName . '.php';

// Debug (xóa sau khi fix)
// echo "Controller: $controllerName<br>";
// echo "Action: $action<br>";
// echo "Controller Path: $controllerPath<br>";
// die();

// Kiểm tra controller có tồn tại không
if (file_exists($controllerPath)) {
    require_once $controllerPath;
    
    // Khởi tạo controller
    $controller = new $controllerName();
    
    // Kiểm tra method có tồn tại không
    if (method_exists($controller, $action)) {
        // Gọi method với params
        call_user_func_array([$controller, $action], $params);
    } else {
        // Method không tồn tại - Debug
        // echo "Method không tồn tại: $action trong $controllerName<br>";
        // echo "Available methods: " . implode(', ', get_class_methods($controller));
        // die();
        show404();
    }
} else {
    // Controller không tồn tại
    show404();
}

/**
 * Hiển thị trang 404
 */
function show404() {
    http_response_code(404);
    echo "<!DOCTYPE html>
    <html lang='vi'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>404 - Không tìm thấy trang</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .error-container {
                text-align: center;
                color: white;
            }
            h1 {
                font-size: 120px;
                margin: 0;
            }
            p {
                font-size: 24px;
            }
            a {
                color: white;
                text-decoration: none;
                border: 2px solid white;
                padding: 10px 20px;
                border-radius: 5px;
                display: inline-block;
                margin-top: 20px;
            }
            a:hover {
                background: white;
                color: #667eea;
            }
        </style>
    </head>
    <body>
        <div class='error-container'>
            <h1>404</h1>
            <p>Trang bạn tìm kiếm không tồn tại!</p>
            <a href='" . BASE_URL . "'>Về trang chủ</a>
        </div>
    </body>
    </html>";
    exit();
}
