<?php
require_once 'includes/auth.php';
require_once 'includes/orders.php';

$page_title = 'Giỏ hàng - Doof Food';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['remove_key'])) {
    $rk = $_POST['remove_key'];
    unset($_SESSION['cart'][$rk]);
    header('Location: giohang.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['update_cart'])) {
    if (!empty($_POST['qty']) && is_array($_POST['qty'])) {
        foreach ($_POST['qty'] as $k => $v) {
            $q = (int)$v;
            if ($q <= 0) {
                unset($_SESSION['cart'][$k]);
            } elseif (isset($_SESSION['cart'][$k])) {
                $_SESSION['cart'][$k]['quantity'] = $q;
            }
        }
    }
    header('Location: giohang.php');
    exit;
}

$voucher_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_voucher'])) {
    $_SESSION['voucher'] = strtoupper(trim($_POST['voucher_code'] ?? ''));
}

$voucher_code = isset($_SESSION['voucher']) ? $_SESSION['voucher'] : '';
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$member_discount = 0;
if (isset($_SESSION['user'])) {
    $rank = $_SESSION['user']['member_rank'];
    if ($rank === 'Bạc') {
        $member_discount = (int)($subtotal * 0.05);
    } elseif ($rank === 'Vàng') {
        $member_discount = (int)($subtotal * 0.10);
    } elseif ($rank === 'Kim Cương') {
        $member_discount = (int)($subtotal * 0.15);
    }
}

$voucher_discount = 0;
if ($voucher_code !== '' && $subtotal > 0) {
    if ($voucher_code === 'DOOF10' && $subtotal >= 100000) {
        $voucher_discount = (int)($subtotal * 0.10);
        $voucher_msg = 'Áp dụng mã DOOF10 giảm 10% thành công!';
    } elseif ($voucher_code === 'DOOF20' && $subtotal >= 150000) {
        $voucher_discount = 20000;
        $voucher_msg = 'Áp dụng mã DOOF20 giảm 20.000đ thành công!';
    } elseif ($voucher_code === 'DOOF30' && $subtotal >= 250000) {
        $voucher_discount = (int)($subtotal * 0.30);
        $voucher_msg = 'Áp dụng mã DOOF30 giảm 30% thành công!';
    } else {
        $voucher_msg = 'Mã không hợp lệ hoặc đơn hàng chưa đủ điều kiện.';
    }
}

$total_discount = $member_discount + $voucher_discount;
$total = $subtotal - $total_discount;
if ($total < 0) {
    $total = 0;
}

$errors = array();
$open_popup = false;
$order_success = '';
$data = array(
    'hoten' => isset($_SESSION['user']) ? $_SESSION['user']['fullname'] : '',
    'sdt' => isset($_SESSION['user']) ? $_SESSION['user']['phone'] : '',
    'diachi' => '',
    'ghichu' => '',
    'thanhtoan' => 'COD'
);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['place_order'])) {
    $data['hoten'] = trim($_POST['hoten'] ?? '');
    $data['sdt'] = trim($_POST['sdt'] ?? '');
    $data['diachi'] = trim($_POST['diachi'] ?? '');
    $data['ghichu'] = trim($_POST['ghichu'] ?? '');
    $data['thanhtoan'] = $_POST['thanhtoan'] ?? 'COD';

    if (empty($data['hoten'])) {
        $errors['hoten'] = 'Chưa nhập họ tên người nhận';
    }
    if (empty($data['sdt'])) {
        $errors['sdt'] = 'Chưa nhập số điện thoại';
    }
    if (empty($data['diachi'])) {
        $errors['diachi'] = 'Chưa nhập địa chỉ giao hàng';
    }
    if (empty($_SESSION['cart'])) {
        $errors['cart'] = 'Giỏ hàng đang trống';
    }

    if (empty($errors)) {
        $uid = isset($_SESSION['user']) ? $_SESSION['user']['user_id'] : null;
        $code = create_order(
            $uid,
            $data['hoten'],
            $data['sdt'],
            $data['diachi'],
            $data['ghichu'],
            $data['thanhtoan'],
            $voucher_code,
            $total_discount,
            $total,
            $_SESSION['cart']
        );
        disconnect_db();
        if ($code) {
            $_SESSION['cart'] = array();
            unset($_SESSION['voucher']);
            $order_success = $code;
            $subtotal = 0;
            $member_discount = 0;
            $voucher_discount = 0;
            $total_discount = 0;
            $total = 0;
        } else {
            $errors['cart'] = 'Đặt hàng thất bại, vui lòng thử lại';
            $open_popup = true;
        }
    } else {
        $open_popup = true;
    }
}

