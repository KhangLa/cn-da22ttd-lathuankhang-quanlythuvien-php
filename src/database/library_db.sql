-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 03, 2025 lúc 06:45 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `library_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `publisher` varchar(100) DEFAULT NULL,
  `publish_year` int(4) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `available_quantity` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `status` enum('available','unavailable','maintenance') NOT NULL DEFAULT 'available',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `publisher`, `publish_year`, `isbn`, `category_id`, `quantity`, `available_quantity`, `description`, `cover_image`, `location`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Lập trình C cơ bản', 'Phạm Văn Ất', 'NXB Giáo dục', 2020, '978-604-0-00001-1', 1, 10, 8, 'Giáo trình lập trình C cho người mới bắt đầu', NULL, 'Kệ A1', 'available', '2025-11-07 21:59:10', '2025-11-10 22:08:13'),
(2, 'Cấu trúc dữ liệu và giải thuật', 'Nguyễn Đức Nghĩa', 'NXB Đại học Quốc gia', 2019, '978-604-0-00002-2', 1, 15, 11, 'Giáo trình về cấu trúc dữ liệu và giải thuật', NULL, 'Kệ A2', 'available', '2025-11-07 21:59:10', '2025-12-03 22:56:47'),
(3, 'Nguyên lý Kinh tế học', 'N. Gregory Mankiw', 'NXB Thống kê', 2021, '978-604-0-00003-3', 2, 20, 17, 'Nguyên lý kinh tế học vĩ mô và vi mô', NULL, 'Kệ B1', 'available', '2025-11-07 21:59:10', '2025-11-25 14:04:05'),
(4, 'Nhà Giả Kim', 'Paulo Coelho', 'NXB Hội Nhà văn', 2018, '978-604-0-00004-4', 3, 12, 10, 'Tiểu thuyết nổi tiếng thế giới', NULL, 'Kệ C1', 'available', '2025-11-07 21:59:10', '2025-11-07 22:49:42'),
(5, 'Toán cao cấp A1', 'Đỗ Công Khanh', 'NXB Giáo dục', 2020, '978-604-0-00005-5', 4, 25, 20, 'Giáo trình toán cao cấp', NULL, 'Kệ D1', 'available', '2025-11-07 21:59:10', '2025-11-10 22:20:36'),
(7, 'Lịch sử Việt Nam', 'Trần Trọng Kim', 'NXB Văn học', 2017, '978-604-0-00006-6', 6, 10, 8, 'Lịch sử Việt Nam qua các thời kỳ', NULL, 'Kệ F1', 'available', '2025-11-07 21:59:10', '2025-11-24 14:41:59'),
(8, 'Đắc Nhân Tâm', 'Dale Carnegie', 'NXB Tổng hợp TP.HCM', 2020, '978-604-0-00007-7', 7, 15, 12, 'Sách về kỹ năng giao tiếp', NULL, 'Kệ G1', 'available', '2025-11-07 21:59:10', NULL),
(9, 'Cơ sở dữ liệu', 'Nguyễn Tuệ', 'NXB Khoa học & Kỹ thuật', 2021, '978-604-0-00008-8', 1, 18, 14, 'Giáo trình về hệ quản trị cơ sở dữ liệu', NULL, 'Kệ A3', 'available', '2025-11-07 21:59:10', '2025-12-02 08:31:28'),
(10, 'Marketing căn bản', 'Philip Kotler', 'NXB Lao động', 2019, '978-604-0-00009-9', 2, 12, 10, 'Nguyên lý Marketing cơ bản', NULL, 'Kệ B2', 'available', '2025-11-07 21:59:10', NULL),
(20, 'Tư Tưởng Hồ Chí Minh', 'Bộ Giáo Dục và Đào Tạo', 'Nhà xuất bản chính trị Quốc gia sự thật', 0, '', 8, 20, 20, 'Giáo trình Tư tưởng Hồ Chí Minh dành cho sinh viên không chuyên lý luận chính trị', 'uploads/book_covers/6911fecab2df6_1762787018.jpg', 'Kệ C3', 'available', '2025-11-10 22:03:38', '2025-12-03 19:39:53'),
(21, 'Chủ Nghĩa Xã Hội Khoa Học', 'Bộ Giáo Dục và Đào Tạo', 'Nhà xuất bản chính trị Quốc gia sự thật', 0, '', 8, 20, 20, 'Giáo trình Chủ Nghĩa Xã Hội Khoa Học dành cho sinh viên không chuyên lý luận chính trị', 'uploads/book_covers/6912027a5f33e_1762787962.jpg', 'Kệ C3', 'available', '2025-11-10 22:19:22', '2025-11-22 15:06:15'),
(22, 'Tư tưởng Mác Lênin', 'Bộ Giáo Dục và Đào Tạo', 'Nhà xuất bản chính trị Quốc gia sự thật', 0, '', 8, 20, 20, 'Giáo trình Tư tưởng Mác Lênin dành cho bậc đại học không chuyên lý luận chính trị', 'uploads/book_covers/6915962fd344b_1763022383.jpg', 'Kệ C3', 'available', '2025-11-13 15:26:23', '2025-12-02 08:30:05'),
(23, 'Vi Tích Phân A1', 'Phạm Minh Triển', 'Đại học Trà Vinh', 0, '', 9, 25, 25, '', NULL, 'Kệ A1', 'available', '2025-11-20 16:30:41', '2025-11-20 16:32:04'),
(24, 'Đại Số Tuyến Tính', 'Trần Quang Hà', 'Đại học Trà Vinh', 0, '', 9, 20, 20, '', NULL, 'Kệ T1', 'available', '2025-11-24 14:41:18', NULL),
(25, 'Lịch sử Việt Nam', 'Bộ Giáo Dục và Đào Tạo', 'Nhà xuất bản chính trị Quốc gia sự thật', 0, '', 6, 20, 20, '', NULL, 'Kệ H1', 'available', '2025-12-04 00:18:52', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `book_reports`
--

CREATE TABLE `book_reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_id` int(11) DEFAULT NULL,
  `report_type` enum('damaged','missing_pages','torn','stained','lost','other') NOT NULL DEFAULT 'other',
  `description` text NOT NULL,
  `status` enum('pending','reviewed','resolved','rejected') NOT NULL DEFAULT 'pending',
  `librarian_note` text DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `book_reports`
--

INSERT INTO `book_reports` (`id`, `user_id`, `book_id`, `borrow_id`, `report_type`, `description`, `status`, `librarian_note`, `reviewed_by`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(1, 6, 21, NULL, 'missing_pages', 'Sách bị thiếu trang', 'resolved', 'Thứ 2 đem sách bị mất trang đến thư viện, thủ thư sẽ tiến hành thủ tục cấp lại sách mới', 2, '2025-11-25 14:05:53', '2025-11-22 14:49:44', '2025-11-25 14:05:53'),
(2, 6, 21, NULL, 'missing_pages', 'thán chiêu', 'resolved', 'Kệ em', 2, '2025-11-22 15:07:09', '2025-11-22 15:01:44', '2025-11-22 15:07:09'),
(3, 6, 22, NULL, 'damaged', 'Sách bị rách, một số trang bị in xéo mất nội dung', 'resolved', 'Sáng thứ 7 8h30 đem sách bị hư hỏng', 2, '2025-11-24 15:42:48', '2025-11-24 13:05:24', '2025-11-24 15:42:48'),
(4, 6, 22, NULL, 'stained', 'Phần mục lục bị ố vàng, che mất một số phần', 'rejected', 'Lúc mượn đã kiểm tra kỹ', 2, '2025-11-24 15:43:17', '2025-11-24 15:42:21', '2025-11-24 15:43:17'),
(5, 6, 20, NULL, 'missing_pages', 'Trang 36 thiếu 18 dòng', 'resolved', '', 2, '2025-12-03 22:57:09', '2025-12-02 08:33:45', '2025-12-03 22:57:09'),
(6, 6, 2, 33, 'lost', 'Mất sách', 'resolved', '', 2, '2025-12-03 23:00:19', '2025-12-03 22:59:41', '2025-12-03 23:00:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `borrows`
--

CREATE TABLE `borrows` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `approved_by` int(11) DEFAULT NULL COMMENT 'ID thủ thư duyệt yêu cầu',
  `approved_at` datetime DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL COMMENT 'Lý do từ chối nếu status = rejected',
  `book_id` int(11) NOT NULL,
  `borrow_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('pending','approved','borrowed','returned','overdue','rejected','lost') NOT NULL DEFAULT 'pending' COMMENT 'pending: Chờ duyệt, approved: Đã duyệt, borrowed: Đang mượn, returned: Đã trả, overdue: Quá hạn, rejected: Từ chối, lost: Mất sách',
  `fine_amount` decimal(10,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `borrows`
--

INSERT INTO `borrows` (`id`, `user_id`, `approved_by`, `approved_at`, `rejection_reason`, `book_id`, `borrow_date`, `due_date`, `return_date`, `status`, `fine_amount`, `notes`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, NULL, NULL, 1, '2024-11-01', '2024-11-15', NULL, 'borrowed', 0.00, NULL, '2025-11-07 21:59:10', NULL),
(2, 3, NULL, NULL, NULL, 4, '2024-11-03', '2024-11-17', NULL, 'borrowed', 0.00, NULL, '2025-11-07 21:59:10', NULL),
(3, 4, NULL, NULL, NULL, 2, '2024-10-25', '2024-11-08', NULL, 'overdue', 0.00, NULL, '2025-11-07 21:59:10', NULL),
(4, 4, NULL, NULL, NULL, 5, '2024-11-05', '2024-11-19', NULL, 'borrowed', 0.00, NULL, '2025-11-07 21:59:10', NULL),
(22, 6, 2, '2025-11-17 13:23:35', 'Sách này em đã mượn rồi', 20, '2025-11-17', '2025-12-01', NULL, 'rejected', 0.00, 'Yêu cầu mượn qua hệ thống online', '2025-11-17 13:17:05', '2025-11-17 13:23:35'),
(29, 9, NULL, NULL, NULL, 7, '2025-11-24', '2025-12-08', NULL, 'borrowed', 0.00, '', '2025-11-24 14:41:59', NULL),
(30, 9, NULL, NULL, NULL, 3, '2025-11-25', '2025-12-09', NULL, 'borrowed', 0.00, 'Trả vào T2 8h30', '2025-11-25 14:04:05', NULL),
(32, 9, NULL, NULL, NULL, 9, '2025-12-02', '2025-12-16', NULL, 'borrowed', 0.00, 'Mượn 7 ngày', '2025-12-02 08:31:28', NULL),
(33, 6, 2, '2025-12-03 22:56:47', NULL, 2, '2025-12-03', '2025-12-17', NULL, 'borrowed', 0.00, 'Yêu cầu mượn qua hệ thống online', '2025-12-03 22:56:20', '2025-12-03 22:56:47');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Công nghệ thông tin', 'cong-nghe-thong-tin', 'Sách về lập trình, phần mềm, mạng máy tính', '2025-11-07 21:59:10', NULL),
(2, 'Kinh tế', 'kinh-te', 'Sách về kinh tế, quản trị, tài chính', '2025-11-07 21:59:10', NULL),
(3, 'Văn học', 'van-hoc', 'Tiểu thuyết, truyện ngắn, thơ ca', '2025-11-07 21:59:10', NULL),
(4, 'Khoa học tự nhiên', 'khoa-hoc-tu-nhien', 'Toán học, Vật lý, Hóa học, Sinh học', '2025-11-07 21:59:10', NULL),
(5, 'Ngoại ngữ', 'ngoai-ngu', 'Tiếng Anh, tiếng Nhật, tiếng Trung', '2025-11-07 21:59:10', NULL),
(6, 'Lịch sử', 'lich-su', 'Lịch sử Việt Nam và thế giới', '2025-11-07 21:59:10', NULL),
(7, 'Tâm lý - Kỹ năng sống', 'tam-ly-ky-nang-song', 'Sách về tâm lý, phát triển bản thân', '2025-11-07 21:59:10', NULL),
(8, 'Giáo trình', 'giao-trinh', 'Giáo trình các môn học', '2025-11-07 21:59:10', NULL),
(9, 'Toán cao cấp', 'toan-cao-cap', 'Sách toán', '2025-11-10 21:15:33', '2025-11-10 21:16:13'),
(10, 'Kỹ năng mềm', 'ky-nang-mem', '', '2025-11-20 16:31:04', NULL),
(11, 'Truyện tranh', 'truyen-tranh', '', '2025-11-24 14:41:38', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `fines`
--

CREATE TABLE `fines` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `borrow_id` int(11) DEFAULT NULL,
  `fine_type` enum('overdue','damaged','lost','other') NOT NULL DEFAULT 'overdue',
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `reason` text NOT NULL,
  `status` enum('unpaid','paid','waived') NOT NULL DEFAULT 'unpaid',
  `created_by` int(11) NOT NULL,
  `paid_date` datetime DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT NULL,
  `payment_note` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `fines`
--

INSERT INTO `fines` (`id`, `user_id`, `borrow_id`, `fine_type`, `amount`, `reason`, `status`, `created_by`, `paid_date`, `paid_amount`, `payment_note`, `created_at`, `updated_at`) VALUES
(1, 6, NULL, 'overdue', 35000.00, 'Quá hạn trả sách', 'waived', 2, NULL, NULL, 'Quản lý là người nhà', '2025-11-24 15:41:10', '2025-11-24 15:44:13'),
(2, 6, NULL, 'overdue', 10000.00, 'Trả sách trể, vui lòng trả sách đúng hạn', 'paid', 2, '2025-12-02 08:32:09', 15000.00, 'Trể 3 ngày', '2025-11-25 15:54:05', '2025-12-02 08:32:09'),
(3, 6, NULL, 'lost', 100000.00, 'Làm mất sách', 'unpaid', 2, NULL, NULL, NULL, '2025-12-03 23:00:43', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `is_read`, `read_at`, `created_at`) VALUES
(1, 3, 'Chào mừng đến với thư viện', 'Chúc bạn có trải nghiệm tốt với hệ thống thư viện điện tử!', 'info', 0, NULL, '2025-11-07 21:59:10'),
(2, 3, 'Sách mới về Công nghệ', 'Thư viện vừa cập nhật nhiều đầu sách mới về CNTT. Mời bạn ghé xem!', 'success', 0, NULL, '2025-11-07 21:59:10'),
(3, 4, 'Nhắc nhở trả sách', 'Bạn có sách sắp đến hạn trả. Vui lòng kiểm tra!', 'warning', 0, NULL, '2025-11-07 21:59:10'),
(4, NULL, 'Thông báo bảo trì', 'Hệ thống sẽ bảo trì vào 0h ngày 10/11/2024', 'info', 1, '2025-11-07 22:37:44', '2025-11-07 21:59:10'),
(5, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Nhà Giả Kim\' thành công. Hạn trả: 21/11/2025. Vui lòng đến thư viện để nhận sách.', 'success', 1, '2025-11-07 22:40:25', '2025-11-07 22:40:11'),
(6, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Nguyên lý Kinh tế học\' thành công. Hạn trả: 21/11/2025. Vui lòng đến thư viện để nhận sách.', 'success', 0, NULL, '2025-11-07 22:44:21'),
(7, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Nguyên lý Kinh tế học\' thành công.', 'info', 0, NULL, '2025-11-07 22:49:40'),
(8, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Nhà Giả Kim\' thành công.', 'info', 0, NULL, '2025-11-07 22:49:42'),
(9, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Nguyên lý Kinh tế học\' thành công. Hạn trả: 21/11/2025. Vui lòng đến thư viện để nhận sách.', 'success', 0, NULL, '2025-11-07 22:49:54'),
(10, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Nguyên lý Kinh tế học\' thành công.', 'info', 1, '2025-11-07 22:55:11', '2025-11-07 22:50:19'),
(11, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Cơ sở dữ liệu\' thành công. Hạn trả: 21/11/2025. Vui lòng đến thư viện để nhận sách.', 'success', 0, NULL, '2025-11-07 22:59:36'),
(12, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Cơ sở dữ liệu\' thành công.', 'info', 1, '2025-11-07 23:29:19', '2025-11-07 22:59:46'),
(13, 3, 'Thông bác về việc mượn - trả sách tại thư viện', 'Các sinh viên vui lòng trả sách đúng hạn', 'info', 0, NULL, '2025-11-07 23:30:58'),
(14, 4, 'Thông bác về việc mượn - trả sách tại thư viện', 'Các sinh viên vui lòng trả sách đúng hạn', 'info', 0, NULL, '2025-11-07 23:30:58'),
(16, 6, 'Thông bác về việc mượn - trả sách tại thư viện', 'Các sinh viên vui lòng trả sách đúng hạn', 'info', 1, '2025-11-07 23:31:05', '2025-11-07 23:30:58'),
(17, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Cơ sở dữ liệu\' thành công. Hạn trả: 22/11/2025. Vui lòng đến thư viện để nhận sách.', 'success', 1, '2025-11-08 20:18:52', '2025-11-08 20:14:36'),
(18, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Cơ sở dữ liệu\' thành công.', 'info', 1, '2025-11-08 20:18:54', '2025-11-08 20:15:22'),
(19, 3, 'Bảo trì hệ thống thư viện', 'Hệ thống thư viện sẽ tiến hành bảo trì từ 8:30 - 14:00 trong hôm nay', 'info', 0, NULL, '2025-11-08 20:18:40'),
(20, 4, 'Bảo trì hệ thống thư viện', 'Hệ thống thư viện sẽ tiến hành bảo trì từ 8:30 - 14:00 trong hôm nay', 'info', 0, NULL, '2025-11-08 20:18:40'),
(22, 6, 'Bảo trì hệ thống thư viện', 'Hệ thống thư viện sẽ tiến hành bảo trì từ 8:30 - 14:00 trong hôm nay', 'info', 1, '2025-11-08 20:18:49', '2025-11-08 20:18:40'),
(23, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Tư tưởng Mác Lênin\' thành công. Hạn trả: 22/11/2025. Vui lòng đến thư viện để nhận sách.', 'success', 0, NULL, '2025-11-08 20:43:45'),
(24, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Tư tưởng Mác Lênin\' thành công.', 'info', 0, NULL, '2025-11-08 20:47:50'),
(25, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Tư tưởng Mác Lênin\' thành công. Hạn trả: 22/11/2025. Vui lòng đến thư viện để nhận sách.', 'success', 0, NULL, '2025-11-08 21:03:08'),
(26, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Tư tưởng Mác Lênin\' thành công.', 'info', 0, NULL, '2025-11-10 21:30:39'),
(27, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Tư tưởng Mác Lênin\' thành công. Hạn trả: 24/11/2025. Vui lòng đến thư viện để nhận sách.', 'success', 0, NULL, '2025-11-10 21:30:45'),
(28, 2, 'test', 'Test tin nhắn', 'info', 1, '2025-11-10 21:55:38', '2025-11-10 21:44:51'),
(30, 2, 'thử', 'test', 'info', 1, '2025-11-10 21:55:38', '2025-11-10 21:45:49'),
(31, 3, 'Thông báo về việc mượn trả sách', 'Sinh viên vui lòng trả đúng hạn', 'info', 0, NULL, '2025-11-10 21:47:27'),
(32, 4, 'Thông báo về việc mượn trả sách', 'Sinh viên vui lòng trả đúng hạn', 'info', 0, NULL, '2025-11-10 21:47:27'),
(34, 6, 'Thông báo về việc mượn trả sách', 'Sinh viên vui lòng trả đúng hạn', 'info', 1, '2025-11-10 21:47:36', '2025-11-10 21:47:27'),
(35, 2, 'test', 'thử', 'info', 1, '2025-11-10 21:55:38', '2025-11-10 21:49:09'),
(36, 2, '1', '1', 'warning', 1, '2025-11-10 21:55:38', '2025-11-10 21:51:08'),
(38, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Tư tưởng Mác Lênin\' thành công.', 'info', 0, NULL, '2025-11-10 21:53:33'),
(39, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Tư tưởng Mác Lênin\' thành công. Hạn trả: 24/11/2025. Vui lòng đến thư viện để nhận sách.', 'success', 0, NULL, '2025-11-10 21:53:37'),
(40, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Tư tưởng Mác Lênin\' thành công.', 'info', 1, '2025-11-10 21:53:41', '2025-11-10 21:53:39'),
(42, 2, '1', '1', 'success', 1, '2025-11-10 21:58:31', '2025-11-10 21:58:19'),
(43, 2, 'test', '1', 'info', 1, '2025-11-10 22:00:43', '2025-11-10 21:59:07'),
(44, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Tư Tưởng Hồ Chí Minh\' thành công. Hạn trả: 24/11/2025. Vui lòng đến thư viện để nhận sách.', 'success', 0, NULL, '2025-11-10 22:04:42'),
(45, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Tư tưởng Mác Lênin\' thành công. Hạn trả: 24/11/2025. Vui lòng đến thư viện để nhận sách.', 'success', 0, NULL, '2025-11-10 22:04:48'),
(46, 6, 'Trả sách thành công', 'Bạn đã trả sách \'Tư tưởng Mác Lênin\'', 'success', 0, NULL, '2025-11-10 22:05:24'),
(47, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Lập trình C cơ bản\'. Vui lòng trả sách trước ngày 24/11/2025', 'success', 0, NULL, '2025-11-10 22:05:47'),
(48, 2, '1', '1', 'warning', 1, '2025-11-10 22:06:52', '2025-11-10 22:06:11'),
(51, 2, '1', '1', 'success', 1, '2025-11-10 22:06:54', '2025-11-10 22:06:19'),
(52, 3, 'Nhắc nhở trả sách', 'Sinh viên có sách sắp đến hạn trả vui lòng chuẩn bị trả sách đúng hạn để tránh bị phạt.', 'warning', 0, NULL, '2025-11-10 22:07:18'),
(53, 4, 'Nhắc nhở trả sách', 'Sinh viên có sách sắp đến hạn trả vui lòng chuẩn bị trả sách đúng hạn để tránh bị phạt.', 'warning', 0, NULL, '2025-11-10 22:07:18'),
(55, 6, 'Nhắc nhở trả sách', 'Sinh viên có sách sắp đến hạn trả vui lòng chuẩn bị trả sách đúng hạn để tránh bị phạt.', 'warning', 0, NULL, '2025-11-10 22:07:18'),
(56, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Lập trình C cơ bản\' thành công.', 'info', 0, NULL, '2025-11-10 22:08:13'),
(57, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Tư Tưởng Hồ Chí Minh\' thành công.', 'info', 0, NULL, '2025-11-10 22:11:38'),
(58, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Toán cao cấp A1\'. Vui lòng trả sách trước ngày 24/11/2025', 'success', 0, NULL, '2025-11-10 22:16:40'),
(59, 3, 'Thông báo đóng cửa', 'Thư viện sẽ tạm đóng cửa vào hôm nay, Các bạn sinh viên vui lòng trả sách trước thời gian này.', 'info', 0, NULL, '2025-11-10 22:20:16'),
(60, 4, 'Thông báo đóng cửa', 'Thư viện sẽ tạm đóng cửa vào hôm nay, Các bạn sinh viên vui lòng trả sách trước thời gian này.', 'info', 0, NULL, '2025-11-10 22:20:16'),
(62, 6, 'Thông báo đóng cửa', 'Thư viện sẽ tạm đóng cửa vào hôm nay, Các bạn sinh viên vui lòng trả sách trước thời gian này.', 'info', 1, '2025-11-13 15:19:50', '2025-11-10 22:20:16'),
(63, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Toán cao cấp A1\' thành công.', 'info', 1, '2025-11-10 22:21:26', '2025-11-10 22:20:36'),
(64, 2, '1', '1', 'warning', 1, '2025-11-10 22:22:57', '2025-11-10 22:22:26'),
(67, 2, '1', '1', 'warning', 1, '2025-11-10 22:22:54', '2025-11-10 22:22:33'),
(68, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Tư Tưởng Hồ Chí Minh\' thành công. Hạn trả: 27/11/2025. Vui lòng đến thư viện để nhận sách.', 'success', 0, NULL, '2025-11-13 15:20:31'),
(69, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Tư tưởng Mác Lênin\' thành công. Hạn trả: 27/11/2025. Vui lòng đến thư viện để nhận sách.', 'success', 1, '2025-11-13 15:46:05', '2025-11-13 15:28:14'),
(70, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Tư tưởng Mác Lênin\' thành công.', 'info', 0, NULL, '2025-11-17 13:09:53'),
(71, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Tư Tưởng Hồ Chí Minh\' thành công.', 'info', 0, NULL, '2025-11-17 13:09:55'),
(72, 6, 'Yêu cầu mượn sách đã được gửi', 'Yêu cầu mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\' đã được gửi đến thủ thư. Vui lòng chờ xét duyệt.', 'info', 0, NULL, '2025-11-17 13:10:44'),
(73, 2, 'Yêu cầu mượn sách mới', 'Sinh viên có yêu cầu mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\'. Vui lòng xem và xét duyệt.', 'info', 1, '2025-11-17 13:13:43', '2025-11-17 13:10:44'),
(76, 6, 'Yêu cầu mượn sách được chấp nhận', 'Yêu cầu mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\' đã được duyệt. Vui lòng đến thư viện để nhận sách trong vòng 3 ngày.', 'success', 0, NULL, '2025-11-17 13:11:02'),
(77, 2, 'Bảo trì hệ thống', 'Hệ thống thư viện sẽ tiến hành bảo trì vào lúc 14:00 17/11/2025, yêu cầu thủ thư thông báo tới các sinh viên không tiến hành mượn trả sách nhằm tránh sự cố', 'info', 1, '2025-11-17 13:13:46', '2025-11-17 13:13:32'),
(78, 3, 'Bảo trì hệ thống', 'Hệ thống thư viện sẽ tiến hành bảo trì vào lúc 14:00 17/11/2025, trong thời gian bảo trì, yêu cầu sinh viên không thực hiện bất kì tác vụ nào nhằm tránh sự cố', 'warning', 0, NULL, '2025-11-17 13:15:54'),
(79, 4, 'Bảo trì hệ thống', 'Hệ thống thư viện sẽ tiến hành bảo trì vào lúc 14:00 17/11/2025, trong thời gian bảo trì, yêu cầu sinh viên không thực hiện bất kì tác vụ nào nhằm tránh sự cố', 'warning', 0, NULL, '2025-11-17 13:15:54'),
(81, 6, 'Bảo trì hệ thống', 'Hệ thống thư viện sẽ tiến hành bảo trì vào lúc 14:00 17/11/2025, trong thời gian bảo trì, yêu cầu sinh viên không thực hiện bất kì tác vụ nào nhằm tránh sự cố', 'warning', 0, NULL, '2025-11-17 13:15:54'),
(82, 6, 'Yêu cầu mượn sách đã được gửi', 'Yêu cầu mượn sách \'Tư Tưởng Hồ Chí Minh\' đã được gửi đến thủ thư. Vui lòng chờ xét duyệt.', 'info', 0, NULL, '2025-11-17 13:17:05'),
(83, 2, 'Yêu cầu mượn sách mới', 'Sinh viên có yêu cầu mượn sách \'Tư Tưởng Hồ Chí Minh\'. Vui lòng xem và xét duyệt.', 'info', 1, '2025-11-17 13:23:40', '2025-11-17 13:17:05'),
(86, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\' thành công.', 'info', 1, '2025-11-17 14:07:19', '2025-11-17 13:17:16'),
(87, 6, 'Yêu cầu mượn sách bị từ chối', 'Yêu cầu mượn sách \'Tư Tưởng Hồ Chí Minh\' đã bị từ chối. Lý do: Sách này em đã mượn rồi', 'warning', 1, '2025-11-17 13:54:20', '2025-11-17 13:23:35'),
(88, 6, 'Yêu cầu mượn sách đã được gửi', 'Yêu cầu mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\' đã được gửi đến thủ thư. Vui lòng chờ xét duyệt.', 'info', 1, '2025-11-20 15:51:16', '2025-11-20 15:25:16'),
(89, 2, 'Yêu cầu mượn sách mới', 'Sinh viên có yêu cầu mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\'. Vui lòng xem và xét duyệt.', 'info', 1, '2025-11-20 15:26:57', '2025-11-20 15:25:16'),
(92, 6, 'Yêu cầu mượn sách được chấp nhận', 'Yêu cầu mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\' đã được duyệt. Vui lòng đến thư viện để nhận sách trong vòng 3 ngày.', 'success', 1, '2025-11-20 15:51:15', '2025-11-20 15:25:43'),
(93, 2, '1', '1', 'info', 1, '2025-11-20 15:26:58', '2025-11-20 15:26:00'),
(94, 2, '1', '1', 'success', 1, '2025-11-20 16:09:56', '2025-11-20 16:02:01'),
(97, 2, '1', '1', 'warning', 1, '2025-11-20 16:29:40', '2025-11-20 16:27:58'),
(98, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\' thành công.', 'info', 0, NULL, '2025-11-20 16:28:20'),
(99, 6, 'Yêu cầu mượn sách đã được gửi', 'Yêu cầu mượn sách \'Tư Tưởng Hồ Chí Minh\' đã được gửi đến thủ thư. Vui lòng chờ xét duyệt.', 'info', 0, NULL, '2025-11-20 16:28:51'),
(100, 2, 'Yêu cầu mượn sách mới', 'Sinh viên có yêu cầu mượn sách \'Tư Tưởng Hồ Chí Minh\'. Vui lòng xem và xét duyệt.', 'info', 1, '2025-11-20 16:31:28', '2025-11-20 16:28:51'),
(101, 6, 'Yêu cầu mượn sách được chấp nhận', 'Yêu cầu mượn sách \'Tư Tưởng Hồ Chí Minh\' đã được duyệt. Vui lòng đến thư viện để nhận sách trong vòng 3 ngày.', 'success', 1, '2025-11-20 16:31:59', '2025-11-20 16:31:10'),
(102, 6, 'Mượn sách thành công', 'Bạn đã mượn sách \'Vi Tích Phân A1\'. Vui lòng trả sách trước ngày 04/12/2025', 'success', 1, '2025-11-20 16:31:59', '2025-11-20 16:31:23'),
(103, 3, 'Thông báo đóng cửa', 'Thư viện sẽ đóng cửa vào ngày... do... Các bạn sinh viên vui lòng trả sách trước thời gian này.', 'warning', 0, NULL, '2025-11-20 16:31:45'),
(104, 4, 'Thông báo đóng cửa', 'Thư viện sẽ đóng cửa vào ngày... do... Các bạn sinh viên vui lòng trả sách trước thời gian này.', 'warning', 0, NULL, '2025-11-20 16:31:45'),
(106, 6, 'Thông báo đóng cửa', 'Thư viện sẽ đóng cửa vào ngày... do... Các bạn sinh viên vui lòng trả sách trước thời gian này.', 'warning', 1, '2025-11-20 16:31:58', '2025-11-20 16:31:45'),
(107, 9, 'Thông báo đóng cửa', 'Thư viện sẽ đóng cửa vào ngày... do... Các bạn sinh viên vui lòng trả sách trước thời gian này.', 'warning', 0, NULL, '2025-11-20 16:31:45'),
(108, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Vi Tích Phân A1\' thành công.', 'info', 0, NULL, '2025-11-20 16:32:04'),
(109, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Tư Tưởng Hồ Chí Minh\' thành công.', 'info', 0, NULL, '2025-11-20 16:32:06'),
(110, 6, 'Yêu cầu mượn sách đã được gửi', 'Yêu cầu mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\' đã được gửi đến thủ thư. Vui lòng chờ xét duyệt.', 'info', 0, NULL, '2025-11-22 14:43:31'),
(111, 2, 'Yêu cầu mượn sách mới', 'Sinh viên có yêu cầu mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\'. Vui lòng xem và xét duyệt.', 'info', 1, '2025-11-22 14:43:57', '2025-11-22 14:43:31'),
(112, 6, 'Yêu cầu mượn sách được chấp nhận', 'Yêu cầu mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\' đã được duyệt. Vui lòng đến thư viện để nhận sách trong vòng 3 ngày.', 'success', 0, NULL, '2025-11-22 14:43:54'),
(113, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\' thành công.', 'info', 0, NULL, '2025-11-22 14:49:54'),
(114, 6, 'Yêu cầu mượn sách đã được gửi', 'Yêu cầu mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\' đã được gửi đến thủ thư. Vui lòng chờ xét duyệt.', 'info', 0, NULL, '2025-11-22 14:58:09'),
(115, 2, 'Yêu cầu mượn sách mới', 'Sinh viên có yêu cầu mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\'. Vui lòng xem và xét duyệt.', 'info', 1, '2025-11-22 15:01:59', '2025-11-22 14:58:09'),
(116, 6, 'Yêu cầu mượn sách được chấp nhận', 'Yêu cầu mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\' đã được duyệt. Vui lòng đến thư viện để nhận sách trong vòng 3 ngày.', 'success', 0, NULL, '2025-11-22 15:01:12'),
(117, 2, 'Báo cáo tình trạng sách mới', 'Sinh viên báo cáo sách \'Chủ Nghĩa Xã Hội Khoa Học\' có vấn đề: Thiếu trang. Vui lòng xem xét.', 'warning', 1, '2025-11-22 15:01:58', '2025-11-22 15:01:44'),
(118, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Chủ Nghĩa Xã Hội Khoa Học\' thành công.', 'info', 0, NULL, '2025-11-22 15:06:15'),
(119, 6, 'Yêu cầu mượn sách đã được gửi', 'Yêu cầu mượn sách \'Tư tưởng Mác Lênin\' đã được gửi đến thủ thư. Vui lòng chờ xét duyệt.', 'info', 0, NULL, '2025-11-22 15:06:24'),
(120, 2, 'Yêu cầu mượn sách mới', 'Sinh viên có yêu cầu mượn sách \'Tư tưởng Mác Lênin\'. Vui lòng xem và xét duyệt.', 'info', 1, '2025-11-24 13:09:24', '2025-11-22 15:06:24'),
(121, 6, 'Yêu cầu mượn sách được chấp nhận', 'Yêu cầu mượn sách \'Tư tưởng Mác Lênin\' đã được duyệt. Vui lòng đến thư viện để nhận sách trong vòng 3 ngày.', 'success', 0, NULL, '2025-11-22 15:06:41'),
(122, 6, 'Cập nhật báo cáo tình trạng sách', 'Báo cáo của bạn về sách \'Chủ Nghĩa Xã Hội Khoa Học\' đã được giải quyết. Ghi chú: Kệ em', 'success', 0, NULL, '2025-11-22 15:07:09'),
(123, 2, 'Báo cáo tình trạng sách mới', 'Sinh viên báo cáo sách \'Tư tưởng Mác Lênin\' có vấn đề: Sách bị hư hỏng. Vui lòng xem xét.', 'warning', 1, '2025-11-24 13:09:23', '2025-11-24 13:05:24'),
(124, 6, 'Cập nhật báo cáo tình trạng sách', 'Báo cáo của bạn về sách \'Tư tưởng Mác Lênin\' đang được xem xét. Ghi chú: Sáng thứ 7 8h30 đem sách bị hư hỏng', 'info', 0, NULL, '2025-11-24 13:09:07'),
(125, 9, 'Mượn sách thành công', 'Bạn đã mượn sách \'Lịch sử Việt Nam\'. Vui lòng trả sách trước ngày 08/12/2025', 'success', 0, NULL, '2025-11-24 14:41:59'),
(126, 6, 'Bạn có phiếu phạt mới', 'Bạn có phiếu phạt mới với số tiền: 35,000 VNĐ. Lý do: Quá hạn trả sách', 'warning', 0, NULL, '2025-11-24 15:41:10'),
(127, 2, 'Báo cáo tình trạng sách mới', 'Sinh viên báo cáo sách \'Tư tưởng Mác Lênin\' có vấn đề: Bị dơ, ố. Vui lòng xem xét.', 'warning', 1, '2025-11-24 15:44:18', '2025-11-24 15:42:21'),
(128, 6, 'Cập nhật báo cáo tình trạng sách', 'Báo cáo của bạn về sách \'Tư tưởng Mác Lênin\' đã được giải quyết. Ghi chú: Sáng thứ 7 8h30 đem sách bị hư hỏng', 'success', 0, NULL, '2025-11-24 15:42:48'),
(129, 6, 'Cập nhật báo cáo tình trạng sách', 'Báo cáo của bạn về sách \'Tư tưởng Mác Lênin\' bị từ chối. Ghi chú: Lúc mượn đã kiểm tra kỹ', 'info', 0, NULL, '2025-11-24 15:43:17'),
(130, 6, 'Phiếu phạt đã được miễn', 'Phiếu phạt số #1 đã được miễn. Ghi chú: Quản lý là người nhà', 'success', 0, NULL, '2025-11-24 15:44:13'),
(131, 3, 'Đóng cửa nghỉ 1 ngày', 'Do bên quản lý bốc trúng vé đi du lịch nên thư viện nghỉ 1 ngày nha mấy em', 'success', 0, NULL, '2025-11-24 15:45:06'),
(132, 4, 'Đóng cửa nghỉ 1 ngày', 'Do bên quản lý bốc trúng vé đi du lịch nên thư viện nghỉ 1 ngày nha mấy em', 'success', 0, NULL, '2025-11-24 15:45:06'),
(134, 6, 'Đóng cửa nghỉ 1 ngày', 'Do bên quản lý bốc trúng vé đi du lịch nên thư viện nghỉ 1 ngày nha mấy em', 'success', 0, NULL, '2025-11-24 15:45:06'),
(135, 9, 'Đóng cửa nghỉ 1 ngày', 'Do bên quản lý bốc trúng vé đi du lịch nên thư viện nghỉ 1 ngày nha mấy em', 'success', 0, NULL, '2025-11-24 15:45:06'),
(136, 9, 'Mượn sách thành công', 'Bạn đã mượn sách \'Nguyên lý Kinh tế học\'. Vui lòng trả sách trước ngày 09/12/2025', 'success', 0, NULL, '2025-11-25 14:04:05'),
(137, 6, 'Cập nhật báo cáo tình trạng sách', 'Báo cáo của bạn về sách \'Chủ Nghĩa Xã Hội Khoa Học\' đã được giải quyết. Ghi chú: Thứ 2 đem sách bị mất trang đến thư viện, thủ thư sẽ tiến hành thủ tục cấp lại sách mới', 'success', 0, NULL, '2025-11-25 14:05:53'),
(138, 6, 'Bạn có phiếu phạt mới', 'Bạn có phiếu phạt mới với số tiền: 10,000 VNĐ. Lý do: Trả sách trể, vui lòng trả sách đúng hạn', 'warning', 1, '2025-12-02 08:33:01', '2025-11-25 15:54:05'),
(139, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Tư tưởng Mác Lênin\' thành công.', 'info', 1, '2025-12-02 08:32:59', '2025-12-02 08:30:05'),
(140, 6, 'Yêu cầu mượn sách đã được gửi', 'Yêu cầu mượn sách \'Tư Tưởng Hồ Chí Minh\' đã được gửi đến thủ thư. Vui lòng chờ xét duyệt.', 'info', 1, '2025-12-02 08:32:57', '2025-12-02 08:30:13'),
(141, 2, 'Yêu cầu mượn sách mới', 'Sinh viên có yêu cầu mượn sách \'Tư Tưởng Hồ Chí Minh\'. Vui lòng xem và xét duyệt.', 'info', 1, '2025-12-02 08:32:36', '2025-12-02 08:30:13'),
(142, 6, 'Yêu cầu mượn sách được chấp nhận', 'Yêu cầu mượn sách \'Tư Tưởng Hồ Chí Minh\' đã được duyệt. Vui lòng đến thư viện để nhận sách trong vòng 3 ngày.', 'success', 1, '2025-12-02 08:32:56', '2025-12-02 08:30:52'),
(143, 9, 'Mượn sách thành công', 'Bạn đã mượn sách \'Cơ sở dữ liệu\'. Vui lòng trả sách trước ngày 16/12/2025', 'success', 0, NULL, '2025-12-02 08:31:28'),
(144, 6, 'Phiếu phạt đã được xác nhận thanh toán', 'Phiếu phạt số #2 đã được xác nhận thanh toán với số tiền: 15,000 VNĐ.', 'success', 1, '2025-12-02 08:32:53', '2025-12-02 08:32:09'),
(145, 3, '12', '12', 'info', 0, NULL, '2025-12-02 08:32:43'),
(146, 4, '12', '12', 'info', 0, NULL, '2025-12-02 08:32:43'),
(148, 6, '12', '12', 'info', 1, '2025-12-02 08:32:54', '2025-12-02 08:32:43'),
(149, 9, '12', '12', 'info', 0, NULL, '2025-12-02 08:32:43'),
(150, 2, 'Báo cáo tình trạng sách mới', 'Sinh viên báo cáo sách \'Tư Tưởng Hồ Chí Minh\' có vấn đề: Thiếu trang. Vui lòng xem xét.', 'warning', 1, '2025-12-02 13:53:05', '2025-12-02 08:33:45'),
(151, 2, 'Trả tiền code web', 'trả tiền 36.0000d', '', 1, '2025-12-02 13:53:03', '2025-12-02 08:36:51'),
(152, 6, 'Hủy mượn sách', 'Bạn đã hủy mượn sách \'Tư Tưởng Hồ Chí Minh\' thành công.', 'info', 1, '2025-12-03 22:29:02', '2025-12-03 19:39:53'),
(153, 6, 'Yêu cầu mượn sách đã được gửi', 'Yêu cầu mượn sách \'Cấu trúc dữ liệu và giải thuật\' đã được gửi đến thủ thư. Vui lòng chờ xét duyệt.', 'info', 0, NULL, '2025-12-03 22:56:20'),
(154, 2, 'Yêu cầu mượn sách mới', 'Sinh viên có yêu cầu mượn sách \'Cấu trúc dữ liệu và giải thuật\'. Vui lòng xem và xét duyệt.', 'info', 1, '2025-12-03 22:57:19', '2025-12-03 22:56:20'),
(155, 6, 'Yêu cầu mượn sách được chấp nhận', 'Yêu cầu mượn sách \'Cấu trúc dữ liệu và giải thuật\' đã được duyệt. Vui lòng đến thư viện để nhận sách trong vòng 3 ngày.', 'success', 0, NULL, '2025-12-03 22:56:47'),
(156, 6, 'Cập nhật báo cáo tình trạng sách', 'Báo cáo của bạn về sách \'Tư Tưởng Hồ Chí Minh\' đã được giải quyết. ', 'success', 0, NULL, '2025-12-03 22:57:09'),
(157, 2, 'Báo cáo tình trạng sách mới', 'Sinh viên báo cáo sách \'Cấu trúc dữ liệu và giải thuật\' có vấn đề: Mất sách. Vui lòng xem xét.', 'warning', 0, NULL, '2025-12-03 22:59:41'),
(158, 6, 'Cập nhật báo cáo tình trạng sách', 'Báo cáo của bạn về sách \'Cấu trúc dữ liệu và giải thuật\' đã được giải quyết. ', 'success', 0, NULL, '2025-12-03 23:00:19'),
(159, 6, 'Bạn có phiếu phạt mới', 'Bạn có phiếu phạt mới với số tiền: 100,000 VNĐ. Lý do: Làm mất sách', 'warning', 0, NULL, '2025-12-03 23:00:43'),
(160, 2, '1', '1', 'warning', 0, NULL, '2025-12-04 00:32:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `key_value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `settings`
--

INSERT INTO `settings` (`id`, `key_name`, `key_value`, `description`, `updated_at`) VALUES
(1, 'site_name', 'Thư viện Đại học Trà Vinh', 'Tên thư viện', NULL),
(2, 'max_borrow_days', '14', 'Số ngày mượn tối đa', NULL),
(3, 'max_books_per_user', '5', 'Số sách mượn tối đa mỗi lần', NULL),
(4, 'overdue_fine_per_day', '5000', 'Phí phạt quá hạn (VNĐ/ngày)', NULL),
(5, 'email_notification', '1', 'Bật/tắt thông báo email', NULL),
(6, 'library_address', 'Số 126, Nguyễn Thiện Thành, Phường 5, TP. Trà Vinh', 'Địa chỉ thư viện', NULL),
(7, 'library_phone', '0294.3855246', 'Số điện thoại thư viện', NULL),
(8, 'library_email', 'library@tvu.edu.vn', 'Email thư viện', NULL),
(9, 'contact_email', 'library@tvu.edu.vn', 'Email há»— trá»£ cá»§a thÆ° viá»‡n', '2025-12-03 23:35:54'),
(10, 'contact_phone', '(0294) 3855 246', 'Sá»‘ Ä‘iá»‡n thoáº¡i liÃªn há»‡ thÆ° viá»‡n', '2025-12-03 23:35:54'),
(11, 'contact_address', 'Số 126, Nguyễn Thiện Thành, Trà Vinh', 'Äá»‹a chá»‰ thÆ° viá»‡n', '2025-12-03 23:35:54'),
(12, 'date_format', 'd/m/Y', 'Äá»‹nh dáº¡ng hiá»ƒn thá»‹ ngÃ y thÃ¡ng (d/m/Y, Y-m-d, m/d/Y)', '2025-12-03 23:35:54'),
(13, 'datetime_format', 'd/m/Y H:i', 'Äá»‹nh dáº¡ng hiá»ƒn thá»‹ ngÃ y giá»', '2025-12-03 23:35:54'),
(14, 'time_format', 'H:i', 'Äá»‹nh dáº¡ng hiá»ƒn thá»‹ giá»', '2025-12-03 23:35:54'),
(15, 'timezone', 'Asia/Ho_Chi_Minh', 'MÃºi giá» há»‡ thá»‘ng', '2025-12-03 23:35:54'),
(16, 'maintenance_mode', '0', 'Cháº¿ Ä‘á»™ báº£o trÃ¬ (0: Táº¯t, 1: Báº­t)', '2025-12-03 23:35:54'),
(17, 'maintenance_message', 'Web bảo trì!', 'ThÃ´ng bÃ¡o khi báº­t cháº¿ Ä‘á»™ báº£o trÃ¬', '2025-12-03 23:35:54'),
(18, 'allow_renewal', '1', 'Cho phÃ©p gia háº¡n sÃ¡ch (0: KhÃ´ng, 1: CÃ³)', '2025-12-03 23:35:54'),
(19, 'max_renewal_times', '2', 'Sá»‘ láº§n gia háº¡n tá»‘i Ä‘a cho má»—i cuá»‘n sÃ¡ch', '2025-12-03 23:35:54'),
(20, 'renewal_days', '7', 'Sá»‘ ngÃ y Ä‘Æ°á»£c gia háº¡n thÃªm má»—i láº§n', '2025-12-03 23:35:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `student_code` varchar(20) DEFAULT NULL,
  `class_code` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('admin','librarian','student') NOT NULL DEFAULT 'student',
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `student_code`, `class_code`, `phone`, `address`, `avatar`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@tvu.edu.vn', 'Quản trị viên', NULL, NULL, '0123456789', NULL, NULL, 'admin', 'active', '2025-11-07 21:59:10', NULL),
(2, 'librarian', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'librarian@tvu.edu.vn', 'Thủ thư', NULL, NULL, '0987654321', NULL, NULL, 'librarian', 'active', '2025-11-07 21:59:10', '2025-11-08 20:22:49'),
(3, 'student', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student@tvu.edu.vn', 'Trần Thị Mai', '2151120001', NULL, '0369852147', NULL, NULL, 'student', 'active', '2025-11-07 21:59:10', NULL),
(4, 'nguyenvana', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'nguyenvana@student.tvu.edu.vn', 'Nguyễn Văn A', '2151120002', NULL, '0912345678', NULL, NULL, 'student', 'active', '2025-11-07 21:59:10', NULL),
(6, 'KhangLa', '$2y$10$3jWE5atBYX4CaHAppli0eesrnr/t29qtLRNev3gakftka1grR3BZy', '110122090@st.tvu.edu.vn', 'La Thuấn Khang', '110122090', 'DA22TTD', '1234567890', '', 'uploads/user_avatars/6911fb2e58c55_1762786094.jpg', 'student', 'active', '2025-11-07 22:19:45', '2025-11-10 22:10:55'),
(9, 'DuyTan', '$2y$10$RbiDfmCctGItOmDLW/HVG.0VIWW3TzEfE07Hrv2NAeZG8IzsZuf.e', '110122243@st.tvu.edu.vn', 'Phạm Duy Tân', '110122243', 'DA22TTD', '1234567890', '', 'uploads/user_avatars/691ee068b18d9_1763631208.jpg', 'student', 'active', '2025-11-20 16:27:35', '2025-11-20 16:33:35'),
(10, 'GiaHao', '$2y$10$dCYkNLQcyO.vPeKf5PlE5.sLjq/IVEz/pQUcxIjB3WjB2HLPYAXJ.', '110122070@st.tvu.edu.vn', 'Đỗ Gia Hào', '110122070', NULL, '', '', NULL, 'student', 'active', '2025-12-04 00:35:01', NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `title` (`title`),
  ADD KEY `author` (`author`),
  ADD KEY `isbn` (`isbn`),
  ADD KEY `status` (`status`);

--
-- Chỉ mục cho bảng `book_reports`
--
ALTER TABLE `book_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `borrow_id` (`borrow_id`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `fk_book_reports_reviewer` (`reviewed_by`);

--
-- Chỉ mục cho bảng `borrows`
--
ALTER TABLE `borrows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `status` (`status`),
  ADD KEY `borrow_date` (`borrow_date`),
  ADD KEY `due_date` (`due_date`),
  ADD KEY `fk_borrows_approved_by` (`approved_by`),
  ADD KEY `idx_status_created` (`status`,`created_at`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `name` (`name`);

--
-- Chỉ mục cho bảng `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `borrow_id` (`borrow_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `is_read` (`is_read`),
  ADD KEY `created_at` (`created_at`);

--
-- Chỉ mục cho bảng `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role` (`role`),
  ADD KEY `status` (`status`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `book_reports`
--
ALTER TABLE `book_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `borrows`
--
ALTER TABLE `borrows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `fines`
--
ALTER TABLE `fines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT cho bảng `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_books_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `book_reports`
--
ALTER TABLE `book_reports`
  ADD CONSTRAINT `fk_book_reports_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_book_reports_borrow` FOREIGN KEY (`borrow_id`) REFERENCES `borrows` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_book_reports_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_book_reports_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `borrows`
--
ALTER TABLE `borrows`
  ADD CONSTRAINT `fk_borrows_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_borrows_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_borrows_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fk_fines_borrow` FOREIGN KEY (`borrow_id`) REFERENCES `borrows` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_fines_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_fines_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
