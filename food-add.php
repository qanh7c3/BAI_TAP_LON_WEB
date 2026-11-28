<?php
require_once 'includes/auth.php';
require_once 'includes/foods.php';

redirect_if_not_logged_in();

$page_title = 'Thêm món ăn - Doof Food';
$errors = [];
$data = [
    'food_name' => '',
    'food_category' => '',
    'food_tag' => '',
    'food_desc' => '',
    'food_address' => '',
    'food_price' => '',
    'food_image' => '',
    'food_options' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'food_name' => trim($_POST['food_name'] ?? ''),
        'food_category' => $_POST['food_category'] ?? '',
        'food_tag' => trim($_POST['food_tag'] ?? ''),
        'food_desc' => trim($_POST['food_desc'] ?? ''),
        'food_address' => trim($_POST['food_address'] ?? ''),
        'food_price' => trim($_POST['food_price'] ?? ''),
        'food_image' => trim($_POST['food_image'] ?? ''),
        'food_options' => trim($_POST['food_options'] ?? '')
    ];

    if (empty($data['food_name'])) {
        $errors['food_name'] = 'Chưa nhập tên món ăn';
    }
    if (empty($data['food_category'])) {
        $errors['food_category'] = 'Chưa chọn danh mục';
    }
    if (empty($data['food_price']) || !is_numeric($data['food_price'])) {
        $errors['food_price'] = 'Chưa nhập giá hợp lệ';
    }

    if (empty($errors)) {
        add_food(
            $data['food_name'],
            $data['food_category'],
            $data['food_tag'],
            $data['food_desc'],
            $data['food_address'],
            $data['food_price'],
            $data['food_image'],
            $data['food_options']
        );
        disconnect_db();
        header('Location: food-list.php');
        exit;
    }
}

ob_start();
?>
<main>
    <section class="booking-section">
        <h2>Thêm món ăn</h2>
        <p><a href="food-list.php">Trở về danh sách</a></p>
        <form method="post">
            <label>Tên món ăn:</label>
            <input type="text" name="food_name" value="<?= htmlspecialchars($data['food_name']) ?>">
            <?php if (!empty($errors['food_name'])): ?>
                <span style="color:red"><?= htmlspecialchars($errors['food_name']) ?></span>
            <?php endif; ?>

            <label>Danh mục:</label>
            <select name="food_category">
                <option value="">-- Chọn danh mục --</option>
                <option value="mon-nuoc" <?= $data['food_category'] === 'mon-nuoc' ? 'selected' : '' ?>>Món nước</option>
                <option value="mon-chien" <?= $data['food_category'] === 'mon-chien' ? 'selected' : '' ?>>Món chiên</option>
                <option value="do-uong" <?= $data['food_category'] === 'do-uong' ? 'selected' : '' ?>>Đồ uống</option>
                <option value="do-an-vat" <?= $data['food_category'] === 'do-an-vat' ? 'selected' : '' ?>>Đồ ăn vặt</option>
            </select>
            <?php if (!empty($errors['food_category'])): ?>
                <span style="color:red"><?= htmlspecialchars($errors['food_category']) ?></span>
            <?php endif; ?>

            <label>Tag nổi bật:</label>
            <input type="text" name="food_tag" value="<?= htmlspecialchars($data['food_tag']) ?>" placeholder="VD: Đặc sản Hải Phòng">

            <label>Giá cơ bản (VNĐ):</label>
            <input type="number" name="food_price" value="<?= htmlspecialchars($data['food_price']) ?>">
            <?php if (!empty($errors['food_price'])): ?>
                <span style="color:red"><?= htmlspecialchars($errors['food_price']) ?></span>
            <?php endif; ?>

            <label>Địa chỉ quán:</label>
            <input type="text" name="food_address" value="<?= htmlspecialchars($data['food_address']) ?>">

            <label>Đường dẫn ảnh:</label>
            <input type="text" name="food_image" value="<?= htmlspecialchars($data['food_image']) ?>" placeholder="anh html/images/ten-anh.jpg">

            <label>Tùy chọn phần/size (VD: Phần thường:35000|Phần đặc biệt:65000):</label>
            <input type="text" name="food_options" value="<?= htmlspecialchars($data['food_options']) ?>">

            <label>Mô tả món:</label>
            <textarea name="food_desc" rows="3"><?= htmlspecialchars($data['food_desc']) ?></textarea>

            <button type="submit">Lưu món ăn</button>
        </form>
    </section>
</main>
<?php
$content = ob_get_clean();
include 'layouts/main.php';
?>
