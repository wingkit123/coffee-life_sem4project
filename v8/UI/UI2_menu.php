<?php
// menu.php - Database-driven menu with enhanced add to cart
session_start();
include '../header.php';
require_once '../config.php';

// Fetch products from database
try {
    $stmt = $pdo->prepare("SELECT * FROM product WHERE quantity > 0 ORDER BY category, name");
    $stmt->execute();
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching products: " . $e->getMessage());
    $products = [];
}

// Get unique categories for filtering
$categories = array_unique(array_column($products, 'category'));

// Display cart messages
$cart_message = '';
$cart_message_type = '';
if (isset($_SESSION['cart_message'])) {
    $cart_message = $_SESSION['cart_message'];
    $cart_message_type = $_SESSION['cart_message_type'];
    unset($_SESSION['cart_message'], $_SESSION['cart_message_type']);
}
?>

<!-- Page-specific CSS for Menu -->
<link rel="stylesheet" href="../css/menu.css">

<div class="menu-container">
    <div class="menu-header">
        <h1><i class="fas fa-coffee"></i>Menu</h1>
        <p>Discover our premium coffee beverages and delicious snacks.</p>
    </div>

    <?php if ($cart_message): ?>
        <div class="alert alert-<?php echo $cart_message_type; ?>">
            <?php echo htmlspecialchars($cart_message); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
        <div class="cart-status">
            <i class="fas fa-shopping-cart"></i>
            You have <?php echo count($_SESSION['cart']); ?> item(s) in your cart.
            <a href="UI4_view_cart.php">View Cart</a> |
            <a href="UI5_user_login.php?redirect=UI4_view_cart.php">Checkout</a>
        </div>
    <?php endif; ?>

    <!-- Category Filters -->
    <div class="category-filters">
        <button class="category-btn active" onclick="filterProducts('all')">
            <i class="fas fa-th"></i> All Items
        </button>
        <?php foreach ($categories as $category): ?>
            <button class="category-btn" onclick="filterProducts('<?php echo strtolower(str_replace(' ', '-', $category)); ?>')">
                <?php
                $icons = [
                    'Coffee' => 'fas fa-coffee',
                    'Cold Coffee' => 'fas fa-snowflake',
                    'Snacks' => 'fas fa-cookie-bite',
                    'Pastries' => 'fas fa-birthday-cake',
                    'Cold Drinks' => 'fas fa-glass-whiskey',
                    'Healthy' => 'fas fa-leaf'
                ];
                echo '<i class="' . ($icons[$category] ?? 'fas fa-utensils') . '"></i> ';
                echo $category;
                ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Products by Category -->
    <?php
    foreach ($categories as $category) {
        $categoryProducts = array_filter($products, function ($product) use ($category) {
            return $product['category'] === $category;
        });

        if (!empty($categoryProducts)) {
            echo '<div class="products-section" data-category="' . strtolower(str_replace(' ', '-', $category)) . '">';
            echo '<div class="section-title">';
            echo '<h2 style="color: white;">' . htmlspecialchars($category) . '</h2>';

            $descriptions = [
                'Coffee' => 'Premium espresso-based beverages made with single-origin beans',
                'Cold Coffee' => 'Refreshing iced coffee drinks perfect for Malaysia\'s climate',
                'Snacks' => 'Savory pastries and local favorites to pair with your coffee',
                'Pastries' => 'Freshly baked sweet treats and breakfast pastries',
                'Cold Drinks' => 'Non-coffee cold beverages for all preferences',
                'Healthy' => 'Nutritious options for health-conscious customers'
            ];

            echo '<p style="color: white;">' . ($descriptions[$category] ?? 'Delicious items in this category') . '</p>';
            echo '</div>';

            echo '<div class="product-grid">';
            foreach ($categoryProducts as $product) {
                echo '<div class="product-item">';
                echo '<div class="category-badge">' . htmlspecialchars($product['category']) . '</div>';

                echo '<img src="../' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '" onerror="this.src=\'https://placehold.co/300x200/6F4E37/ffffff?text=' . urlencode($product['name']) . '\'">';

                echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
                echo '<div class="price">RM ' . number_format($product['price'], 2) . '</div>';
                echo '<div class="description">' . htmlspecialchars($product['description']) . '</div>';

                echo '<div class="product-actions">';
                echo '<button class="btn btn-primary" onclick="openAddToCartModal(' . $product['id'] . ', \'' . htmlspecialchars($product['name']) . '\', ' . $product['price'] . ', \'' . htmlspecialchars($product['category']) . '\')">';
                echo '<i class="fas fa-cart-plus"></i> Add to Cart';
                echo '</button>';
                echo '<a href="UI3_product_details.php?id=' . $product['id'] . '" class="btn btn-secondary">';
                echo '<i class="fas fa-info-circle"></i> Details';
                echo '</a>';
                echo '</div>';

                echo '</div>';
            }
            echo '</div>'; // product-grid
            echo '</div>'; // products-section
        }
    }
    ?>
