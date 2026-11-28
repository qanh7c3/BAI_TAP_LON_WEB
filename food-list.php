<?php
require_once 'includes/auth.php';
require_once 'includes/foods.php';

redirect_if_not_logged_in();

$page_title = 'Quản lý món ăn - Doof Food';
$foods = get_all_foods();
disconnect_db();

ob_start();
?>
<main>
    <h2>Danh sách món ăn</h2>
    <a href="food-add.php">Thêm món ăn mới</a>

    <form method="GET" action="" style="margin: 12px 0;">
        <input type="text" name="q" placeholder="Tìm theo ID hoặc tên món..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button type="submit">Tìm</button>
    </form>

    <table border="1" cellpadding="8" style="width:100%; border-collapse: collapse; background:#fff;">
        <tr>
            <th>ID</th>
            <th>Tên món</th>
            <th>Danh mục</th>
            <th>Giá cơ bản</th>
            <th>Địa chỉ quán</th>
            <th>Thao tác</th>
        </tr>
        <?php foreach ($foods as $item):
            if (!empty($_GET['q'])) {
                $q = strtolower($_GET['q']);
                if (strpos(strtolower($item['food_name']), $q) === false && $item['food_id'] != ($_GET['q'] ?? '')) continue;
            }
        ?>
            <tr>
                <td><?= $item['food_id'] ?></td>
                <td><?= htmlspecialchars($item['food_name']) ?></td>
                <td><?= htmlspecialchars($item['food_category']) ?></td>
                <td><?= number_format($item['food_price'], 0, ',', '.') ?>đ</td>
                <td><?= htmlspecialchars($item['food_address']) ?></td>
                <td>
                    <form method="post" action="food-delete.php">
                        <input onclick="window.location = 'food-edit.php?id=<?= $item['food_id'] ?>'" type="button" value="Sửa"/>
                        <input type="hidden" name="id" value="<?= $item['food_id'] ?>"/>
                        <input onclick="return confirm('Bạn có chắc muốn xóa không?');" type="submit" name="delete" value="Xóa"/>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
<?php
$content = ob_get_clean();
include 'layouts/main.php';
?>
