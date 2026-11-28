CREATE DATABASE IF NOT EXISTS doof_food CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE doof_food;

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    phone VARCHAR(20) DEFAULT '',
    member_rank VARCHAR(30) DEFAULT 'Bạc',
    role VARCHAR(20) DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS tb_foods (
    food_id INT AUTO_INCREMENT PRIMARY KEY,
    food_name VARCHAR(255) NOT NULL,
    food_category VARCHAR(50) NOT NULL,
    food_tag VARCHAR(100) DEFAULT '',
    food_desc TEXT,
    food_address VARCHAR(255) DEFAULT '',
    food_price INT NOT NULL DEFAULT 0,
    food_image VARCHAR(255) DEFAULT '',
    food_options VARCHAR(255) DEFAULT ''
);

CREATE TABLE IF NOT EXISTS tb_orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    order_code VARCHAR(30) NOT NULL UNIQUE,
    user_id INT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_address VARCHAR(255) NOT NULL,
    order_note VARCHAR(255) DEFAULT '',
    payment_method VARCHAR(20) DEFAULT 'COD',
    voucher_code VARCHAR(30) DEFAULT '',
    discount_amount INT DEFAULT 0,
    total_amount INT DEFAULT 0,
    order_status VARCHAR(50) DEFAULT 'Đang chuẩn bị',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tb_order_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    food_id INT NOT NULL,
    food_name VARCHAR(255) NOT NULL,
    food_option VARCHAR(100) DEFAULT '',
    price INT NOT NULL,
    quantity INT NOT NULL
);

CREATE TABLE IF NOT EXISTS tb_catering (
    catering_id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    service_type VARCHAR(50) NOT NULL,
    meal_quantity INT NOT NULL DEFAULT 1,
    serve_date DATE NOT NULL,
    location VARCHAR(255) DEFAULT '',
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, fullname, phone, member_rank, role) VALUES
('admin', '$2y$10$8/PB2g/QE9JIomRnWshg2u97d0yWiuwVrY3zuKYMwskKkpPcPvCK2', 'Quản trị Doof Food', '0123456789', 'Kim Cương', 'admin'),
('khach', '$2y$10$8/PB2g/QE9JIomRnWshg2u97d0yWiuwVrY3zuKYMwskKkpPcPvCK2', 'Nguyễn Văn A', '0987654321', 'Bạc', 'user');

INSERT INTO tb_foods (food_name, food_category, food_tag, food_desc, food_address, food_price, food_image, food_options) VALUES
('Hamburger bò phô mai', 'do-an-vat', 'Best seller của quán', 'Thịt bò, phô mai cheddar, rau xà lách, cà chua và sốt đặc biệt.', '27 Minh Khai, Quận Hồng Bàng, Hải Phòng', 65000, 'anh html/images/59-620x620-3.jpg', 'Tiêu chuẩn:65000|Cỡ lớn:85000|Cỡ nhỏ:45000'),
('Bánh đa cua Hải Phòng', 'mon-nuoc', 'Đặc sản Hải Phòng', 'Bánh đa đỏ kết hợp cùng riêu cua, chả lá lốt, các loại chả, hành lá và nước dùng đậm đà.', 'Bánh đa cua 48 Lạch Tray - Số 48 Lạch Tray, Ngô Quyền, TP Hải Phòng', 45000, 'anh html/images/images.jpg', 'Phần thường:45000|Phần đặc biệt:65000'),
('Nem cua bể Bà Cụ', 'mon-chien', 'Đặc sản Hải Phòng', 'Chả nem giòn rụm, nhân thịt cua đậm đà, ăn kèm rau sống và nước chấm chua ngọt.', '179 P. Cầu Đất, Gia Viên, Hải Phòng, Việt Nam', 25000, 'anh html/images/nem-cua-be.jpg', ''),
('Bún cá cay', 'mon-nuoc', 'Món ăn bình dân', 'Tô bún cá hấp dẫn cả sắc, hương, vị với phi lê cá chiên giòn bên trong mềm ngọt. Món ăn này dễ chiều vị giác trong tiết thu se lạnh.', '66 P. Lê Lợi, Gia Viên, Hải Phòng, Việt Nam', 35000, 'anh html/images/bun-ca-cay-hai-phong-o-ha-noi-12.jpg', 'Phần thường:35000|Phần đặc biệt:65000'),
('Bánh mì que cay Hải Phòng', 'do-an-vat', 'Đặc sản Hải Phòng', 'Từ một món ăn dân dã, bánh mì que Hải Phòng hiện nay đã trở thành món ăn nổi tiếng hấp dẫn thực khách bốn phương.', 'Tiệm Bánh Mì Cay Ông Cuông - 184 P. Hàng Kênh, Lê Chân, Hải Phòng, Việt Nam', 5000, 'anh html/images/banh-mi-que-hai-phong-o-ha-noi-2.jpg', ''),
('Sủi dìn, chè vừng', 'do-an-vat', 'Giải pháp cho mùa đông', 'Một bát sủi dìn, chè vừng nóng hổi có mùi thơm của gừng, vị bùi của lạc, dừa tươi, rất thích hợp để thưởng thức vào những ngày gió lạnh.', 'Quán Đông Dung - Số 163 Cầu Đất, Ngô Quyền', 20000, 'anh html/images/sui-din-thumb.jpg', ''),
('Bánh bèo Hải Phòng', 'do-an-vat', 'Đặc sản Hải Phòng', 'Bánh bèo được bọc trong lá chuối, mềm dẻo, nhân đẫm thịt và có nước chấm độc đáo làm từ nước xương ninh nhừ.', 'Bánh bèo Lạch Tray - Số 294 Lạch Tray, Ngô Quyền', 30000, 'anh html/images/mon-ngon-hai-phong-6.jpg', ''),
('Cà phê cốt dừa', 'do-uong', 'Thức uống mới lạ', 'Cà phê cốt dừa được đánh đều quyện cùng cốt dừa đặc sệt, thêm trân châu, thạch đen, dừa khô và dừa tươi nạo sợi.', 'Quán cô Hạnh - Số 130 Lam Sơn, Lê Chân', 15000, 'anh html/images/cach-pha-ca-phe-cot-dua.jpg', 'Cỡ nhỏ:15000|Cỡ to:60000'),
('Cháo cay Hải Phòng', 'mon-nuoc', 'Cháo cay vừa miệng', 'Cháo cay Hải Phòng sánh mịn, nóng hổi, kết hợp cùng các loại topping thơm ngon và vị cay nồng đặc trưng.', 'Quán cháo Trần Bình Trọng: Số 17 Trần Bình Trọng, Ngô Quyền', 15000, 'anh html/images/mon-ngon-hai-phong-14.jpg', 'Cháo truyền thống:5000|Quẩy + ruốc:15000|Mộc nhĩ + thịt:25000|Tiết + lòng:30000'),
('Nem chua An Thọ', 'do-an-vat', 'Đặc sản Hải Phòng', 'Nem chua An Thọ làm từ thịt và bì heo tươi ngon, lên men tự nhiên giòn sừn sựt, ăn kèm sung muối, dưa chuột muối.', 'Cửa hàng Thanh Thủy - Số 311 Phủ Thượng Đoạn, Hải An', 30000, 'anh html/images/mon-ngon-hai-phong-16.jpg', '');
