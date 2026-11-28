<?php
require_once 'includes/auth.php';
require_once 'includes/foods.php';

$page_title = 'Thực đơn - Doof Food';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['add_to_cart'])) {
    $food_id = isset($_POST['food_id']) ? (int)$_POST['food_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    if ($quantity < 1) {
        $quantity = 1;
    }

    $food = get_food($food_id);
    if (!empty($food)) {
        $opt_label = 'Mặc định';
        $price = (int)$food['food_price'];

        if (!empty($_POST['food_option'])) {
            $parts = explode(':', $_POST['food_option']);
            if (count($parts) === 2) {
                $opt_label = $parts[0];
                $price = (int)$parts[1];
            }
        }

        if (!empty($_POST['spice_level'])) {
            $opt_label .= ' - ' . $_POST['spice_level'];
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        $key = $food_id . '_' . md5($opt_label);
        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$key] = array(
                'food_id' => $food['food_id'],
                'food_name' => $food['food_name'],
                'food_option' => $opt_label,
                'price' => $price,
                'quantity' => $quantity
            );
        }
    }

    $redirect_url = 'thucdon.php?added=1';
    if (!empty($_POST['current_cat'])) {
        $redirect_url .= '&cat=' . urlencode($_POST['current_cat']);
    }
    disconnect_db();
    header('Location: ' . $redirect_url);
    exit;
}

$foods = get_all_foods();
disconnect_db();

$cat = isset($_GET['cat']) ? $_GET['cat'] : 'all';
$q = trim($_GET['q'] ?? '');

ob_start();
?>
<section id="menu" class="food-menu">
    <h2>Thực đơn của chúng tôi</h2>
    <p>Khám phá những món ăn tươi ngon của Hải Phòng</p>

    <?php if (!empty($_GET['added'])): ?>
        <p style="color:green; font-weight:bold;">Đã thêm món vào giỏ hàng! <a href="giohang.php">Xem giỏ hàng</a></p>
    <?php endif; ?>

    <form method="get" action="thucdon.php" style="margin: 10px 0 14px;">
        <input type="hidden" name="cat" value="<?= htmlspecialchars($cat) ?>">
        <input type="text" name="q" placeholder="Tìm món ăn..." value="<?= htmlspecialchars($q) ?>">
        <button type="submit">Tìm kiếm</button>
    </form>

    <div>
        <a href="thucdon.php?cat=all" class="category-btn <?= $cat === 'all' ? 'active' : '' ?>">Tất cả</a>
        <a href="thucdon.php?cat=mon-nuoc" class="category-btn <?= $cat === 'mon-nuoc' ? 'active' : '' ?>">Món nước</a>
        <a href="thucdon.php?cat=mon-chien" class="category-btn <?= $cat === 'mon-chien' ? 'active' : '' ?>">Món chiên</a>
        <a href="thucdon.php?cat=do-uong" class="category-btn <?= $cat === 'do-uong' ? 'active' : '' ?>">Đồ uống</a>
        <a href="thucdon.php?cat=do-an-vat" class="category-btn <?= $cat === 'do-an-vat' ? 'active' : '' ?>">Đồ ăn vặt</a>
    </div>

    <div class="food-list">
        <?php foreach ($foods as $food):
            if ($cat !== 'all' && $food['food_category'] !== $cat) continue;
            if ($q !== '') {
                if (strpos(strtolower($food['food_name']), strtolower($q)) === false) continue;
            }
            $options = array();
            if (!empty($food['food_options'])) {
                $options = explode('|', $food['food_options']);
            }
        ?>
            <article class="food-card">
                <form method="post" action="thucdon.php" style="display:flex; flex-direction:column; height:100%;">
                    <input type="hidden" name="food_id" value="<?= $food['food_id'] ?>">
                    <input type="hidden" name="current_cat" value="<?= htmlspecialchars($cat) ?>">
                    <div class="food-image">
                        <img width="400px" src="<?= htmlspecialchars($food['food_image']) ?>" alt="<?= htmlspecialchars($food['food_name']) ?>">
                    </div>
                    <div class="food-info">
                        <?php if (!empty($food['food_tag'])): ?>
                            <span class="food-tag"><?= htmlspecialchars($food['food_tag']) ?></span>
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($food['food_name']) ?></h3>
                        <p class="food-description"><?= htmlspecialchars($food['food_desc']) ?></p>
                        <p class="food-address">
                            <strong>Địa chỉ: </strong>
                            <span><?= htmlspecialchars($food['food_address']) ?></span>
                        </p>

                        <?php if (!empty($options)): ?>
                            <div class="food-option">
                                <label>Chọn phần:</label>
                                <select name="food_option">
                                    <?php foreach ($options as $opt):
                                        $p = explode(':', $opt);
                                        if (count($p) === 2):
                                    ?>
                                        <option value="<?= htmlspecialchars($opt) ?>">
                                            <?= htmlspecialchars($p[0]) ?> - <?= number_format((int)$p[1], 0, ',', '.') ?>đ
                                        </option>
                                    <?php endif; endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <?php if ($food['food_category'] === 'mon-nuoc'): ?>
                            <div class="food-option">
                                <label>Độ cay:</label>
                                <select name="spice_level">
                                    <option value="Không cay">Không cay</option>
                                    <option value="Ít cay">Ít cay</option>
                                    <option value="Cay vừa">Cay vừa</option>
                                    <option value="Nhiều cay">Nhiều cay</option>
                                </select>
                            </div>
                        <?php endif; ?>

                        <div class="food-option">
                            <label>Số lượng:</label>
                            <input type="number" name="quantity" min="1" value="1">
                        </div>
                    </div>
                    <div class="food-bottom">
                        <p class="food-price"><?= number_format($food['food_price'], 0, ',', '.') ?>đ</p>
                        <button type="submit" name="add_to_cart" value="1" class="btn-add">+ Thêm vào giỏ hàng</button>
                    </div>
                </form>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php
$content = ob_get_clean();
include 'layouts/main.php';
?>
