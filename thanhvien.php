<?php
require_once 'includes/auth.php';
$page_title = 'Ưu đãi thành viên - Doof Food';

ob_start();
?>
<main>
    <section class="member-banner">
        <h1>HẠNG THÀNH VIÊN DOOF FOOD</h1>
        <p>Đồng hành cùng Doof Food và tận hưởng những đặc quyền dành riêng cho từng hạng thành viên.</p>
    </section>

    <section class="member-section">
        <h2>CÁC HẠNG THÀNH VIÊN</h2>
        <div class="member-list">
            <article class="member-card silver">
                <div class="member-icon">🥈</div>
                <h3>THÀNH VIÊN BẠC</h3>
                <p class="member-desc">Hạng thành viên cơ bản</p>
                <div class="member-benefits">
                    <p>✓ Giảm giá 5% cho đơn hàng</p>
                    <p>✓ Tích lũy điểm thưởng</p>
                    <p>✓ Nhận thông tin khuyến mãi</p>
                </div>
                <button class="member-register" type="button" onclick="window.location.href='dangky.php?rank=Bạc'">Đăng ký ngay</button>
            </article>

            <article class="member-card gold">
                <div class="member-icon">🥇</div>
                <h3>THÀNH VIÊN VÀNG</h3>
                <p class="member-desc">Hạng thành viên cao cấp</p>
                <div class="member-benefits">
                    <p>✓ Giảm giá 10% cho đơn hàng</p>
                    <p>✓ Tích lũy điểm thưởng</p>
                    <p>✓ Nhận voucher sinh nhật</p>
                    <p>✓ Ưu tiên nhận khuyến mãi</p>
                </div>
                <button class="member-register" type="button" onclick="window.location.href='dangky.php?rank=Vàng'">Đăng ký ngay</button>
            </article>

            <article class="member-card diamond">
                <div class="member-icon">💎</div>
                <h3>THÀNH VIÊN KIM CƯƠNG</h3>
                <p class="member-desc">Hạng thành viên đặc biệt</p>
                <div class="member-benefits">
                    <p>✓ Giảm giá 15% cho đơn hàng</p>
                    <p>✓ Tích lũy điểm thưởng</p>
                    <p>✓ Nhận voucher đặc biệt</p>
                    <p>✓ Ưu tiên hỗ trợ khách hàng</p>
                    <p>✓ Nhận ưu đãi độc quyền</p>
                </div>
                <button class="member-register" type="button" onclick="window.location.href='dangky.php?rank=Kim Cương'">Đăng ký ngay</button>
            </article>
        </div>
    </section>
</main>
<?php
$content = ob_get_clean();
include 'layouts/main.php';
?>
