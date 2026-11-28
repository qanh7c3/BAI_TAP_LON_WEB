<?php
require_once 'includes/db.php';

$page_title = 'Quên mật khẩu - Doof Food';
$message = '';
$is_error = false;

if ($_POST) {
    $username = trim($_POST['username'] ?? '');
    if ($username) {
        connect_db();
        $new_pass = password_hash('1234567', PASSWORD_DEFAULT);
        $username = mysqli_real_escape_string($conn, $username);
        $sql = "UPDATE users SET password = '$new_pass' WHERE username = '$username'";
        if (mysqli_query($conn, $sql) && mysqli_affected_rows($conn) > 0) {
            $message = "Mật khẩu đã được đặt lại thành: <strong>1234567</strong>";
        } else {
            $message = "Không tìm thấy người dùng.";
            $is_error = true;
        }
        disconnect_db();
    }
}

ob_start();
?>
<main>
    <section class="booking-section">
        <h2>Quên mật khẩu</h2>
        <?php if ($message): ?>
            <p style="color:<?= $is_error ? 'red' : 'green' ?>"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post">
            <p>Nhập tên đăng nhập để khôi phục mật khẩu:</p>
            <p><input type="text" name="username" placeholder="Tên đăng nhập" required></p>
            <p><button type="submit">Khôi phục</button></p>
            <p><a href="login.php">Quay lại đăng nhập</a></p>
        </form>
    </section>
</main>
<?php
$content = ob_get_clean();
include 'layouts/main.php';
?>