ob_start();
?>
<main>
    <section class="cart-banner">
        <h1>GIỎ HÀNG CỦA BẠN</h1>
        <p>Kiểm tra các món ăn bạn đã lựa chọn trước khi thanh toán.</p>
    </section>

    <?php if ($order_success): ?>
        <div style="background:#e8f8ec; border:1px solid #4caf50; padding:16px; border-radius:8px; margin-bottom:20px;">
            <h3 style="color:green; margin-bottom:6px;">Đặt hàng thành công!</h3>
            <p>Mã đơn hàng của bạn là: <strong><?= htmlspecialchars($order_success) ?></strong></p>
            <p><a href="index.php?order_code=<?= urlencode($order_success) ?>">Tra cứu đơn hàng ngay</a> | <a href="thucdon.php">Tiếp tục đặt món</a></p>
        </div>
    <?php endif; ?>

    <section class="cart-section">
        <div class="cart-container">
            <div class="cart-products">
                <h2>Sản phẩm đã chọn</h2>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <form method="post" action="giohang.php">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Món ăn</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                    <th>Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['cart'] as $key => $item): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($item['food_name']) ?></strong><br>
                                            <small><?= htmlspecialchars($item['food_option']) ?></small>
                                        </td>
                                        <td><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                                        <td>
                                            <input type="number" name="qty[<?= htmlspecialchars($key) ?>]" value="<?= $item['quantity'] ?>" min="1" style="width:65px;">
                                        </td>
                                        <td><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</td>
                                        <td>
                                            <button type="submit" name="remove_key" value="<?= htmlspecialchars($key) ?>" onclick="return confirm('Bạn có chắc muốn xóa không?');">Xóa</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div style="margin-top:12px;">
                            <button type="submit" name="update_cart" value="1">Cập nhật số lượng</button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="empty-cart">
                        <p>Giỏ hàng của bạn đang trống!</p>
                        <a href="thucdon.php" class="continue-shopping">Tiếp tục mua hàng</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="cart-summary">
                <h2>Tổng đơn hàng</h2>
                <form method="post" action="giohang.php" style="margin-bottom:12px;">
                    <label>Mã khuyến mãi:</label>
                    <input type="text" name="voucher_code" value="<?= htmlspecialchars($voucher_code) ?>" placeholder="VD: DOOF10" style="width:100%; margin:4px 0;">
                    <button type="submit" name="apply_voucher" value="1">Áp dụng mã</button>
                    <?php if ($voucher_msg): ?>
                        <p style="font-size:13px; color:#555;"><?= htmlspecialchars($voucher_msg) ?></p>
                    <?php endif; ?>
                </form>

                <div class="summary-row">
                    <span>Tổng tiền hàng:</span>
                    <span><?= number_format($subtotal, 0, ',', '.') ?>đ</span>
                </div>
                <div class="summary-row">
                    <span>Giảm giá thành viên:</span>
                    <span>-<?= number_format($member_discount, 0, ',', '.') ?>đ</span>
                </div>
                <div class="summary-row">
                    <span>Giảm giá voucher:</span>
                    <span>-<?= number_format($voucher_discount, 0, ',', '.') ?>đ</span>
                </div>
                <div class="summary-row total">
                    <strong>Tổng thanh toán:</strong>
                    <strong><?= number_format($total, 0, ',', '.') ?>đ</strong>
                </div>

                <?php if (!empty($_SESSION['cart'])): ?>
                    <button type="button" class="checkout-button" onclick="document.getElementById('popup-dathang').classList.add('active')">Xác nhận đặt hàng</button>
                <?php endif; ?>
                <a href="thucdon.php" class="continue-link">Tiếp tục chọn món</a>
            </div>
        </div>
    </section>