</div>

<!-- Add to Cart Modal -->
<div id="addToCartModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddToCartModal()">&times;</span>
        <h2 id="modalProductName">Add to Cart</h2>

        <form id="addToCartForm" method="post" action="../functions/function2_add_to_cart.php">
            <input type="hidden" id="modalProductId" name="product_id">

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <div class="quantity-controls">
                    <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">-</button>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="10" class="quantity-input">
                    <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
                </div>
            </div>

            <div class="form-group" id="sugarLevelGroup">
                <label for="sugar_level">Sugar Level:</label>
                <select id="sugar_level" name="sugar_level">
                    <option value="No Sugar">No Sugar</option>
                    <option value="Less Sweet">Less Sweet (25%)</option>
                    <option value="Regular" selected>Regular (50%)</option>
                    <option value="Extra Sweet">Extra Sweet (75%)</option>
                    <option value="Very Sweet">Very Sweet (100%)</option>
                </select>
            </div>

            <div class="form-group" id="milkTypeGroup">
                <label for="milk_type">Milk Type:</label>
                <select id="milk_type" name="milk_type">
                    <option value="Regular Milk" selected>Regular Milk</option>
                    <option value="Soy Milk">Soy Milk (+RM 1.00)</option>
                    <option value="Almond Milk">Almond Milk (+RM 1.50)</option>
                    <option value="Oat Milk">Oat Milk (+RM 1.50)</option>
                    <option value="Coconut Milk">Coconut Milk (+RM 1.00)</option>
                    <option value="No Milk">No Milk</option>
                </select>
            </div>

            <div class="form-group">
                <label for="special_instructions">Special Instructions (Optional):</label>
                <textarea id="special_instructions" name="special_instructions" placeholder="Extra hot, extra foam, etc..."></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-fullwidth">
                    <i class="fas fa-cart-plus"></i> Add to Cart - RM <span id="totalPrice">0.00</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentProductPrice = 0;

    function openAddToCartModal(productId, productName, price, category) {
        currentProductPrice = price;

        document.getElementById('modalProductId').value = productId;
        document.getElementById('modalProductName').textContent = 'Add ' + productName + ' to Cart';

        // Show/hide customization options based on category
        const sugarGroup = document.getElementById('sugarLevelGroup');
        const milkGroup = document.getElementById('milkTypeGroup');

        if (category === 'Coffee' || category === 'Cold Coffee') {
            sugarGroup.style.display = 'block';
            milkGroup.style.display = 'block';
        } else {
            sugarGroup.style.display = 'none';
            milkGroup.style.display = 'none';
        }

        // Reset form
        document.getElementById('quantity').value = 1;
        document.getElementById('sugar_level').value = 'Regular';
        document.getElementById('milk_type').value = 'Regular Milk';
        document.getElementById('special_instructions').value = '';

        updateTotalPrice();
        document.getElementById('addToCartModal').style.display = 'block';
    }

    function closeAddToCartModal() {
        document.getElementById('addToCartModal').style.display = 'none';
    }

    function changeQuantity(change) {
        const quantityInput = document.getElementById('quantity');
        let newQuantity = parseInt(quantityInput.value) + change;
        if (newQuantity < 1) newQuantity = 1;
        if (newQuantity > 10) newQuantity = 10;
        quantityInput.value = newQuantity;
        updateTotalPrice();
    }

    function updateTotalPrice() {
        const quantity = parseInt(document.getElementById('quantity').value);
        const milkType = document.getElementById('milk_type').value;

        let milkPrice = 0;
        if (milkType === 'Soy Milk' || milkType === 'Coconut Milk') milkPrice = 1.00;
        if (milkType === 'Almond Milk' || milkType === 'Oat Milk') milkPrice = 1.50;

        const totalPrice = (currentProductPrice + milkPrice) * quantity;
        document.getElementById('totalPrice').textContent = totalPrice.toFixed(2);
    }

    // Update price when milk type changes
    document.getElementById('milk_type').addEventListener('change', updateTotalPrice);
    document.getElementById('quantity').addEventListener('input', updateTotalPrice);

    // Category filtering
    function filterProducts(category) {
        const sections = document.querySelectorAll('.products-section');
        const buttons = document.querySelectorAll('.category-btn');

        // Update active button
        buttons.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');

        // Show/hide sections
        sections.forEach(section => {
            if (category === 'all' || section.dataset.category === category) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('addToCartModal');
        if (event.target === modal) {
            closeAddToCartModal();
        }
    }

    // Auto-hide success/error messages
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 3000);
</script>

<?php include '../footer.php'; ?>