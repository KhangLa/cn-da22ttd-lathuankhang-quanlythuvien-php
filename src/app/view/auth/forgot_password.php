<?php $pageTitle = 'Quên mật khẩu - Thư viện TVU'; ?>
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
        }
        .forgot-container {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        .forgot-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .forgot-header h1 {
            color: #2563eb;
            margin-bottom: 0.5rem;
        }
        .forgot-footer {
            text-align: center;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-header">
            <h1>📚 Thư viện TVU</h1>
            <p>Quên mật khẩu</p>
        </div>
        
        <?php 
        $flash = getFlash();
        if ($flash): 
        ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= $flash['message'] ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?= BASE_URL ?>/auth/forgot-password">
            <div class="form-group">
                <label for="email" class="form-label">Email đăng ký</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control" 
                       placeholder="Nhập email của bạn"
                       required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Gửi yêu cầu
                </button>
            </div>
        </form>
        
        <div class="forgot-footer">
            <p><a href="<?= BASE_URL ?>/auth/login">← Quay lại đăng nhập</a></p>
        </div>
    </div>
</body>
</html>
