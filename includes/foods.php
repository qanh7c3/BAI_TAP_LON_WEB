<?php
require_once __DIR__ . '/db.php';

function get_all_foods()
{
    global $conn;
    connect_db();

    $sql = "select * from tb_foods";
    $query = mysqli_query($conn, $sql);
    $result = array();

    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $result[] = $row;
        }
    }

    return $result;
}

function get_food($food_id)
{
    global $conn;
    connect_db();

    $food_id = (int)$food_id;
    $sql = "select * from tb_foods where food_id = {$food_id}";
    $query = mysqli_query($conn, $sql);
    $result = array();

    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $result = $row;
    }

    return $result;
}

function add_food($food_name, $food_category, $food_tag, $food_desc, $food_address, $food_price, $food_image, $food_options)
{
    global $conn;
    connect_db();

    $food_name = addslashes($food_name);
    $food_category = addslashes($food_category);
    $food_tag = addslashes($food_tag);
    $food_desc = addslashes($food_desc);
    $food_address = addslashes($food_address);
    $food_price = (int)$food_price;
    $food_image = addslashes($food_image);
    $food_options = addslashes($food_options);

    $sql = "
        INSERT INTO tb_foods(food_name, food_category, food_tag, food_desc, food_address, food_price, food_image, food_options) VALUES
        ('$food_name', '$food_category', '$food_tag', '$food_desc', '$food_address', $food_price, '$food_image', '$food_options')
    ";
    $query = mysqli_query($conn, $sql);
    return $query;
}

function edit_food($food_id, $food_name, $food_category, $food_tag, $food_desc, $food_address, $food_price, $food_image, $food_options)
{
    global $conn;
    connect_db();

    $food_id = (int)$food_id;
    $food_name = addslashes($food_name);
    $food_category = addslashes($food_category);
    $food_tag = addslashes($food_tag);
    $food_desc = addslashes($food_desc);
    $food_address = addslashes($food_address);
    $food_price = (int)$food_price;
    $food_image = addslashes($food_image);
    $food_options = addslashes($food_options);

    $sql = "
        UPDATE tb_foods SET
        food_name = '$food_name',
        food_category = '$food_category',
        food_tag = '$food_tag',
        food_desc = '$food_desc',
        food_address = '$food_address',
        food_price = $food_price,
        food_image = '$food_image',
        food_options = '$food_options'
        WHERE food_id = $food_id
    ";
    $query = mysqli_query($conn, $sql);
    return $query;
}

function delete_food($food_id)
{
    global $conn;
    connect_db();

    $food_id = (int)$food_id;
    $sql = "
        DELETE FROM tb_foods
        WHERE food_id = $food_id
    ";
    $query = mysqli_query($conn, $sql);
    return $query;
}
?>
