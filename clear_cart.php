<?php
// clear_cart.php
require_once 'functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    clearCart();
}

header("Location: view_cart.php");
exit;
?>