<?php
require_once 'includes/auth.php';
require_once 'includes/orders.php';

$page_title = 'Website đặt đồ ăn - Doof Food';
$order_code = trim($_GET['order_code'] ?? '');
$found_order = array();
$order_items = array();
$order_error = '';

if ($order_code !== '') {
    $found_order = get_order_by_code($order_code);
    if (!empty($found_order)) {
        $order_items = get_order_items($found_order['order_id']);
    } else {
        $order_error = 'Không tìm thấy đơn hàng với mã: ' . $order_code;
    }
    disconnect_db();
}

ob_start();
?>
<section class="hero">
    <div class="hero-image">
        <img src="anh html/images/Gemini_Generated_Image_1afz5p1afz5p1afz.png" alt="Các món ăn hấp dẫn và đặc sản Hải Phòng">
    </div>
    <div class="hero-content">
        <h1>Ngon mỗi ngày, vui mỗi bữa</h1>
        <p>
            Khám phá nét đặc sắc của ẩm thực Hải Phòng với những món ăn hấp dẫn và hương vị khó quên.
            Cùng Doof Food tìm hiểu và lựa chọn món ăn yêu thích!
        </p>
        <a href="thucdon.php" class="btn-order">Khám phá thực đơn</a>
    </div>
</section>

<section class="about-us">
    <h2>Về chúng tôi</h2>
    <p>
        Doof Food là website giới thiệu và đặt món ăn, mang đến cho khách hàng cơ hội khám phá những
        món ăn đặc trưng và đặc sản nổi tiếng của Hải Phòng.
    </p>
    <p>
        Chúng tôi mong muốn giúp khách hàng dễ dàng tìm hiểu các món ăn, lựa chọn món yêu thích và
        trải nghiệm nét đặc sắc trong văn hóa ẩm thực địa phương.
    </p>
</section>
<hr>

<section class="order-service">
    <h2>Tiện ích đơn hàng & Dịch vụ nâng cao</h2>

    <div class="order-tracking">
        <h3>Theo dõi đơn hàng</h3>
        <p>Kiểm tra trạng thái đơn hàng và tình trạng giao hàng bằng cách nhập mã đơn hàng của bạn.</p>
        <form method="get" action="index.php">
            <label for="order-code">Mã đơn hàng</label>
            <input type="text" id="order-code" name="order_code" placeholder="Nhập mã đơn hàng" value="<?= htmlspecialchars($order_code) ?>">
            <button type="submit">Tra cứu đơn hàng</button>
        </form>

        <?php if ($order_error): ?>
            <p style="color:red; margin-top:10px;"><?= htmlspecialchars($order_error) ?></p>
        <?php endif; ?>

        <?php if (!empty($found_order)): ?>
            <div style="margin-top:15px; padding:12px; background:#fff; border:1px solid #ddd; border-radius:8px;">
                <p><strong>Mã đơn:</strong> <?= htmlspecialchars($found_order['order_code']) ?></p>
                <p><strong>Người nhận:</strong> <?= htmlspecialchars($found_order['customer_name']) ?> - <?= htmlspecialchars($found_order['customer_phone']) ?></p>
                <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($found_order['customer_address']) ?></p>
                <p><strong>Thanh toán:</strong> <?= htmlspecialchars($found_order['payment_method']) ?></p>
                <p><strong>Trạng thái:</strong> <span style="color:green; font-weight:bold;"><?= htmlspecialchars($found_order['order_status']) ?></span></p>
                <p><strong>Tổng tiền:</strong> <?= number_format($found_order['total_amount'], 0, ',', '.') ?>đ</p>
                <?php if (!empty($order_items)): ?>
                    <ul>
                        <?php foreach ($order_items as $item): ?>
                            <li>
                                <?= htmlspecialchars($item['food_name']) ?>
                                (<?= htmlspecialchars($item['food_option']) ?>)
                                x <?= $item['quantity'] ?>
                                - <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="catering-service">
        <h3>Dịch vụ đặt tiệc & Suất ăn văn phòng</h3>
        <img width="700px" src="anh html/images/Vietnamese Office Catering Feast.png" alt="Dịch vụ đặt tiệc và suất ăn văn phòng">
        <p>
            Cung cấp dịch vụ đặt món số lượng lớn cho tiệc gia đình, sinh nhật, sự kiện và các bữa ăn văn phòng.
            Khách hàng có thể yêu cầu báo giá theo số lượng suất ăn và nhu cầu phục vụ.
        </p>
        <a href="dattiec.php" class="btn-catering">Yêu cầu đặt tiệc</a>
    </div>
</section>
<?php
$content = ob_get_clean();
include 'layouts/main.php';
?>
