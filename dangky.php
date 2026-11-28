<?php
require_once 'includes/auth.php';

$page_title = 'Đăng ký thành viên - Doof Food';
$errors = array();
$default_rank = $_GET['rank'] ?? 'Bạc';
if (!in_array($default_rank, array('Bạc', 'Vàng', 'Kim Cương'))) {
    $default_rank = 'Bạc';
}

$data = array(
    'username' => '',
    'fullname' => '',
    'phone' => '',
    'member_rank' => $default_rank
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['username'] = trim($_POST['username'] ?? '');
    $data['fullname'] = trim($_POST['fullname'] ?? '');
    $data['phone'] = trim($_POST['phone'] ?? '');
    $data['member_rank'] = $_POST['member_rank'] ?? 'Bạc';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($data['username'])) {
        $errors['username'] = 'Chưa nhập tên đăng nhập';
    }
    if (empty($data['fullname'])) {
        $errors['fullname'] = 'Chưa nhập họ tên';
    }
    if (empty($password)) {
        $errors['password'] = 'Chưa nhập mật khẩu';
    }
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Mật khẩu nhập lại không khớp';
    }

    if (empty($errors)) {
        $ok = register_user($data['username'], $password, $data['fullname'], $data['phone'], $data['member_rank']);
        disconnect_db();
        if ($ok) {
            header('Location: login.php?registered=1');
            exit;
        } else {
            $errors['username'] = 'Tên đăng nhập đã tồn tại';
        }
    }
}

ob_start();
?>
<main>
    <section class="booking-section">
        <h2>Đăng ký tài khoản thành viên</h2>
        <form method="post" action="dangky.php">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($data['username']) ?>" placeholder="Nhập tên đăng nhập">
            <?php if (!empty($errors['username'])): ?>
                <span style="color:red"><?= htmlspecialchars($errors['username']) ?></span>
            <?php endif; ?>

            <label for="fullname">Họ và tên:</label>
            <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($data['fullname']) ?>" placeholder="Nhập họ tên">
            <?php if (!empty($errors['fullname'])): ?>
                <span style="color:red"><?= htmlspecialchars($errors['fullname']) ?></span>
            <?php endif; ?>

            <label for="phone">Số điện thoại:</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($data['phone']) ?>" placeholder="Nhập số điện thoại">

            <label for="member_rank">Hạng thành viên:</label>
            <select id="member_rank" name="member_rank">
                <option value="Bạc" <?= $data['member_rank'] === 'Bạc' ? 'selected' : '' ?>>Thành viên Bạc (Giảm 5%)</option>
                <option value="Vàng" <?= $data['member_rank'] === 'Vàng' ? 'selected' : '' ?>>Thành viên Vàng (Giảm 10%)</option>
                <option value="Kim Cương" <?= $data['member_rank'] === 'Kim Cương' ? 'selected' : '' ?>>Thành viên Kim Cương (Giảm 15%)</option>
            </select>

            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu">
            <?php if (!empty($errors['password'])): ?>
                <span style="color:red"><?= htmlspecialchars($errors['password']) ?></span>
            <?php endif; ?>

            <label for="confirm_password">Nhập lại mật khẩu:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu">
            <?php if (!empty($errors['confirm_password'])): ?>
                <span style="color:red"><?= htmlspecialchars($errors['confirm_password']) ?></span>
            <?php endif; ?>

            <button type="submit">Đăng ký</button>
        </form>
        <p style="margin-top:14px;"><a href="login.php">Đã có tài khoản? Đăng nhập</a></p>
    </section>
</main>
<?php
$content = ob_get_clean();
include 'layouts/main.php';
?>
