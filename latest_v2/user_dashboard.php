<?php
// user_dashboard.php
require_once 'functions.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
  header("Location: user_login.php");
  exit;
}

// Get user information
$username = $_SESSION['username'] ?? 'Guest';
$user_id = $_SESSION['user_id'] ?? 0;

// Get recent orders (mock data for now)
$recent_orders = [
  ['id' => 1, 'date' => '2024-12-20', 'total' => 25.50, 'status' => 'Delivered'],
  ['id' => 2, 'date' => '2024-12-18', 'total' => 18.75, 'status' => 'Processing'],
  ['id' => 3, 'date' => '2024-12-15', 'total' => 32.00, 'status' => 'Delivered']
];

// Get favorite products
$featured_products = array_slice(getProducts(), 0, 3);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Beans Cafe</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #6F4E37 0%, #8B4513 100%);
      margin: 0;
      padding: 0;
      min-height: 100vh;
    }

    .dashboard-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .dashboard-header {
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 15px;
      margin-bottom: 30px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      text-align: center;
      background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="coffee" patternUnits="userSpaceOnUse" width="20" height="20"><circle cx="10" cy="10" r="2" fill="%23f0f0f0"/></pattern></defs><rect width="100" height="100" fill="url(%23coffee)"/></svg>');
    }

    .welcome-message {
      font-size: 2.5em;
      color: #6F4E37;
      margin-bottom: 10px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }

    .user-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border-left: 5px solid #6F4E37;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
      font-size: 3em;
      color: #6F4E37;
      margin-bottom: 15px;
    }

    .stat-number {
      font-size: 2.5em;
      font-weight: bold;
      color: #333;
      margin-bottom: 5px;
    }

    .stat-label {
      color: #666;
      font-size: 1.1em;
    }

    .dashboard-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 30px;
      margin-bottom: 30px;
    }

    .recent-orders,
    .quick-actions {
      background: white;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .section-title {
      font-size: 1.5em;
      color: #6F4E37;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
    }

    .section-title i {
      margin-right: 10px;
    }

    .order-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 0;
      border-bottom: 1px solid #eee;
    }

    .order-item:last-child {
      border-bottom: none;
    }

    .order-info {
      flex: 1;
    }

    .order-id {
      font-weight: bold;
      color: #333;
    }

    .order-date {
      color: #666;
      font-size: 0.9em;
    }

    .order-total {
      font-weight: bold;
      color: #6F4E37;
      font-size: 1.1em;
    }

    .order-status {
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.8em;
      font-weight: bold;
    }

    .status-delivered {
      background: #d4edda;
      color: #155724;
    }

    .status-processing {
      background: #fff3cd;
      color: #856404;
    }

    .action-button {
      display: block;
      background: linear-gradient(135deg, #6F4E37, #8B4513);
      color: white;
      padding: 15px 20px;
      text-decoration: none;
      border-radius: 10px;
      margin-bottom: 15px;
      text-align: center;
      transition: transform 0.3s ease;
      font-weight: bold;
    }

    .action-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(111, 78, 55, 0.3);
    }

    .featured-products {
      background: white;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
    }

    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }

    .product-card {
      text-align: center;
      padding: 20px;
      border: 2px solid #f0f0f0;
      border-radius: 10px;
      transition: border-color 0.3s ease;
    }

    .product-card:hover {
      border-color: #6F4E37;
    }

    .product-image {
      width: 100px;
      height: 100px;
      background: #f0f0f0;
      border-radius: 50%;
      margin: 0 auto 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2em;
      color: #6F4E37;
    }

    .navigation-bar {
      background: rgba(255, 255, 255, 0.95);
      padding: 15px 0;
      border-radius: 10px;
      margin-bottom: 20px;
      text-align: center;
    }

    .nav-link {
      display: inline-block;
      margin: 0 15px;
      padding: 10px 20px;
      background: #6F4E37;
      color: white;
      text-decoration: none;
      border-radius: 25px;
      transition: background-color 0.3s ease;
      font-weight: bold;
    }

    .nav-link:hover {
      background: #8B4513;
    }

    @media (max-width: 768px) {
      .dashboard-grid {
        grid-template-columns: 1fr;
      }

      .user-stats {
        grid-template-columns: 1fr;
      }

      .products-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Animation classes */
    .fade-in {
      animation: fadeIn 0.8s ease-in;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .bounce {
      animation: bounce 2s infinite;
    }

    @keyframes bounce {

      0%,
      20%,
      50%,
      80%,
      100% {
        transform: translateY(0);
      }

      40% {
        transform: translateY(-10px);
      }

      60% {
        transform: translateY(-5px);
      }
    }
  </style>
</head>

<body>
  <div class="dashboard-container">
    <!-- Navigation Bar -->
    <div class="navigation-bar fade-in">
      <a href="menu.php" class="nav-link"><i class="fas fa-coffee"></i> Browse Menu</a>
      <a href="view_cart.php" class="nav-link"><i class="fas fa-shopping-cart"></i> View Cart</a>
      <a href="contact_us.php" class="nav-link"><i class="fas fa-envelope"></i> Contact Us</a>
      <a href="user_logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Welcome Header -->
    <div class="dashboard-header fade-in">
      <div class="welcome-message bounce">
        ☕ Welcome back, <?php echo htmlspecialchars($username); ?>!
      </div>
      <p style="font-size: 1.2em; color: #666; margin: 0;">
        Ready for your next coffee adventure?
      </p>
    </div>

    <!-- User Statistics -->
    <div class="user-stats">
      <div class="stat-card fade-in" style="animation-delay: 0.2s;">
        <div class="stat-icon bounce">
          <i class="fas fa-shopping-bag"></i>
        </div>
        <div class="stat-number"><?php echo count($recent_orders); ?></div>
        <div class="stat-label">Total Orders</div>
      </div>

      <div class="stat-card fade-in" style="animation-delay: 0.4s;">
        <div class="stat-icon bounce">
          <i class="fas fa-heart"></i>
        </div>
        <div class="stat-number">12</div>
        <div class="stat-label">Favorite Items</div>
      </div>

      <div class="stat-card fade-in" style="animation-delay: 0.6s;">
        <div class="stat-icon bounce">
          <i class="fas fa-star"></i>
        </div>
        <div class="stat-number">4.8</div>
        <div class="stat-label">Average Rating</div>
      </div>

      <div class="stat-card fade-in" style="animation-delay: 0.8s;">
        <div class="stat-icon bounce">
          <i class="fas fa-coins"></i>
        </div>
        <div class="stat-number">250</div>
        <div class="stat-label">Loyalty Points</div>
      </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="dashboard-grid">
      <!-- Recent Orders -->
      <div class="recent-orders fade-in" style="animation-delay: 1s;">
        <h2 class="section-title">
          <i class="fas fa-history"></i>
          Recent Orders
        </h2>

        <?php if (empty($recent_orders)): ?>
          <p style="text-align: center; color: #666; padding: 20px;">
            No orders yet. <a href="menu.php" style="color: #6F4E37;">Start shopping!</a>
          </p>
        <?php else: ?>
          <?php foreach ($recent_orders as $order): ?>
            <div class="order-item">
              <div class="order-info">
                <div class="order-id">Order #<?php echo $order['id']; ?></div>
                <div class="order-date"><?php echo date('M j, Y', strtotime($order['date'])); ?></div>
              </div>
              <div class="order-total">$<?php echo number_format($order['total'], 2); ?></div>
              <div class="order-status <?php echo 'status-' . strtolower($order['status']); ?>">
                <?php echo $order['status']; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 20px;">
          <a href="#" class="action-button">View All Orders</a>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="quick-actions fade-in" style="animation-delay: 1.2s;">
        <h2 class="section-title">
          <i class="fas fa-bolt"></i>
          Quick Actions
        </h2>

        <a href="menu.php" class="action-button">
          <i class="fas fa-coffee"></i> Browse Menu
        </a>

        <a href="view_cart.php" class="action-button">
          <i class="fas fa-shopping-cart"></i> View Cart
        </a>

        <a href="#" class="action-button">
          <i class="fas fa-redo"></i> Reorder Favorites
        </a>

        <a href="contact_us.php" class="action-button">
          <i class="fas fa-headset"></i> Customer Support
        </a>

        <a href="#" class="action-button">
          <i class="fas fa-user-edit"></i> Update Profile
        </a>
      </div>
    </div>

    <!-- Featured Products -->
    <div class="featured-products fade-in" style="animation-delay: 1.4s;">
      <h2 class="section-title">
        <i class="fas fa-star"></i>
        Recommended for You
      </h2>

      <div class="products-grid">
        <?php foreach ($featured_products as $product): ?>
          <div class="product-card">
            <div class="product-image">
              <i class="fas fa-coffee"></i>
            </div>
            <h3 style="color: #6F4E37; margin-bottom: 10px;">
              <?php echo htmlspecialchars($product['name']); ?>
            </h3>
            <p style="color: #666; font-size: 0.9em; margin-bottom: 15px;">
              <?php echo htmlspecialchars(substr($product['description'], 0, 60)) . '...'; ?>
            </p>
            <div style="font-weight: bold; color: #6F4E37; font-size: 1.2em; margin-bottom: 15px;">
              $<?php echo number_format($product['price'], 2); ?>
            </div>
            <a href="product_details.php?id=<?php echo $product['id']; ?>"
              style="background: #6F4E37; color: white; padding: 8px 16px; text-decoration: none; border-radius: 5px; font-size: 0.9em;">
              View Details
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <script>
    // Add dynamic time-based greeting
    document.addEventListener('DOMContentLoaded', function() {
      const hour = new Date().getHours();
      let greeting = 'Welcome back';

      if (hour < 12) {
        greeting = 'Good morning';
      } else if (hour < 17) {
        greeting = 'Good afternoon';
      } else {
        greeting = 'Good evening';
      }

      const welcomeMessage = document.querySelector('.welcome-message');
      welcomeMessage.innerHTML = `☕ ${greeting}, <?php echo htmlspecialchars($username); ?>!`;

      // Add click animations to stat cards
      const statCards = document.querySelectorAll('.stat-card');
      statCards.forEach(card => {
        card.addEventListener('click', function() {
          this.style.transform = 'scale(0.95)';
          setTimeout(() => {
            this.style.transform = 'translateY(-5px)';
          }, 150);
        });
      });

      // Auto-refresh order status (simulation)
      setInterval(updateOrderStatus, 30000); // Every 30 seconds
    });

    function updateOrderStatus() {
      // Simulate real-time order updates
      console.log('Checking for order updates...');
    }

    // Add notification system
    function showNotification(message, type = 'info') {
      const notification = document.createElement('div');
      notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#d4edda' : '#f8d7da'};
                color: ${type === 'success' ? '#155724' : '#721c24'};
                padding: 15px 20px;
                border-radius: 5px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                z-index: 1000;
                animation: slideIn 0.5s ease;
            `;
      notification.textContent = message;
      document.body.appendChild(notification);

      setTimeout(() => {
        notification.remove();
      }, 5000);
    }

    // Show welcome notification
    setTimeout(() => {
      showNotification('Dashboard loaded successfully!', 'success');
    }, 1000);
  </script>
</body>

</html>