<?php
require_once 'includes/auth.php';
require_once 'includes/foods.php';

redirect_if_not_logged_in();

$id = isset($_POST['id']) ? (int)$_POST['id'] : '';
if ($id) {
    delete_food($id);
}
disconnect_db();

header("location: food-list.php");
exit;
?>
