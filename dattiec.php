<?php
require_once 'includes/auth.php';
require_once 'includes/orders.php';

$page_title = 'Trang đặt tiệc - Doof Food';
$errors = array();
$message = '';
$data = array(
    'fullname' => isset($_SESSION['user']) ? $_SESSION['user']['fullname'] : '',
    'phone' => isset($_SESSION['user']) ? $_SESSION['user']['phone'] : '',
    'service' => '',
    'meal_quantity' => 1,
    'date' => '',
    'location' => '',
    'note' => ''
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['fullname'] = trim($_POST['fullname'] ?? '');
    $data['phone'] = trim($_POST['phone'] ?? '');
    $data['service'] = $_POST['service'] ?? '';
    $data['meal_quantity'] = (int)($_POST['meal-quanity'] ?? 1);
    $data['date'] = $_POST['date'] ?? '';
    $data['location'] = trim($_POST['location'] ?? '');
    $data['note'] = trim($_POST['note'] ?? '');

    if (empty($data['fullname'])) {
        $errors['fullname'] = 'Chưa nhập họ và tên';
    }
    if (empty($data['phone'])) {
        $errors['phone'] = 'Chưa nhập số điện thoại';
    }
    if (empty($data['service'])) {
        $errors['service'] = 'Chưa chọn loại dịch vụ';
    }
    if (empty($data['date'])) {
        $errors['date'] = 'Chưa chọn ngày phục vụ';
    }

    if (empty($errors)) {
        if (add_catering($data['fullname'], $data['phone'], $data['service'], $data['meal_quantity'], $data['date'], $data['location'], $data['note'])) {
            $message = 'Gửi yêu cầu đặt tiệc thành công! Chúng tôi sẽ sớm liên hệ với bạn.';
            $data = array('fullname' => '', 'phone' => '', 'service' => '', 'meal_quantity' => 1, 'date' => '', 'location' => '', 'note' => '');
        } else {
            $errors['db'] = 'Có lỗi xảy ra, vui lòng thử lại.';
        }
        disconnect_db();
    }
}

ob_start();
?>
<main>
    <section class="booking-section">
        <h2>Đặt tiệc & Suất ăn văn phòng</h2>
        <p>
            Đặt món đặc sản Hải Phòng cho tiệc gia đình, sinh nhật, sự kiện hoặc bữa ăn văn phòng.<br>
            <b>Vui lòng điền thông tin để gửi yêu cầu.</b>
        </p>

        <?php if ($message): ?>
            <p style="color:green; font-weight:bold;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <?php if (!empty($errors['db'])): ?>
            <p style="color:red"><?= htmlspecialchars($errors['db']) ?></p>
        <?php endif; ?>

        <form action="dattiec.php" method="post">
            <label for="fullname">Họ và tên:</label>
            <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($data['fullname']) ?>" placeholder="Nhập họ và tên">
            <?php if (!empty($errors['fullname'])): ?>
                <span style="color:red"><?= htmlspecialchars($errors['fullname']) ?></span>
            <?php endif; ?>

            <label for="phone">Số điện thoại:</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($data['phone']) ?>" placeholder="Nhập số điện thoại">
            <?php if (!empty($errors['phone'])): ?>
                <span style="color:red"><?= htmlspecialchars($errors['phone']) ?></span>
            <?php endif; ?>

            <label for="service">Chọn loại dịch vụ:</label>
            <select id="service" name="service">
                <option value="">-- Chọn dịch vụ --</option>
                <option value="sinh-nhat" <?= $data['service'] === 'sinh-nhat' ? 'selected' : '' ?>>Sinh nhật</option>
                <option value="su-kien" <?= $data['service'] === 'su-kien' ? 'selected' : '' ?>>Sự kiện, hội nghị</option>
                <option value="van-phong" <?= $data['service'] === 'van-phong' ? 'selected' : '' ?>>Suất ăn văn phòng</option>
            </select>
            <?php if (!empty($errors['service'])): ?>
                <span style="color:red"><?= htmlspecialchars($errors['service']) ?></span>
            <?php endif; ?>

            <label for="meal-quanity">Số lượng suất ăn:</label>
            <input type="number" id="meal-quanity" name="meal-quanity" min="1" value="<?= (int)$data['meal_quantity'] ?>" placeholder="Nhập suất ăn">

            <label for="date">Ngày cần phục vụ:</label>
            <input type="date" id="date" name="date" value="<?= htmlspecialchars($data['date']) ?>">
            <?php if (!empty($errors['date'])): ?>
                <span style="color:red"><?= htmlspecialchars($errors['date']) ?></span>
            <?php endif; ?>

            <label for="location">Địa điểm giao hàng:</label>
            <input type="text" id="location" name="location" value="<?= htmlspecialchars($data['location']) ?>" placeholder="Nhập địa chỉ giao hàng">

            <label for="note">Món ăn và yêu cầu thêm:</label>
            <textarea id="note" name="note" rows="4" placeholder="Nhập các món muốn đặt hoặc yêu cầu khác"><?= htmlspecialchars($data['note']) ?></textarea>

            <button type="submit">Gửi yêu cầu đặt tiệc</button>
        </form>
    </section>
</main>
<?php
$content = ob_get_clean();
include 'layouts/main.php';
?>
