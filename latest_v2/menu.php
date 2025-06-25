<?php
// menu.php - Clean version with enhanced add to cart
session_start();

// Sample products data (same as before, no duplicates)
$products = [
    // Coffee Beverages
    [
        'id' => 1,
        'name' => 'Espresso',
        'price' => 5.50,
        'category' => 'Coffee',
        'description' => 'Rich, concentrated coffee shot with bold flavor and aromatic crema.',
        'image' => 'uploads/images/espresso.jpg'
    ],
    [
        'id' => 2,
        'name' => 'Americano',
        'price' => 6.00,
        'category' => 'Coffee',
        'description' => 'Smooth espresso with hot water, perfect for everyday coffee lovers.',
        'image' => 'uploads/images/americano.jpg'
    ],
    [
        'id' => 3,
        'name' => 'Latte',
        'price' => 7.50,
        'category' => 'Coffee',
        'description' => 'Creamy espresso with steamed milk, topped with microfoam.',
        'image' => 'uploads/images/latte.jpg'
    ],
    [
        'id' => 4,
        'name' => 'Cappuccino',
        'price' => 7.00,
        'category' => 'Coffee',
        'description' => 'Perfect balance of espresso, steamed milk, and thick foam.',
        'image' => 'uploads/images/cappuccino.jpg'
    ],
    [
        'id' => 5,
        'name' => 'Mocha',
        'price' => 8.00,
        'category' => 'Coffee',
        'description' => 'Rich espresso with chocolate syrup and steamed milk.',
        'image' => 'uploads/images/mocha.jpg'
    ],
    [
        'id' => 6,
        'name' => 'Macchiato',
        'price' => 7.50,
        'category' => 'Coffee',
        'description' => 'Espresso "marked" with a dollop of steamed milk foam.',
        'image' => 'uploads/images/macchiato.jpg'
    ],
    
    // Snacks
    [
        'id' => 7,
        'name' => 'Curry Puff',
        'price' => 4.50,
        'category' => 'Snacks',
        'description' => 'Traditional Malaysian pastry filled with spiced curry potatoes.',
        'image' => 'uploads/images/curry-puff.jpg'
    ],
    [
        'id' => 8,
        'name' => 'Chicken Roll',
        'price' => 5.90,
        'category' => 'Snacks',
        'description' => 'Flaky pastry filled with seasoned chicken and vegetables.',
        'image' => 'uploads/images/chicken-roll.jpg'
    ],
    [
        'id' => 9,
        'name' => 'Sausage Roll',
        'price' => 7.50,
        'category' => 'Snacks',
        'description' => 'Seasoned pork sausage wrapped in golden puff pastry.',
        'image' => 'uploads/images/sausage-roll.jpg'
    ],
    [
        'id' => 10,
        'name' => 'Tuna Puff',
        'price' => 5.50,
        'category' => 'Snacks',
        'description' => 'Light and flaky pastry filled with seasoned tuna.',
        'image' => 'uploads/images/tuna-puff.jpg'
    ],
    
    // Pastries
    [
        'id' => 11,
        'name' => 'Blueberry Muffin',
        'price' => 6.50,
        'category' => 'Pastries',
        'description' => 'Fresh blueberries in a tender, moist muffin. Perfect breakfast treat.',
        'image' => 'uploads/images/blueberry-muffin.jpg'
    ],
    [
        'id' => 12,
        'name' => 'Chocolate Chip Muffin',
        'price' => 6.50,
        'category' => 'Pastries',
        'description' => 'Rich chocolate chip muffin with a tender crumb.',
        'image' => 'uploads/images/chocolate-muffin.jpg'
    ],
    [
        'id' => 13,
        'name' => 'Banana Walnut Muffin',
        'price' => 6.90,
        'category' => 'Pastries',
        'description' => 'Moist banana muffin with crunchy walnuts.',
        'image' => 'uploads/images/banana-muffin.jpg'
    ],
    [
        'id' => 14,
        'name' => 'Croissant',
        'price' => 8.90,
        'category' => 'Pastries',
        'description' => 'Buttery, layered French pastry. Perfect for breakfast.',
        'image' => 'uploads/images/croissant.jpg'
    ],
    
    // Cold Drinks
    [
        'id' => 15,
        'name' => 'Iced Chocolate',
        'price' => 10.90,
        'category' => 'Cold Drinks',
        'description' => 'Rich chocolate drink with ice and whipped cream.',
        'image' => 'uploads/images/iced-chocolate.jpg'
    ],
    [
        'id' => 16,
        'name' => 'Iced Tea',
        'price' => 8.90,
        'category' => 'Cold Drinks',
        'description' => 'Refreshing iced tea with lemon. Various flavors available.',
        'image' => 'uploads/images/iced-tea.jpg'
    ],
    [
        'id' => 17,
        'name' => 'Frappe',
        'price' => 15.90,
        'category' => 'Cold Coffee',
        'description' => 'Blended iced coffee with milk and ice. Thick and creamy.',
        'image' => 'uploads/images/frappe.jpg'
    ],
    [
        'id' => 18,
        'name' => 'Smoothie Bowl',
        'price' => 16.90,
        'category' => 'Healthy',
        'description' => 'Acai smoothie topped with granola, fruits, and nuts.',
        'image' => 'uploads/images/smoothie-bowl.jpg'
    ]
];

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeanMarket Menu - Coffee & Snacks</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="home_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px 0;
        }
        
        .shop-name-nav {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.95);
            padding: 10px 20px;
            border-radius: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .shop-name-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        
        .shop-name-nav a {
            color: #6F4E37;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.2em;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .shop-name-nav a:hover {
            color: #8B4513;
        }
        
        .menu-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .menu-header {
            text-align: center;
            margin-bottom: 40px;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .menu-header h1 {
            color: #6F4E37;
            font-size: 3em;
            margin-bottom: 15px;
            font-weight: bold;
        }
        
        .menu-header p {
            color: #666;
            font-size: 1.2em;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .cart-status {
            background: #6F4E37;
            color: white;
            padding: 15px 25px;
            border-radius: 25px;
            margin-bottom: 30px;
            text-align: center;
            font-size: 1.1em;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(111, 78, 55, 0.3);
        }
        
        .cart-status a {
            color: #fff3cd;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .cart-status a:hover {
            color: white;
            text-decoration: underline;
        }
        
        .category-filters {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        
        .category-btn {
            background: white;
            color: #6F4E37;
            border: 2px solid #6F4E37;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .category-btn:hover,
        .category-btn.active {
            background: #6F4E37;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(111, 78, 55, 0.3);
            text-decoration: none;
        }
        
        .products-section {
            margin-bottom: 50px;
        }
        
        .section-title {
            background: linear-gradient(135deg, #6F4E37, #8B4513);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .section-title h2 {
            margin: 0;
            font-size: 2em;
            font-weight: bold;
        }
        
        .section-title p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
            padding: 10px;
        }
        
        .product-item {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .product-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            border-color: #6F4E37;
        }
        
        .product-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
            background: #f8f9fa;
        }
        
        .product-item:hover img {
            transform: scale(1.05);
        }
        
        .product-item h3 {
            font-size: 1.4em;
            color: #6F4E37;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .product-item .price {
            font-size: 1.6em;
            color: #8B4513;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .product-item .description {
            color: #666;
            font-size: 0.95em;
            line-height: 1.5;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .product-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #6F4E37, #8B4513);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(111, 78, 55, 0.3);
            color: white;
            text-decoration: none;
        }
        
        .btn-secondary {
            background: white;
            color: #6F4E37;
            border: 2px solid #6F4E37;
        }
        
        .btn-secondary:hover {
            background: #6F4E37;
            color: white;
            text-decoration: none;
        }
        
        .category-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(111, 78, 55, 0.9);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        
        /* Add to Cart Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s ease;
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            animation: slideIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }
        
        .close:hover {
            color: #6F4E37;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group select,
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-group select:focus,
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #6F4E37;
            box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .quantity-btn {
            background: #6F4E37;
            color: white;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        
        .quantity-btn:hover {
            background: #8B4513;
        }
        
        .quantity-input {
            width: 80px !important;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
        }
        
        @media (max-width: 768px) {
            .menu-header h1 {
                font-size: 2.2em;
            }
            
            .category-filters {
                gap: 10px;
            }
            
            .category-btn {
                padding: 10px 20px;
                font-size: 14px;
            }
            
            .product-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 20px;
            }
            
            .modal-content {
                margin: 10% auto;
                padding: 20px;
                width: 95%;
            }
        }
        
        @media (max-width: 480px) {
            .menu-container {
                padding: 10px;
            }
            
            .menu-header {
                padding: 30px 20px;
            }
            
            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Shop Name Navigation -->
    <div class="shop-name-nav">
        <a href="index.php">
            <i class="fas fa-coffee"></i>
            BeanMarket
        </a>
    </div>
    
    <div class="menu-container">
        <div class="menu-header">
            <h1><i class="fas fa-coffee"></i> BeanMarket Menu</h1>
            <p>Discover our premium coffee beverages and delicious snacks. Your alternative to expensive coffee shops - quality coffee that's a necessity, not a luxury.</p>
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
                <a href="view_cart.php">View Cart</a> |
                <a href="checkout_prompt.php">Checkout</a>
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
                echo '<h2>' . htmlspecialchars($category) . '</h2>';
                
                $descriptions = [
                    'Coffee' => 'Premium espresso-based beverages made with single-origin beans',
                    'Cold Coffee' => 'Refreshing iced coffee drinks perfect for Malaysia\'s climate',
                    'Snacks' => 'Savory pastries and local favorites to pair with your coffee',
                    'Pastries' => 'Freshly baked sweet treats and breakfast pastries',
                    'Cold Drinks' => 'Non-coffee cold beverages for all preferences',
                    'Healthy' => 'Nutritious options for health-conscious customers'
                ];
                
                echo '<p>' . ($descriptions[$category] ?? 'Delicious items in this category') . '</p>';
                echo '</div>';
                
                echo '<div class="product-grid">';
                foreach ($categoryProducts as $product) {
                    echo '<div class="product-item">';
                    echo '<div class="category-badge">' . htmlspecialchars($product['category']) . '</div>';
                    
                    echo '<img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '" onerror="this.src=\'https://placehold.co/300x200/6F4E37/ffffff?text=' . urlencode($product['name']) . '\'">';
                    
                    echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
                    echo '<div class="price">RM ' . number_format($product['price'], 2) . '</div>';
                    echo '<div class="description">' . htmlspecialchars($product['description']) . '</div>';
                    
                    echo '<div class="product-actions">';
                    echo '<button class="btn btn-primary" onclick="openAddToCartModal(' . $product['id'] . ', \'' . htmlspecialchars($product['name']) . '\', ' . $product['price'] . ', \'' . htmlspecialchars($product['category']) . '\')">';
                    echo '<i class="fas fa-cart-plus"></i> Add to Cart';
                    echo '</button>';
                    echo '<a href="product_details.php?id=' . $product['id'] . '" class="btn btn-secondary">';
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
            
            <form id="addToCartForm" method="post" action="add_to_cart.php">
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
                    <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 18px; padding: 15px;">
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
</body>
</html>
