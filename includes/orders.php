<?php
require_once __DIR__ . '/db.php';

function create_order($user_id, $customer_name, $customer_phone, $customer_address, $order_note, $payment_method, $voucher_code, $discount_amount, $total_amount, $cart_items)
{
    global $conn;
    connect_db();

    $order_code = 'DH' . date('YmdHis') . rand(10, 99);
    $user_id_sql = $user_id ? (int)$user_id : 'NULL';
    $customer_name = mysqli_real_escape_string($conn, $customer_name);
    $customer_phone = mysqli_real_escape_string($conn, $customer_phone);
    $customer_address = mysqli_real_escape_string($conn, $customer_address);
    $order_note = mysqli_real_escape_string($conn, $order_note);
    $payment_method = mysqli_real_escape_string($conn, $payment_method);
    $voucher_code = mysqli_real_escape_string($conn, $voucher_code);
    $discount_amount = (int)$discount_amount;
    $total_amount = (int)$total_amount;

    $sql = "
        INSERT INTO tb_orders(order_code, user_id, customer_name, customer_phone, customer_address, order_note, payment_method, voucher_code, discount_amount, total_amount, order_status) VALUES
        ('$order_code', $user_id_sql, '$customer_name', '$customer_phone', '$customer_address', '$order_note', '$payment_method', '$voucher_code', $discount_amount, $total_amount, 'Đang chuẩn bị')
    ";
    $query = mysqli_query($conn, $sql);
    if (!$query) {
        return false;
    }

    $order_id = mysqli_insert_id($conn);
    foreach ($cart_items as $item) {
        $food_id = (int)$item['food_id'];
        $food_name = mysqli_real_escape_string($conn, $item['food_name']);
        $food_option = mysqli_real_escape_string($conn, $item['food_option']);
        $price = (int)$item['price'];
        $quantity = (int)$item['quantity'];

        $sql_item = "
            INSERT INTO tb_order_items(order_id, food_id, food_name, food_option, price, quantity) VALUES
            ($order_id, $food_id, '$food_name', '$food_option', $price, $quantity)
        ";
        mysqli_query($conn, $sql_item);
    }

    return $order_code;
}

function get_order_by_code($order_code)
{
    global $conn;
    connect_db();

    $order_code = mysqli_real_escape_string($conn, $order_code);
    $sql = "SELECT * FROM tb_orders WHERE order_code = '$order_code' LIMIT 1";
    $query = mysqli_query($conn, $sql);
    $result = array();

    if ($query && mysqli_num_rows($query) > 0) {
        $result = mysqli_fetch_assoc($query);
    }

    return $result;
}

function get_order_items($order_id)
{
    global $conn;
    connect_db();

    $order_id = (int)$order_id;
    $sql = "SELECT * FROM tb_order_items WHERE order_id = $order_id";
    $query = mysqli_query($conn, $sql);
    $result = array();

    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $result[] = $row;
        }
    }

    return $result;
}

function add_catering($fullname, $phone, $service_type, $meal_quantity, $serve_date, $location, $note)
{
    global $conn;
    connect_db();

    $fullname = addslashes($fullname);
    $phone = addslashes($phone);
    $service_type = addslashes($service_type);
    $meal_quantity = (int)$meal_quantity;
    $serve_date = addslashes($serve_date);
    $location = addslashes($location);
    $note = addslashes($note);

    $sql = "
        INSERT INTO tb_catering(fullname, phone, service_type, meal_quantity, serve_date, location, note) VALUES
        ('$fullname', '$phone', '$service_type', $meal_quantity, '$serve_date', '$location', '$note')
    ";
    $query = mysqli_query($conn, $sql);
    return $query;
}
?>
