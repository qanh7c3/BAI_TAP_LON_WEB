<?php
require_once 'includes/auth.php';
$page_title = 'Khuyến mãi - Doof Food';

ob_start();
?>
<main>
    <section class="promotion-banner">
        <h1>ƯU ĐÃI HẤP DẪN</h1>
        <p>Khám phá những chương trình khuyến mãi và voucher đặc biệt từ Doof Food!</p>
    </section>

    <section class="promotion-section">
        <h2>Voucher dành cho bạn</h2>
        <div class="voucher-list">
            <article class="voucher-card">
                <div class="voucher-content">
                    <h3>GIẢM 10%</h3>
                    <p>Cho đơn hàng từ 100.000đ</p>
                    <p class="voucher-code">Mã: DOOF10</p>
                    <p class="voucher-expiry">Hạn sử dụng: 31/12/2026</p>
                    <a href="thucdon.php" class="voucher-button">Sử dụng ngay</a>
                </div>
            </article>

            <article class="voucher-card">
                <div class="voucher-content">
                    <h3>GIẢM 20.000đ</h3>
                    <p>Cho đơn hàng từ 150.000đ</p>
                    <p class="voucher-code">Mã: DOOF20</p>
                    <p class="voucher-expiry">Hạn sử dụng: 31/12/2026</p>
                    <a href="thucdon.php" class="voucher-button">Sử dụng ngay</a>
                </div>
            </article>

            <article class="voucher-card">
                <div class="voucher-content">
                    <h3>GIẢM 30%</h3>
                    <p>Cho đơn hàng từ 250.000đ</p>
                    <p class="voucher-code">Mã: DOOF30</p>
                    <p class="voucher-expiry">Hạn sử dụng: 31/12/2026</p>
                    <a href="thucdon.php" class="voucher-button">Sử dụng ngay</a>
                </div>
            </article>
        </div>
    </section>
    <hr>

    <section class="promotion-section">
        <h2>Chương trình nổi bật</h2>
        <div class="promotion-list">
            <article class="promotion-card">
                <h3>Ưu đãi khách hàng mới</h3>
                <p>Khách hàng lần đầu trải nghiệm Doof Food có thể nhận ưu đãi cho đơn hàng đầu tiên.</p>
            </article>

            <article class="promotion-card">
                <h3>Ưu đãi cuối tuần</h3>
                <p>Khám phá các chương trình ưu đãi đặc biệt dành cho khách hàng vào dịp cuối tuần.</p>
            </article>

            <article class="promotion-card">
                <h3>Ưu đãi khi đặt nhóm</h3>
                <p>Nhận ưu đãi hấp dẫn khi đặt nhiều món ăn trong cùng một đơn hàng.</p>
            </article>
        </div>
    </section>
</main>
<?php
$content = ob_get_clean();
include 'layouts/main.php';
?>
