<?php
require_once 'includes/auth.php';
$page_title = 'Hệ thống cửa hàng - Doof Food';

ob_start();
?>
<main>
    <section class="store-banner">
        <h1>HỆ THỐNG CỬA HÀNG DOOF FOOD</h1>
        <p>Mang hương vị ẩm thực Hải Phòng đến gần hơn với khách hàng.</p>
    </section>

    <section class="store-intro">
        <h2>Về hệ thống cửa hàng</h2>
        <p>
            Doof Food hướng đến việc mang những món ăn đặc trưng của Hải Phòng đến với khách hàng
            thông qua hệ thống cửa hàng và dịch vụ đặt món trực tuyến.
        </p>
    </section>

    <section class="store-section">
        <h2>Thông tin cửa hàng</h2>
        <div class="store-list">
            <article class="store-card">
                <h3>Doof Food - Cửa hàng 1</h3>
                <p><strong>Địa chỉ:</strong> 48 Lạch Tray, Ngô Quyền, Hải Phòng</p>
                <p><strong>Giờ hoạt động:</strong> 08:00 - 22:00</p>
                <p><strong>Hotline:</strong> 0123 456 789</p>
            </article>

            <article class="store-card">
                <h3>Doof Food - Cửa hàng 2</h3>
                <p><strong>Địa chỉ:</strong> 27 Minh Khai, Hồng Bàng, Hải Phòng</p>
                <p><strong>Giờ hoạt động:</strong> 08:00 - 22:00</p>
                <p><strong>Hotline:</strong> 0123 456 789</p>
            </article>
        </div>
    </section>
    <hr>

    <section class="store-contact">
        <h2>Liên hệ với chúng tôi</h2>
        <p>Nếu cần hỗ trợ, khách hàng có thể liên hệ với Doof Food.</p>
        <p>Email: dooffood@gmail.com</p>
        <p>Hotline: 0123 456 789</p>
    </section>
</main>
<?php
$content = ob_get_clean();
include 'layouts/main.php';
?>
