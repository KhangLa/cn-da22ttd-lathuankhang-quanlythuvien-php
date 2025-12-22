#Thiết kế ứng dụng web quản lý thư viện - Đại học Trà Vinh

## Mục lục
- Thiết kế ứng dụng web quản lý thư viện - Đại học Trà Vinh
  - [Mục lục](#mục-lục)
  - [Giới thiệu](#giới-thiệu)
  - [Tính năng](#tính-năng)
  - [Giới Thiệu Về Repositories của tôi](#giới-thiệu-về-repositories-của-tôi)
  - [Cài đặt](#cài-đặt)
  - [Sử dụng](#sử-dụng)
  - [Thông tin liên lạc](#thông-tin-liên-)
---

## Giới thiệu
Website Quản lý Thư viện là ứng dụng web được xây dựng dành cho Đại học Trà Vinh nhằm hiện đại hóa quy trình mượn trả sách. 
Hệ thống được phát triển theo mô hình MVC (Model-View-Controller) sử dụng ngôn ngữ PHP, cơ sở dữ liệu MySQL cùng giao diện HTML, CSS và JavaScript. 
Ứng dụng cung cấp giải pháp toàn diện cho việc quản lý tài liệu, hỗ trợ sinh viên tra cứu dễ dàng và giúp thủ thư điều hành các hoạt động thư viện một cách chuyên nghiệp, chính xác.

---

## Tính năng
-**Dành cho Sinh viên**: Đăng ký/đăng nhập, cập nhật hồ sơ, tìm kiếm sách theo tiêu đề/tác giả, gửi yêu cầu mượn sách trực tuyến, theo dõi lịch sử mượn trả, báo cáo sự cố sách và nhận thông báo từ hệ thống.
-**Dành cho Thủ thư**: Quản lý danh mục sách (thêm, sửa, xóa, upload ảnh bìa), duyệt/từ chối yêu cầu mượn, xử lý trả sách và tự động tính toán tiền phạt quá hạn, gửi thông báo cho sinh viên.
-**Dành cho Quản trị viên (Admin)**: Quản lý toàn bộ tài khoản sinh viên và thủ thư, cấu hình các tham số hệ thống (thời hạn mượn, mức phạt), xem báo cáo thống kê hoạt động thư viện theo ngày/tháng/năm.
-**Thống kê và Báo cáo**: Hệ thống Dashboard trực quan với biểu đồ hoạt động, báo cáo sách phổ biến và danh sách sinh viên hoạt động tích cực.

---

## Giới Thiệu Về Repositories của tôi
Đây là nơi tôi để toàn bộ thư mục liên quan đến source code của ứng dụng web và các file báo cáo. Toàn bộ thư mục nằm ở branches master trong repositories này. Dưới đây là miêu tả chi tiết:
-**src**: Đây là nơi lưu source code liên quan đến ứng dụng web (theo mô hình MVC) và cơ sở dữ liệu.
-**thesis**: Đây là nơi chứa các tập tin tài liệu văn bản, file thiết kế và báo cáo của Đồ án.

---

## Cài đặt
1.**Tải về ứng dụng web**:
- Ứng dụng web có thể được tải về bằng cách tải file src từ repository này.
2.**Cài đặt XAMPP**:
- Đảm bảo rằng XAMPP (PHP 7.4+) đã được cài đặt trên máy tính của bạn.
- Khởi động module Apache và MySQL từ XAMPP Control Panel.
- Truy cập http://localhost/phpmyadmin, tạo một cơ sở dữ liệu mới có tên là library_db.
- Nhập (Import) các bảng từ file database/library_db.sql vào cơ sở dữ liệu vừa tạo.
3.**Thiết lập thư mục dự án**:
- Vào thư mục htdocs trong xampp (thường là C:\xampp\htdocs\).
- Tạo một thư mục tên là QLTV và giải nén source code vào đây.
- Kiểm tra cấu hình kết nối tại file config/config.php để đảm bảo thông số Database chính xác.
4.**Chạy ứng dụng**:
- Mở trình duyệt và truy cập vào đường dẫn: http://localhost/QLTV/index.php.

---

## Sử dụng
- Đăng nhập: Sử dụng các tài khoản mặc định (Admin: admin/admin123, Thủ thư: librarian/lib123, Sinh viên: student/student123).
- Tra cứu: Sinh viên sử dụng thanh tìm kiếm tại trang chủ để tìm sách theo nhu cầu.
- Mượn sách: Chọn sách muốn mượn và nhấn "Yêu cầu mượn", sau đó chờ thủ thư phê duyệt.
- Quản lý: Thủ thư và Admin truy cập vào Dashboard để thực hiện các nghiệp vụ quản lý kho sách và người dùng.

---

## Thông tin liên lạc
- Họ tên: La Thuấn Khang
- Lớp: DA22TTD
- MSSV: 110122090
- Số điện thoại: 0967393450
- Email: lathuankhang2004@gmail.com
- Đơn vị: Đại học Trà Vinh
