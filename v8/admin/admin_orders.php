<?php
// admin_orders.php - Order Management
session_start();
require_once '../functions/functions.php';

// Check admin authentication
checkAdminAuth();

// Get order details if viewing specific order
$viewing_order = null;
if (isset($_GET['view']) && is_numeric($_GET['view'])) {
  $viewing_order = getOrderDetails($_GET['view']);
}

// Get all orders
$orders = getAllOrdersAdmin(100);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Management - Coffee's Life</title>
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="admin-body">
  <div class="admin-container">
    <!-- Admin Header -->
    <div class="admin-header">
      <h1><i class="fas fa-shopping-cart"></i> Order Management</h1>
    </div>

    <!-- Navigation -->
    <nav class="admin-nav">
      <ul>
        <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="admin_products.php"><i class="fas fa-coffee"></i> Products</a></li>
        <li><a href="admin_orders.php" class="active"><i class="fas fa-shopping-cart"></i> Orders</a></li>
        <li><a href="admin_users.php"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul>
    </nav>

    <?php if ($viewing_order): ?>
      <!-- Order Details View -->
      <div class="admin-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
          <h2><i class="fas fa-receipt"></i> Order #<?php echo $viewing_order['id']; ?> Details</h2>
          <a href="admin_orders.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Orders
          </a>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
          <!-- Order Information -->
          <div>
            <h3><i class="fas fa-info-circle"></i> Order Information</h3>
            <table style="width: 100%; margin-top: 15px;">
              <tr>
                <td><strong>Order ID:</strong></td>
                <td>#<?php echo $viewing_order['id']; ?></td>
              </tr>
              <tr>
                <td><strong>Status:</strong></td>
                <td>
                  <span class="status-badge status-<?php echo $viewing_order['status']; ?>">
                    <?php echo ucfirst($viewing_order['status']); ?>
                  </span>
                </td>
              </tr>
              <tr>
                <td><strong>Total Amount:</strong></td>
                <td><strong>RM <?php echo number_format($viewing_order['total_amount'], 2); ?></strong></td>
              </tr>
              <tr>
                <td><strong>Order Date:</strong></td>
                <td><?php echo date('F j, Y g:i A', strtotime($viewing_order['created_at'])); ?></td>
              </tr>
            </table>
          </div>

          <!-- Customer Information -->
          <div>
            <h3><i class="fas fa-user"></i> Customer Information</h3>
            <table style="width: 100%; margin-top: 15px;">
              <?php if ($viewing_order['user_id']): ?>
                <tr>
                  <td><strong>Customer Type:</strong></td>
                  <td><i class="fas fa-user"></i> Registered User</td>
                </tr>
                <tr>
                  <td><strong>Username:</strong></td>
                  <td><?php echo htmlspecialchars($viewing_order['username']); ?></td>
                </tr>
              <?php else: ?>
                <tr>
                  <td><strong>Customer Type:</strong></td>
                  <td><i class="fas fa-user-secret"></i> Guest</td>
                </tr>
                <tr>
                  <td><strong>Name:</strong></td>
                  <td><?php echo htmlspecialchars($viewing_order['guest_name']); ?></td>
                </tr>
                <tr>
                  <td><strong>Phone:</strong></td>
                  <td><?php echo htmlspecialchars($viewing_order['guest_phone']); ?></td>
                </tr>
              <?php endif; ?>
            </table>
          </div>
        </div>

        <!-- Order Items -->
        <h3><i class="fas fa-list"></i> Order Items</h3>
        <table class="admin-table" style="margin-top: 15px;">
          <thead>
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Customizations</th>
              <th>Item Total</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($viewing_order['items'] as $item): ?>
              <tr>
                <td>
                  <strong><?php echo htmlspecialchars($item['product_name']); ?></strong><br>
                  <small>Product ID: <?php echo $item['product_id']; ?></small>
                </td>
                <td>RM <?php echo number_format($item['product_price'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>
                  <?php if ($item['sugar_level'] || $item['milk_type'] || $item['special_instructions']): ?>
                    <?php if ($item['sugar_level']): ?>
                      <div><strong>Sugar:</strong> <?php echo htmlspecialchars($item['sugar_level']); ?></div>
                    <?php endif; ?>
                    <?php if ($item['milk_type']): ?>
                      <div><strong>Milk:</strong> <?php echo htmlspecialchars($item['milk_type']); ?></div>
                    <?php endif; ?>
                    <?php if ($item['special_instructions']): ?>
                      <div><strong>Notes:</strong> <?php echo htmlspecialchars($item['special_instructions']); ?></div>
                    <?php endif; ?>
                  <?php else: ?>
                    <em>No customizations</em>
                  <?php endif; ?>
                </td>
                <td><strong>RM <?php echo number_format($item['item_total'], 2); ?></strong></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr style="background: #f8f6f3; font-weight: bold;">
              <td colspan="4" style="text-align: right;">Total Amount:</td>
              <td><strong>RM <?php echo number_format($viewing_order['total_amount'], 2); ?></strong></td>
            </tr>
          </tfoot>
        </table>
      </div>
    <?php else: ?>
      <!-- Orders List -->
      <div class="admin-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
          <h2><i class="fas fa-list"></i> All Orders (<?php echo count($orders); ?>)</h2>
          <div>
            <span class="status-badge status-pending">Pending: <?php echo count(array_filter($orders, fn($o) => $o['status'] === 'pending')); ?></span>
            <span class="status-badge status-completed">Completed: <?php echo count(array_filter($orders, fn($o) => $o['status'] === 'completed')); ?></span>
          </div>
        </div>

        <?php if (empty($orders)): ?>
          <p>No orders found.</p>
        <?php else: ?>
          <table class="admin-table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $order): ?>
                <tr>
                  <td>
                    <strong>#<?php echo $order['id']; ?></strong>
                  </td>
                  <td>
                    <?php if ($order['user_id']): ?>
                      <i class="fas fa-user" style="color: #28a745;"></i>
                      <?php echo htmlspecialchars($order['username']); ?>
                    <?php else: ?>
                      <i class="fas fa-user-secret" style="color: #6c757d;"></i>
                      <?php echo htmlspecialchars($order['guest_name']); ?>
                      <br><small style="color: #666;"><?php echo htmlspecialchars($order['guest_phone']); ?></small>
                    <?php endif; ?>
                  </td>
                  <td>
                    <span style="background: #e9ecef; padding: 3px 8px; border-radius: 12px; font-size: 0.85rem;">
                      <?php echo $order['item_count']; ?> items
                    </span>
                  </td>
                  <td><strong>RM <?php echo number_format($order['total_amount'], 2); ?></strong></td>
                  <td>
                    <span class="status-badge status-<?php echo $order['status']; ?>">
                      <?php echo ucfirst($order['status']); ?>
                    </span>
                  </td>
                  <td>
                    <?php echo date('M j, Y', strtotime($order['created_at'])); ?><br>
                    <small style="color: #666;"><?php echo date('g:i A', strtotime($order['created_at'])); ?></small>
                  </td>
                  <td>
                    <a href="admin_orders.php?view=<?php echo $order['id']; ?>" class="btn btn-small btn-primary">
                      <i class="fas fa-eye"></i> View Details
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <script>
    // Auto-refresh orders list every 2 minutes (only on list view)
    <?php if (!$viewing_order): ?>
      setTimeout(function() {
        location.reload();
      }, 120000);
    <?php endif; ?>
  </script>
</body>

</html>
