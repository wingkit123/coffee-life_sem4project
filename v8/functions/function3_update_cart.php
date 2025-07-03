<?php
// function3_update_cart.php
require_once 'functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $newQuantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0; // Can be 0 to remove

    if ($productId > 0) {
        updateCartItemQuantity($productId, $newQuantity);
    }
}

header("Location: ../UI/UI4_view_cart.php");
exit;
?>