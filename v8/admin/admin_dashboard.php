<?php
// admin_dashboard.php - Main Admin Dashboard
session_start();
require_once '../functions/functions.php';

// Check admin authentication
checkAdminAuth();

// Get dashboard statistics
$stats = getAdminStats();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Coffee's Life</title>
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="admin-body">
  <div class="admin-container">
    <!-- Admin Header -->
    <div class="admin-header">
      <h1><i class="fas fa-tachometer-alt"></i> <span style="color:white">Admin Dashboard</span></h1>
      <p><span style="color:white">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</span></p>
    </div>

    <!-- Navigation -->
    <nav class="admin-nav">
      <ul>
        <li><a href="admin_dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="admin_products.php"><i class="fas fa-coffee"></i> Products</a></li>
        <!-- <li><a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li> -->
        <li><a href="admin_users.php"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="admin_admins.php"><i class="fas fa-user-shield"></i> Admins</a></li>
        <li><a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <li><a href="../UI/UI1_index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a></li>
      </ul>
    </nav>

    <!-- Statistics Cards -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-number"><?php echo $stats['total_products']; ?></div>
        <div class="stat-label">
          <i class="fas fa-coffee"></i> Total Products
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?php echo $stats['low_stock']; ?></div>
        <div class="stat-label">
          <i class="fas fa-exclamation-triangle"></i> Low Stock
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?php echo $stats['out_of_stock']; ?></div>
        <div class="stat-label">
          <i class="fas fa-times-circle"></i> Out of Stock
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?php echo $stats['total_admins']; ?></div>
        <div class="stat-label">
          <i class="fas fa-user-shield"></i> Admin Accounts
        </div>
      </div>
    </div>

    <!-- Product Overview -->
    <div class="admin-card">
      <h2><i class="fas fa-chart-bar"></i> Product Overview</h2>
      <div class="product-overview">
        <div class="overview-item">
          <h3>Total Products</h3>
          <p><?php echo $stats['total_products']; ?> items in database</p>
        </div>
        <div class="overview-item">
          <h3>Stock Status</h3>
          <p><?php echo $stats['low_stock']; ?> items need restocking</p>
          <p><?php echo $stats['out_of_stock']; ?> items out of stock</p>
        </div>
        <div class="overview-item">
          <h3>Quick Actions</h3>
          <a href="admin_products.php?action=add" class="btn btn-primary">Add New Product</a>
          <a href="admin_products.php" class="btn btn-secondary">Manage Products</a>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="admin-card">
      <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <a href="admin_products.php?action=add" class="btn btn-success">
          <i class="fas fa-plus"></i> Add New Product
        </a>
        <a href="admin_products.php" class="btn btn-primary">
          <i class="fas fa-coffee"></i> Manage Products
        </a>
        <a href="admin_admins.php" class="btn btn-secondary">
          <i class="fas fa-user-shield"></i> Manage Admins
        </a>
        <a href="../UI/UI1_index.php" target="_blank" class="btn btn-primary">
          <i class="fas fa-external-link-alt"></i> View Website
        </a>
      </div>
    </div>
  </div>

  <script>
    // Auto-refresh stats every 5 minutes
    setTimeout(function() {
      location.reload();
    }, 300000);
  </script>
</body>

</html>