<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_user = isset($_SESSION['user']) ? $_SESSION['user']['fullname'] : '';
$current_rank = isset($_SESSION['user']) ? $_SESSION['user']['member_rank'] : '';
$page_title = isset($page_title) ? $page_title : 'Doof Food - Website đặt đồ ăn';
$cart_count = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $c) {
        $cart_count += $c['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php">
                <img width="300px" src="anh html/images/1793013929461_8924446136831943708_g6147288263816683401_651cab4cb875ba927a2cfc3bd7e672c3.jpg" alt="Doof Food">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="thucdon.php">Thực đơn</a></li>
                <li><a href="khuyenmai.php">Khuyến mãi</a></li>
                <li><a href="cuahang.php">Hệ thống cửa hàng</a></li>
                <li><a href="thanhvien.php">Ưu đãi thành viên</a></li>
                <li><a href="dattiec.php">Đặt tiệc</a></li>
                <li>
                    <a href="giohang.php">
                        <i class="fa-solid fa-cart-shopping"></i>
                        Giỏ hàng
                        <span id="cart-count"><?= $cart_count ?></span>
                    </a>
                </li>
                <?php if ($current_user): ?>
                    <li><a href="food-list.php">Quản lý món</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="user-action" style="margin-top:8px;">
            <?php if ($current_user): ?>
                <span>Xin chào: <strong><?= htmlspecialchars($current_user) ?></strong> (<?= htmlspecialchars($current_rank) ?>)</span> |
                <a href="logout.php">Đăng xuất</a>
            <?php else: ?>
                <a href="login.php">Đăng nhập</a> |
                <a href="dangky.php">Đăng ký</a>
            <?php endif; ?>
        </div>
    </header>

    <?php echo $content; ?>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Thông tin liên hệ</h3>
                <p>Hotline: 0123 456 789</p>
                <p>Email: dooffood@gmail.com</p>
                <p>Địa chỉ: Hải Phòng, Việt Nam</p>
            </div>
            <div class="footer-section">
                <h3>Về chúng tôi</h3>
                <p>Mang đến những món ăn đặc trưng của Hải Phòng, giúp khách hàng dễ dàng khám phá ẩm thực địa phương.</p>
                <a href="cuahang.php">Hệ thống cửa hàng</a>
            </div>
            <div class="footer-section">
                <h3>Hỗ trợ khách hàng</h3>
                <a href="thucdon.php">Thực đơn</a> |
                <a href="khuyenmai.php">Khuyến mãi</a> |
                <a href="dattiec.php">Đặt tiệc</a> |
                <a href="thanhvien.php">Thành viên</a>
            </div>
            <div class="footer-section">
                <h3>Kết nối với chúng tôi</h3>
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">TikTok</a>
            </div>
        </div>
        <div class="footer-bottom">
            <?php if ($current_user): ?>
                <p>Đăng nhập bởi: <strong><?= htmlspecialchars($current_user) ?></strong></p>
            <?php endif; ?>
            <p>&copy; 2026 Doof Food. Bản quyền thuộc về website.</p>
        </div>
    </footer>
</body>
</html>
