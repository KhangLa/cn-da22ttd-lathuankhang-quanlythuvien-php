<?php $pageTitle = 'Đăng nhập - Thư viện TVU'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-wrapper {
            display: flex;
            width: 90%;
            max-width: 700px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            overflow: hidden;
            min-height: 350px;
        }
        .login-form-section {
            width: 35%;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-form-section h2 {
            color: #333;
            margin-bottom: 0.3rem;
            font-size: 1.3rem;
            font-weight: 600;
        }
        .login-form-section p {
            color: #64748b;
            margin-bottom: 1.2rem;
            font-size: 0.8rem;
        }
        .login-intro-section {
            width: 65%;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        .login-intro-section img {
            max-width: 100px;
            margin-bottom: 0.8rem;
        }
        .login-intro-section h1 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        .login-intro-section p {
            font-size: 0.75rem;
            line-height: 1.4;
            max-width: 250px;
            margin-bottom: 0.5rem;
            color: rgba(255,255,255,0.95);
        }
        .login-intro-section .features {
            margin-top: 0.8rem;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        .feature-item {
            background: rgba(255,255,255,0.15);
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            backdrop-filter: blur(10px);
            font-size: 0.7rem;
        }
        .feature-item div:first-child {
            font-size: 1rem;
            margin-bottom: 0.2rem;
        }
        .login-footer {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.75rem;
        }
        .login-footer a {
            color: #2563eb;
            text-decoration: none;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }
            .login-form-section, .login-intro-section {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Side: Login Form (30%) -->
        <div class="login-form-section">
            <h2>Đăng nhập</h2>
            <p>Vui lòng nhập thông tin để tiếp tục</p>
            
            <?php 
            $flash = getFlash();
            ?>
            <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>" style="padding: 12px; margin-bottom: 20px; border-radius: 8px;">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?= BASE_URL ?>/auth/login" id="login-form">
                <div class="form-group">
                    <label for="username" class="form-label">Tên đăng nhập</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-control" 
                           placeholder="Nhập tên đăng nhập"
                           required
                           autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Nhập mật khẩu"
                           required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Đăng nhập
                    </button>
                </div>
            </form>
            
            <div class="login-footer">
                <p>Chưa có tài khoản? <a href="<?= BASE_URL ?>/auth/register">Đăng ký ngay</a></p>
                <p><a href="<?= BASE_URL ?>/auth/forgot-password">Quên mật khẩu?</a></p>
            </div>
        </div>
        
        <!-- Right Side: Logo & Introduction (70%) -->
        <div class="login-intro-section">
            <img src="<?= BASE_URL ?>/public/images/logo.png" alt="Logo Thư viện TVU">
            <h1>Thư viện TVU</h1>
            <p>Chào mừng đến với Thư viện Đại học Trà Vinh</p>
            <p style="font-size: 1.1rem; opacity: 0.9;">Khám phá kho tàng tri thức phong phú với hàng ngàn đầu sách, tài liệu học tập và nghiên cứu</p>
            
            <div class="features">
                <div class="feature-item">
                    <div>📚</div>
                    <div>Kho sách đa dạng</div>
                </div>
                <div class="feature-item">
                    <div>🔍</div>
                    <div>Tìm kiếm nhanh</div>
                </div>
                <div class="feature-item">
                    <div>📱</div>
                    <div>Quản lý dễ dàng</div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="<?= BASE_URL ?>/public/js/validation.js"></script>
</body>
</html>
