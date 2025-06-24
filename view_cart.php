<?php
// view_cart.php
require_once 'functions.php'; // Make sure functions.php is accessible

// Start session to retrieve cart
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// If user is not logged in AND not continuing as guest, redirect to checkout prompt
if (!isset($_SESSION['user_logged_in']) && !isset($_SESSION['is_guest'])) {
    header("Location: checkout_prompt.php"); // Redirect to the new prompt page
    exit;
}

$cart = getCart();
$totalPrice = getTotalCartPrice();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart - BeanMarket</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Specific styles for view_cart.php */
        .cart-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 25px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .cart-container h1 {
            text-align: center;
            color: #343a40;
            margin-bottom: 30px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px dashed #e0e0e0;
            margin-bottom: 15px;
        }

        .cart-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .cart-item-details {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }

        .cart-item-details img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cart-item-info {
            flex-grow: 1;
            text-align: left;
        }

        .cart-item-info h3 {
            margin: 0 0 5px 0;
            font-size: 1.2em;
            color: #333;
        }

        .cart-item-info p {
            margin: 0;
            font-size: 0.95em;
            color: #666;
        }

        .cart-item-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .update-quantity-form {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .update-quantity-form label {
            font-size: 0.9em;
            color: #555;
            font-weight: bold;
        }

        .update-quantity-form input[type="number"] {
            width: 60px;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            text-align: center;
            font-size: 0.95em;
        }

        .update-quantity-form button,
        .remove-item-form button {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9em;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .update-quantity-form button {
            background-color: #007bff;
            color: white;
        }

        .update-quantity-form button:hover {
            background-color: #0056b3;
            transform: translateY(-1px);
        }

        .remove-item-form button.danger {
            background-color: #dc3545;
            color: white;
        }

        .remove-item-form button.danger:hover {
            background-color: #c82333;
            transform: translateY(-1px);
        }

        .cart-summary {
            text-align: right;
            font-size: 1.5em;
            font-weight: bold;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            color: #333;
        }

        .empty-cart-message {
            text-align: center;
            font-size: 1.2em;
            color: #888;
            margin-top: 50px;
        }

        .cart-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
            gap: 15px; /* Space between buttons */
        }

        .cart-actions .btn {
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1em;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: inline-block; /* Ensure buttons behave like blocks */
            text-align: center;
            min-width: 160px; /* Ensure consistent button size */
        }

        .cart-actions .btn.secondary {
            background-color: #6c757d;
            color: white;
        }

        .cart-actions .btn.secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .cart-actions .btn.danger {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .cart-actions .btn.danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .cart-actions .btn.primary {
            background-color: #28a745; /* Changed to success green for checkout */
            color: white;
        }

        .cart-actions .btn.primary:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        /* Pay at Counter instruction */
        .pay-at-counter-instruction {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background-color: #e9f7ef; /* Light green background */
            border: 1px solid #c8e6c9;
            border-radius: 8px;
            font-size: 1.1em;
            color: #28a745; /* Darker green text */
            font-weight: bold;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .cart-item-details {
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 10px;
            }
            .cart-item-details img {
                margin-bottom: 10px;
            }
            .cart-item-actions {
                width: 100%;
                justify-content: flex-start;
            }
            .update-quantity-form {
                width: 100%;
                justify-content: space-between;
            }
            .update-quantity-form input {
                flex-grow: 1;
            }
            .cart-actions {
                flex-direction: column;
                align-items: center;
            }
            .cart-actions .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container cart-container">
        <h1>Your Shopping Cart</h1>

        <?php if (empty($cart)): ?>
            <p class="empty-cart-message">Your cart is currently empty. <a href="menu.php">Start shopping now!</a></p>
        <?php else: ?>
            <?php foreach ($cart as $productId => $item): ?>
                <div class="cart-item">
                    <div class="cart-item-details">
                        <?php
                        $productDetails = getProductById($productId); // Fetch product details for image
                        $imagePath = $productDetails['image_path'] ?? '';
                        ?>
                        <?php if (!empty($imagePath)): ?>
                            <img src="uploads/images/<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/80x80?text=No+Image" alt="No Image Available">
                        <?php endif; ?>
                        <div class="cart-item-info">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p>Unit Price: $<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></p>
                            <p>Subtotal: $<?php echo htmlspecialchars(number_format($item['price'] * $item['quantity'], 2)); ?></p>
                        </div>
                    </div>
                    <div class="cart-item-actions">
                        <form class="update-quantity-form" action="update_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($productId); ?>">
                            <label for="qty_<?php echo $productId; ?>">Qty:</label>
                            <input type="number" id="qty_<?php echo $productId; ?>" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1">
                            <button type="submit">Update</button>
                        </form>
                        <form class="remove-item-form" action="remove_from_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($productId); ?>">
                            <button type="submit" class="danger">Remove</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="cart-summary">
                Total: $<?php echo htmlspecialchars(number_format($totalPrice, 2)); ?>
            </div>

            <!-- New: Pay at Counter Instruction -->
            <div class="pay-at-counter-instruction">
                Please proceed to the counter to complete your order and make payment. Thank you!
            </div>

            <div class="cart-actions">
                <a href="menu.php" class="btn secondary">Continue Shopping</a>
                <form action="clear_cart.php" method="post" style="display: inline-block;">
                    <button type="submit" class="btn danger">Clear Cart</button>
                </form>
                <!-- Changed 'Proceed to Checkout' to redirect to checkout_prompt.php -->
                <a href="checkout_prompt.php" class="btn primary">Proceed to Checkout</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
