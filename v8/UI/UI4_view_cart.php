<?php
// UI4_view_cart.php
require_once '../functions/functions.php'; // Make sure functions.php is accessible

// Start session to retrieve cart
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// If user is not logged in AND not continuing as guest, redirect to checkout prompt
if (!isset($_SESSION['user_logged_in']) && !isset($_SESSION['is_guest'])) {
    header("Location: UI5_user_login.php?redirect=UI4_view_cart.php"); // Redirect to login page with both login and guest options
    exit;
}

$cart = getCart();
$totalPrice = getTotalCartPrice();

// Display cart messages
$cart_message = '';
$cart_message_type = '';
if (isset($_SESSION['cart_message'])) {
    $cart_message = $_SESSION['cart_message'];
    $cart_message_type = $_SESSION['cart_message_type'];
    unset($_SESSION['cart_message'], $_SESSION['cart_message_type']);
}

include '../header.php';
?>
<link rel="stylesheet" href="../css/cart.css">
</head>

<body>
    <div class="container cart-container-detailed">
        <h1>Your Shopping Cart</h1>

        <?php if ($cart_message): ?>
            <div class="alert alert-<?php echo $cart_message_type; ?>">
                <?php echo htmlspecialchars($cart_message); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($cart)): ?>
            <p class="empty-cart-message-detailed">Your cart is currently empty. <a href="UI2_menu.php">Start shopping now!</a></p>
        <?php else: ?>
            <?php foreach ($cart as $productId => $item): ?>
                <div class="cart-item-detailed">
                    <div class="cart-item-details-detailed">
                        <?php
                        $productDetails = getProductById($productId); // Fetch product details for image
                        $imagePath = $productDetails['image_path'] ?? '';
                        ?>
                        <?php if (!empty($imagePath)): ?>
                            <img src="../uploads/images/<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/80x80?text=No+Image" alt="No Image Available">
                        <?php endif; ?>
                        <div class="cart-item-info-detailed">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p>Unit Price: RM <?php echo htmlspecialchars(number_format($item['price'], 2)); ?></p>
                            <p class="item-subtotal">Subtotal: RM <?php echo htmlspecialchars(number_format($item['price'] * $item['quantity'], 2)); ?></p>
                        </div>
                    </div>
                    <div class="cart-item-actions-detailed">
                        <form class="update-quantity-form-detailed" action="../functions/function3_update_cart.php" method="post" data-unit-price="<?php echo $item['price']; ?>">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($productId); ?>">
                            <label for="qty_<?php echo $productId; ?>">Qty:</label>
                            <input type="number" id="qty_<?php echo $productId; ?>" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1">
                            <button type="submit">Update</button>
                        </form>
                        <form class="remove-item-form-detailed" action="../functions/function1_remove_from_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($productId); ?>">
                            <button type="submit" class="danger">Remove</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="cart-summary-detailed">
                Total: RM <?php echo htmlspecialchars(number_format($totalPrice, 2)); ?>
            </div>

            <!-- Pay at Counter Instruction (initially hidden) -->
            <div class="pay-at-counter-instruction" id="payCounterInstruction" style="display: none;">
                Please proceed to the counter to complete your order and make payment. Thank you!
            </div>

            <div class="cart-actions-detailed">
                <a href="UI2_menu.php" class="btn secondary">Continue Shopping</a>
                <form action="../functions/function4_clear_cart.php" method="post" style="display: inline-block;">
                    <button type="submit" class="btn danger">Clear Cart</button>
                </form>
                <!-- Direct link to login page which has both login and guest options -->
                <button onclick="showPayAtCounterInstruction()" class="btn primary">Proceed to Checkout</button>
            </div>
        <?php endif; ?>
    </div>
</body>

<script>
    function showPayAtCounterInstruction() {
        // Show the pay at counter instruction
        document.getElementById('payCounterInstruction').style.display = 'block';

        // Scroll to the instruction for better visibility
        document.getElementById('payCounterInstruction').scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });

        // Optional: Hide the checkout button after clicking
        event.target.style.display = 'none';
    }

    function recalcTotal() {
        let sum = 0;
        document.querySelectorAll('.item-subtotal').forEach(el => {
            sum += parseFloat(el.textContent.replace('Subtotal: RM ', ''));
        });
        document.querySelector('.cart-summary-detailed').textContent = `Total: RM ${sum.toFixed(2)}`;
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Quantity update on form submit
        document.querySelectorAll('.update-quantity-form-detailed').forEach(form => {
            form.addEventListener('submit', e => {
                e.preventDefault();
                const qtyInput = form.querySelector('input[name="quantity"]');
                const qty = parseInt(qtyInput.value) || 1;
                qtyInput.value = qty;
                const unitPrice = parseFloat(form.dataset.unitPrice);
                const subtotalEl = form.closest('.cart-item-detailed').querySelector('.item-subtotal');
                subtotalEl.textContent = `Subtotal: RM ${(unitPrice * qty).toFixed(2)}`;
                recalcTotal();
                // Submit via AJAX
                fetch(form.action, {
                    method: 'POST',
                    body: new URLSearchParams(new FormData(form))
                });
            });
        });

        // Remove item
        document.querySelectorAll('.remove-item-form-detailed').forEach(form => {
            form.addEventListener('submit', e => {
                e.preventDefault();
                const cartItemEl = form.closest('.cart-item-detailed');
                fetch(form.action, {
                    method: 'POST',
                    body: new URLSearchParams(new FormData(form))
                }).then(() => {
                    cartItemEl.remove();
                    recalcTotal();
                });
            });
        });
    });
</script>

</html>