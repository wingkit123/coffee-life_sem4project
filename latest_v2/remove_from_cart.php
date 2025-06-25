<?php
// remove_from_cart.php
require_once 'functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

    if ($productId > 0) {
        removeFromCart($productId);
    }
}

header("Location: view_cart.php");
exit;
?>