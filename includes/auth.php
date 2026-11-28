<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';

function redirect_if_not_logged_in()
{
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

function login($username, $password)
{
    global $conn;
    connect_db();

    $username = mysqli_real_escape_string($conn, $username);
    $sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $res = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($res)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row;
            return true;
        }
    }
    return false;
}

function register_user($username, $password, $fullname, $phone, $member_rank)
{
    global $conn;
    connect_db();

    $username = mysqli_real_escape_string($conn, $username);
    $fullname = mysqli_real_escape_string($conn, $fullname);
    $phone = mysqli_real_escape_string($conn, $phone);
    $member_rank = mysqli_real_escape_string($conn, $member_rank);

    $check_sql = "SELECT user_id FROM users WHERE username = '$username' LIMIT 1";
    $check = mysqli_query($conn, $check_sql);
    if ($check && mysqli_num_rows($check) > 0) {
        return false;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "
        INSERT INTO users(username, password, fullname, phone, member_rank, role) VALUES
        ('$username', '$hash', '$fullname', '$phone', '$member_rank', 'user')
    ";
    $query = mysqli_query($conn, $sql);
    return $query;
}
?>
