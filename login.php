<?php
require_once 'includes/auth.php';

$page_title = 'Đăng nhập - Doof Food';
$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu';
    } else {
        if (login($username, $password)) {
            disconnect_db();
            header('Location: index.php');
            exit;
        } else {
            $error = 'Sai tên đăng nhập hoặc mật khẩu';
        }
        disconnect_db();
    }
}

ob_start();
?>
<main>
    <section class="booking-section">
        <h2>Đăng nhập tài khoản</h2>
        <?php if (!empty($_GET['registered'])): ?>
            <p style="color:green">Đăng ký thành công! Vui lòng đăng nhập.</p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p style="color:red"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" placeholder="Nhập tên đăng nhập">

            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu">

            <button type="submit">Đăng nhập</button>
        </form>
        <p style="margin-top:14px;">
            <a href="forgot-password.php">Quên mật khẩu?</a> |
            <a href="dangky.php">Chưa có tài khoản? Đăng ký ngay</a>
        </p>
    </section>
</main>
<?php
$content = ob_get_clean();
include 'layouts/main.php';
?>
