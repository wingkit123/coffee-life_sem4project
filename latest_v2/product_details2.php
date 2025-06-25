<?php
// product_details2.php - Second product details page for Cappuccino
require_once 'functions.php';
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Sample product data for Cappuccino (ID: 2)
$product = [
  'id' => 2,
  'name' => 'Premium Cappuccino',
  'price' => 12.90,
  'description' => 'Our signature cappuccino featuring rich espresso perfectly balanced with velvety steamed milk and topped with a thick layer of creamy foam. Made with premium Arabica beans sourced from the highlands of Ethiopia.',
  'image' => 'uploads/images/cappuccino-hero.jpg',
  'category' => 'Coffee',
  'ingredients' => ['Premium Espresso Shot', 'Fresh Whole Milk', 'Steamed Milk Foam', 'Optional: Cocoa Powder'],
  'nutritional_info' => [
    'Calories' => '120',
    'Caffeine' => '75mg',
    'Protein' => '6g',
    'Fat' => '4g',
    'Carbs' => '12g'
  ],
  'sizes' => [
    'Small (8oz)' => 10.90,
    'Medium (12oz)' => 12.90,
    'Large (16oz)' => 14.90
  ],
  'customizations' => [
    'Extra Shot (+RM 2.00)',
    'Decaf Option (Free)',
    'Oat Milk (+RM 1.50)',
    'Almond Milk (+RM 1.50)',
    'Extra Hot/Extra Foam (Free)',
    'Sugar-free Vanilla (+RM 1.00)'
  ]
];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
  // Add to cart functionality
  $size = $_POST['size'] ?? 'Medium (12oz)';
  $customizations = $_POST['customizations'] ?? [];
  $quantity = (int)($_POST['quantity'] ?? 1);

  // Calculate total price
  $base_price = $product['sizes'][$size];
  $customization_cost = 0;

  foreach ($customizations as $custom) {
    if (strpos($custom, '+RM 2.00') !== false) $customization_cost += 2.00;
    if (strpos($custom, '+RM 1.50') !== false) $customization_cost += 1.50;
    if (strpos($custom, '+RM 1.00') !== false) $customization_cost += 1.00;
  }

  $total_price = ($base_price + $customization_cost) * $quantity;

  // Initialize cart if not exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Add to cart
  $cart_item = [
    'id' => $product['id'],
    'name' => $product['name'],
    'size' => $size,
    'customizations' => $customizations,
    'quantity' => $quantity,
    'price' => $total_price,
    'unit_price' => $base_price + $customization_cost
  ];

  $_SESSION['cart'][] = $cart_item;
  $success_message = "Added to cart successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($product['name']); ?> - BeanMarket</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="home_page.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .product-detail-container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 20px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
    }

    .product-header {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      margin-bottom: 40px;
    }

    .product-image-section {
      text-align: center;
    }

    .product-main-image {
      width: 100%;
      max-width: 500px;
      height: 400px;
      object-fit: cover;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      margin-bottom: 20px;
    }

    .product-info {
      padding: 20px 0;
    }

    .product-title {
      font-size: 2.5em;
      color: #6F4E37;
      margin-bottom: 15px;
      font-weight: bold;
    }

    .product-price {
      font-size: 2em;
      color: #8B4513;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .product-description {
      font-size: 1.1em;
      line-height: 1.6;
      color: #555;
      margin-bottom: 30px;
      text-align: justify;
    }

    .product-details-tabs {
      margin-bottom: 30px;
    }

    .tab-buttons {
      display: flex;
      border-bottom: 2px solid #e9ecef;
      margin-bottom: 20px;
    }

    .tab-button {
      background: none;
      border: none;
      padding: 15px 25px;
      cursor: pointer;
      font-size: 16px;
      font-weight: bold;
      color: #666;
      transition: all 0.3s ease;
      border-bottom: 3px solid transparent;
    }

    .tab-button.active {
      color: #6F4E37;
      border-bottom-color: #6F4E37;
    }

    .tab-content {
      display: none;
      padding: 20px 0;
    }

    .tab-content.active {
      display: block;
    }

    .ingredients-list,
    .nutritional-list {
      list-style: none;
      padding: 0;
    }

    .ingredients-list li,
    .nutritional-list li {
      padding: 10px;
      margin-bottom: 8px;
      background: #f8f9fa;
      border-radius: 8px;
      border-left: 4px solid #6F4E37;
    }

    .order-form {
      background: #f8f9fa;
      padding: 30px;
      border-radius: 15px;
      border: 2px solid #e9ecef;
    }

    .form-section {
      margin-bottom: 25px;
    }

    .form-section h4 {
      color: #6F4E37;
      margin-bottom: 15px;
      font-size: 1.2em;
    }

    .size-options,
    .customization-options {
      display: grid;
      gap: 10px;
    }

    .size-option,
    .customization-option {
      display: flex;
      align-items: center;
      padding: 12px 15px;
      background: white;
      border: 2px solid #e9ecef;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .size-option:hover,
    .customization-option:hover {
      border-color: #6F4E37;
      background: #fff8f0;
    }

    .size-option input,
    .customization-option input {
      margin-right: 10px;
    }

    .quantity-section {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 25px;
    }

    .quantity-input {
      width: 80px;
      padding: 12px;
      border: 2px solid #e9ecef;
      border-radius: 8px;
      text-align: center;
      font-size: 16px;
    }

    .quantity-btn {
      background: #6F4E37;
      color: white;
      border: none;
      width: 40px;
      height: 40px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 18px;
      transition: all 0.3s ease;
    }

    .quantity-btn:hover {
      background: #8B4513;
    }

    .add-to-cart-btn {
      width: 100%;
      background: linear-gradient(135deg, #6F4E37, #8B4513);
      color: white;
      border: none;
      padding: 18px;
      border-radius: 12px;
      font-size: 18px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .add-to-cart-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(111, 78, 55, 0.3);
    }

    .back-link {
      display: inline-block;
      margin-bottom: 20px;
      color: #6F4E37;
      text-decoration: none;
      font-weight: bold;
      font-size: 16px;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    .success-message {
      background: #d4edda;
      color: #155724;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      border: 1px solid #c3e6cb;
    }

    .rating-section {
      text-align: center;
      margin: 20px 0;
    }

    .stars {
      color: #ffc107;
      font-size: 1.5em;
      margin-bottom: 10px;
    }

    .related-products {
      margin-top: 50px;
      padding-top: 30px;
      border-top: 2px solid #e9ecef;
    }

    .related-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }

    .related-item {
      background: white;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }

    .related-item:hover {
      transform: translateY(-5px);
    }

    .related-item img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 15px;
    }

    @media (max-width: 768px) {
      .product-header {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .tab-buttons {
        flex-wrap: wrap;
      }

      .tab-button {
        flex: 1;
        min-width: 120px;
      }

      .quantity-section {
        justify-content: center;
      }
    }
  </style>
</head>

<body>
  <a href="menu.php" class="back-link">
    <i class="fas fa-arrow-left"></i> Back to Menu
  </a>

  <div class="product-detail-container">
    <?php if (isset($success_message)): ?>
      <div class="success-message">
        <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
      </div>
    <?php endif; ?>

    <div class="product-header">
      <div class="product-image-section">
        <img src="<?php echo $product['image']; ?>"
          alt="<?php echo htmlspecialchars($product['name']); ?>"
          class="product-main-image"
          onerror="this.src='https://via.placeholder.com/500x400/6F4E37/FFFFFF?text=Premium+Cappuccino'">

        <div class="rating-section">
          <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
          </div>
          <p>4.8/5 (124 reviews)</p>
        </div>
      </div>

      <div class="product-info">
        <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
        <div class="product-price">From RM <?php echo number_format(min($product['sizes']), 2); ?></div>
        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>

        <!-- Product Details Tabs -->
        <div class="product-details-tabs">
          <div class="tab-buttons">
            <button class="tab-button active" onclick="showTab('ingredients')">Ingredients</button>
            <button class="tab-button" onclick="showTab('nutrition')">Nutrition</button>
            <button class="tab-button" onclick="showTab('reviews')">Reviews</button>
          </div>

          <div id="ingredients" class="tab-content active">
            <ul class="ingredients-list">
              <?php foreach ($product['ingredients'] as $ingredient): ?>
                <li><i class="fas fa-leaf"></i> <?php echo htmlspecialchars($ingredient); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>

          <div id="nutrition" class="tab-content">
            <ul class="nutritional-list">
              <?php foreach ($product['nutritional_info'] as $nutrient => $value): ?>
                <li><strong><?php echo $nutrient; ?>:</strong> <?php echo $value; ?></li>
              <?php endforeach; ?>
            </ul>
          </div>

          <div id="reviews" class="tab-content">
            <div style="text-align: center; padding: 20px;">
              <p><strong>Customer Reviews</strong></p>
              <p>"The perfect cappuccino! Creamy foam and rich espresso taste." - Sarah M.</p>
              <p>"My daily go-to drink. Consistently excellent quality." - Ahmad K.</p>
              <p>"Love the smooth texture and balanced flavor." - Lisa T.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Form -->
    <form method="post" class="order-form">
      <h3><i class="fas fa-shopping-cart"></i> Customize Your Order</h3>

      <!-- Size Selection -->
      <div class="form-section">
        <h4>Choose Size:</h4>
        <div class="size-options">
          <?php foreach ($product['sizes'] as $size => $price): ?>
            <label class="size-option">
              <input type="radio" name="size" value="<?php echo $size; ?>"
                <?php echo $size === 'Medium (12oz)' ? 'checked' : ''; ?>>
              <span><?php echo $size; ?> - RM <?php echo number_format($price, 2); ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Customizations -->
      <div class="form-section">
        <h4>Customizations (Optional):</h4>
        <div class="customization-options">
          <?php foreach ($product['customizations'] as $custom): ?>
            <label class="customization-option">
              <input type="checkbox" name="customizations[]" value="<?php echo $custom; ?>">
              <span><?php echo $custom; ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Quantity -->
      <div class="form-section">
        <h4>Quantity:</h4>
        <div class="quantity-section">
          <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">-</button>
          <input type="number" name="quantity" id="quantity" value="1" min="1" max="10" class="quantity-input">
          <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
        </div>
      </div>

      <button type="submit" name="add_to_cart" class="add-to-cart-btn">
        <i class="fas fa-plus"></i> Add to Cart
      </button>
    </form>

    <!-- Related Products -->
    <div class="related-products">
      <h3>You Might Also Like</h3>
      <div class="related-grid">
        <div class="related-item">
          <img src="https://via.placeholder.com/250x150/8B4513/FFFFFF?text=Latte" alt="Latte">
          <h4>Premium Latte</h4>
          <p>RM 11.90</p>
          <a href="product_details.php?id=1" class="btn">View Details</a>
        </div>
        <div class="related-item">
          <img src="https://via.placeholder.com/250x150/6F4E37/FFFFFF?text=Americano" alt="Americano">
          <h4>Classic Americano</h4>
          <p>RM 9.90</p>
          <a href="product_details.php?id=3" class="btn">View Details</a>
        </div>
        <div class="related-item">
          <img src="https://via.placeholder.com/250x150/A0522D/FFFFFF?text=Mocha" alt="Mocha">
          <h4>Chocolate Mocha</h4>
          <p>RM 13.90</p>
          <a href="product_details.php?id=4" class="btn">View Details</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    function showTab(tabName) {
      // Hide all tab contents
      const tabContents = document.querySelectorAll('.tab-content');
      tabContents.forEach(content => content.classList.remove('active'));

      // Remove active class from all buttons
      const tabButtons = document.querySelectorAll('.tab-button');
      tabButtons.forEach(button => button.classList.remove('active'));

      // Show selected tab content
      document.getElementById(tabName).classList.add('active');

      // Add active class to clicked button
      event.target.classList.add('active');
    }

    function changeQuantity(change) {
      const quantityInput = document.getElementById('quantity');
      let currentValue = parseInt(quantityInput.value);
      let newValue = currentValue + change;

      if (newValue >= 1 && newValue <= 10) {
        quantityInput.value = newValue;
      }
    }

    // Add animation on load
    document.addEventListener('DOMContentLoaded', function() {
      const container = document.querySelector('.product-detail-container');
      container.style.opacity = '0';
      container.style.transform = 'translateY(30px)';

      setTimeout(() => {
        container.style.transition = 'all 0.8s ease';
        container.style.opacity = '1';
        container.style.transform = 'translateY(0)';
      }, 100);
    });
  </script>
</body>

</html>