</main>

<div class="popup-overlay <?= $open_popup ? 'active' : '' ?>" id="popup-dathang">
    <div class="popup-container">
        <div class="popup-header">
            <div>
                <h2>Thông tin giao hàng & đặt món</h2>
                <p>Vui lòng cung cấp thông tin để hoàn tất đơn hàng</p>
            </div>
            <button type="button" class="popup-close" onclick="document.getElementById('popup-dathang').classList.remove('active')">×</button>
        </div>

        <form method="post" action="giohang.php" class="popup-content">
            <div class="form-group">
                <label for="hoten">Họ và tên người nhận<span>*</span></label>
                <input type="text" id="hoten" name="hoten" value="<?= htmlspecialchars($data['hoten']) ?>" placeholder="Nhập họ tên">
                <?php if (!empty($errors['hoten'])): ?>
                    <span style="color:red"><?= htmlspecialchars($errors['hoten']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="sdt">Số điện thoại liên hệ<span>*</span></label>
                <input type="tel" id="sdt" name="sdt" value="<?= htmlspecialchars($data['sdt']) ?>" placeholder="Nhập số điện thoại">
                <?php if (!empty($errors['sdt'])): ?>
                    <span style="color:red"><?= htmlspecialchars($errors['sdt']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="diachi">Địa chỉ giao hàng chi tiết<span>*</span></label>
                <input type="text" id="diachi" name="diachi" value="<?= htmlspecialchars($data['diachi']) ?>" placeholder="Nhập địa chỉ">
                <?php if (!empty($errors['diachi'])): ?>
                    <span style="color:red"><?= htmlspecialchars($errors['diachi']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="ghichu">Ghi chú cho tài xế (tùy chọn)</label>
                <input type="text" id="ghichu" name="ghichu" value="<?= htmlspecialchars($data['ghichu']) ?>" placeholder="Nhập ghi chú nếu có">
            </div>

            <div class="form-group">
                <label>Phương thức thanh toán</label>
                <div class="payment-method">
                    <label class="payment-option">
                        <input type="radio" name="thanhtoan" value="COD" <?= $data['thanhtoan'] === 'COD' ? 'checked' : '' ?>>
                        <div>
                            <strong>Tiền mặt khi nhận</strong>
                            <p>COD - Thanh toán khi nhận hàng</p>
                        </div>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="thanhtoan" value="QR" <?= $data['thanhtoan'] === 'QR' ? 'checked' : '' ?>>
                        <div>
                            <strong>Chuyển khoản QR</strong>
                            <p>Thanh toán qua mã ngân hàng</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="order-summary">
                <div class="summary-heading">
                    <strong>Tóm tắt đơn hàng</strong>
                </div>
                <div class="summary-item">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <p><?= htmlspecialchars($item['food_name']) ?> (<?= htmlspecialchars($item['food_option']) ?>) x <?= $item['quantity'] ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="summary-total">
                    <strong>Tổng thanh toán:</strong>
                    <strong><?= number_format($total, 0, ',', '.') ?>đ</strong>
                </div>
            </div>

            <?php if (!empty($errors['cart'])): ?>
                <p style="color:red"><?= htmlspecialchars($errors['cart']) ?></p>
            <?php endif; ?>

            <div class="popup-actions">
                <button type="button" class="btn-back" onclick="document.getElementById('popup-dathang').classList.remove('active')">Quay lại</button>
                <button type="submit" name="place_order" value="1" class="btn-confirm">Xác nhận đặt hàng</button>
            </div>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
include 'layouts/main.php';
?>
