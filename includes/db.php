<?php
global $conn;

function connect_db()
{
    global $conn;
    if (!$conn) {
        $conn = mysqli_connect('localhost', 'root', '', 'doof_food') or die('Can not connect to database');
        mysqli_set_charset($conn, 'utf8mb4');
    }
}

function disconnect_db()
{
    global $conn;
    if ($conn) {
        mysqli_close($conn);
        $conn = null;
    }
}
?>
