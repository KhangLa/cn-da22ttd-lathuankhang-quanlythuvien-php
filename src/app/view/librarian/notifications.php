<?php
$pageTitle = 'Gửi thông báo - Thủ thư';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Gửi thông báo cho sinh viên</h1>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Tạo thông báo mới</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" 
                                   placeholder="Nhập tiêu đề thông báo" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Nội dung <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="5" 
                                      placeholder="Nhập nội dung thông báo..." required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Loại thông báo</label>
                            <select name="type" class="form-control">
                                <option value="info">Thông tin (Xanh)</option>
                                <option value="success">Thành công (Xanh lá)</option>
                                <option value="warning">Cảnh báo (Vàng)</option>
                                <option value="error">Lỗi (Đỏ)</option>
                            </select>
                        </div>
                        
                        <div class="alert alert-info">
                            <strong>Lưu ý:</strong> Thông báo sẽ được gửi đến tất cả sinh viên đang hoạt động trong hệ thống.
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                📢 Gửi thông báo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>Mẫu thông báo</h3>
                </div>
                <div class="card-body">
                    <h5>Thông báo đóng cửa</h5>
                    <p class="text-muted small">
                        Thư viện sẽ đóng cửa vào ngày... do... Các bạn sinh viên vui lòng trả sách trước thời gian này.
                    </p>
                    <hr>
                    
                    <h5>Nhắc nhở trả sách</h5>
                    <p class="text-muted small">
                        Sinh viên có sách sắp đến hạn trả vui lòng chuẩn bị trả sách đúng hạn để tránh bị phạt.
                    </p>
                    <hr>
                    
                    <h5>Sách mới</h5>
                    <p class="text-muted small">
                        Thư viện vừa nhập thêm... cuốn sách mới trong lĩnh vực... Mời các bạn đến mượn sách.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
