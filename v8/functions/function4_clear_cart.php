<?php
// function4_clear_cart.php
require_once 'functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Log for debugging
error_log("Clear cart function called. Method: " . $_SERVER["REQUEST_METHOD"]);
error_log("Session cart before clear: " . print_r($_SESSION['cart'] ?? [], true));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (clearCart()) {
        $_SESSION['cart_message'] = "Cart cleared successfully!";
        $_SESSION['cart_message_type'] = 'success';
        error_log("Cart cleared successfully");
    } else {
        $_SESSION['cart_message'] = "Error clearing cart.";
        $_SESSION['cart_message_type'] = 'error';
        error_log("Error clearing cart");
    }
} else {
    error_log("Not a POST request, redirecting to cart");
}

error_log("Session cart after clear: " . print_r($_SESSION['cart'] ?? [], true));
header("Location: ../UI/UI4_view_cart.php");
exit;
?>