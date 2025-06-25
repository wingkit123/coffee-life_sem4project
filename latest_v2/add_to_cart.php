<?php
// add_to_cart.php - Enhanced with customization options
require_once 'functions.php';

// Start session to use cart functions
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Get customization options
    $sugar_level = isset($_POST['sugar_level']) ? $_POST['sugar_level'] : 'Regular';
    $milk_type = isset($_POST['milk_type']) ? $_POST['milk_type'] : 'Regular Milk';
    $special_instructions = isset($_POST['special_instructions']) ? trim($_POST['special_instructions']) : '';

    if ($productId > 0 && $quantity > 0) {
        // Enhanced cart item with customizations
        $cartItem = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'sugar_level' => $sugar_level,
            'milk_type' => $milk_type,
            'special_instructions' => $special_instructions,
            'added_time' => time()
        ];

        if (addToCartEnhanced($cartItem)) {
            // Set success message in session
            $_SESSION['cart_message'] = "Item added to cart successfully!";
            $_SESSION['cart_message_type'] = 'success';

            // Redirect back to the page where the user came from, or to the cart page
            if (isset($_SERVER['HTTP_REFERER'])) {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            } else {
                header("Location: menu.php");
            }
            exit;
        } else {
            $_SESSION['cart_message'] = "Error: Could not add product to cart. Product might not exist.";
            $_SESSION['cart_message_type'] = 'error';
            header("Location: menu.php");
            exit;
        }
    } else {
        $_SESSION['cart_message'] = "Error: Invalid product ID or quantity.";
        $_SESSION['cart_message_type'] = 'error';
        header("Location: menu.php");
        exit;
    }
} else {
    // If accessed directly without POST, redirect to menu
    header("Location: menu.php");
    exit;
}
