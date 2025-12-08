<?php $pageTitle = 'Đăng ký - Thư viện TVU'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem 0;
        }
        .register-container {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
        }
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .register-header h1 {
            color: #2563eb;
            margin-bottom: 0.5rem;
        }
        .register-footer {
            text-align: center;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>📚 Thư viện TVU</h1>
            <p>Đăng ký tài khoản sinh viên</p>
        </div>
        
        <?php 
        $flash = getFlash();
        if ($flash): 
        ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= $flash['message'] ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?= BASE_URL ?>/auth/register" id="register-form">
            <div class="form-group">
                <label for="username" class="form-label">Tên đăng nhập *</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email *</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Mật khẩu *</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password" class="form-label">Xác nhận mật khẩu *</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="full_name" class="form-label">Họ và tên *</label>
                <input type="text" id="full_name" name="full_name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="student_code" class="form-label">Mã sinh viên *</label>
                <input type="text" id="student_code" name="student_code" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="tel" id="phone" name="phone" class="form-control">
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Đăng ký
                </button>
            </div>
        </form>
        
        <div class="register-footer">
            <p>Đã có tài khoản? <a href="<?= BASE_URL ?>/auth/login">Đăng nhập</a></p>
        </div>
    </div>
    
    <script src="<?= BASE_URL ?>/public/js/validation.js"></script>
</body>
</html>
