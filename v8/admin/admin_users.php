<?php
// admin_users.php - User Management
session_start();
require_once '../functions/functions.php';

// Check admin authentication
checkAdminAuth();

// Get all users
$users = getAllUsersAdmin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Management - Coffee's Life</title>
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="admin-body">
  <div class="admin-container">
    <!-- Admin Header -->
    <div class="admin-header">
      <h1><i class="fas fa-users"></i><span style="color:white"> User Management</span></h1>
    </div>

    <!-- Navigation -->
    <nav class="admin-nav">
      <ul>
        <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="admin_products.php"><i class="fas fa-coffee"></i> Products</a></li>
        <!-- <li><a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li> -->
        <li><a href="admin_users.php" class="active"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul>
    </nav>

    <!-- User Statistics -->
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 30px;">
      <div class="stat-card">
        <div class="stat-number"><?php echo count($users); ?></div>
        <div class="stat-label">
          <i class="fas fa-users"></i> Total Users
        </div>
      </div>
      <!-- Users with Orders card temporarily disabled - orders table not implemented
      <div class="stat-card">
        <div class="stat-number"><?php echo count(array_filter($users, fn($u) => $u['order_count'] > 0)); ?></div>
        <div class="stat-label">
          <i class="fas fa-shopping-cart"></i> Users with Orders
        </div>
      </div>
      -->
      <div class="stat-card">
        <div class="stat-number"><?php echo count(array_filter($users, fn($u) => $u['review_count'] > 0)); ?></div>
        <div class="stat-label">
          <i class="fas fa-star"></i> Users with Reviews
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?php echo count(array_filter($users, fn($u) => strtotime($u['created_at']) > strtotime('-7 days'))); ?></div>
        <div class="stat-label">
          <i class="fas fa-user-plus"></i> New Users (7 days)
        </div>
      </div>
    </div>

    <!-- Users List -->
    <div class="admin-card">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2><i class="fas fa-list"></i> All Registered Users (<?php echo count($users); ?>)</h2>
        <div style="color: #666; font-size: 0.9rem;">
          <i class="fas fa-info-circle"></i> Showing user activity and statistics
        </div>
      </div>

      <?php if (empty($users)): ?>
        <p>No users found.</p>
      <?php else: ?>
        <table class="admin-table">
          <thead>
            <tr>
              <th>User Info</th>
              <th>Contact Details</th>
              <th>Activity</th>
              <th>Registration Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user): ?>
              <tr>
                <td>
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 40px; height: 40px; background: #6f4e37; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                      <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                    </div>
                    <div>
                      <strong><?php echo htmlspecialchars($user['username']); ?></strong><br>
                      <small style="color: #666;">ID: <?php echo $user['id']; ?></small>
                    </div>
                  </div>
                </td>
                <td>
                  <div>
                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?><br>
                    <i class="fas fa-phone"></i> <?php echo htmlspecialchars($user['contact_number']); ?><br>
                    <?php if ($user['address']): ?>
                      <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars(substr($user['address'], 0, 30)) . (strlen($user['address']) > 30 ? '...' : ''); ?>
                    <?php endif; ?>
                  </div>
                </td>
                <td>
                  <div style="display: flex; gap: 15px;">
                    <!-- Orders temporarily disabled - orders table not implemented
                    <div style="text-align: center;">
                      <div style="font-size: 1.2rem; font-weight: bold; color: #6f4e37;"><?php echo $user['order_count']; ?></div>
                      <div style="font-size: 0.8rem; color: #666;">Orders</div>
                    </div>
                    -->
                    <div style="text-align: center;">
                      <div style="font-size: 1.2rem; font-weight: bold; color: #ffc107;"><?php echo $user['review_count']; ?></div>
                      <div style="font-size: 0.8rem; color: #666;">Reviews</div>
                    </div>
                  </div>
                </td>
                <td>
                  <?php echo date('M j, Y', strtotime($user['created_at'])); ?><br>
                  <small style="color: #666;">
                    <?php
                    $days_ago = floor((time() - strtotime($user['created_at'])) / 86400);
                    echo $days_ago === 0 ? 'Today' : $days_ago . ' days ago';
                    ?>
                  </small>
                </td>
                <td>
                  <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Active
                  </span>
                  <br>
                  <?php if ($user['order_count'] > 0): ?>
                    <small style="color: #28a745;"><i class="fas fa-shopping-cart"></i> Customer</small>
                  <?php elseif ($user['review_count'] > 0): ?>
                    <small style="color: #ffc107;"><i class="fas fa-star"></i> Reviewer</small>
                  <?php else: ?>
                    <small style="color: #6c757d;"><i class="fas fa-user"></i> New User</small>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

    <!-- User Insights -->
    <div class="admin-card">
      <h2><i class="fas fa-chart-bar"></i> User Insights</h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <!-- Most Active Users -->
        <div>
          <h3 style="color: #6f4e37; border-bottom: 2px solid #6f4e37; padding-bottom: 10px;">
            <i class="fas fa-trophy"></i> Most Active Users
          </h3>
          <!-- Most Active Users - temporarily disabled as orders table not implemented
          <?php
          $active_users = array_slice(
            array_filter($users, fn($u) => $u['order_count'] > 0),
            0,
            5
          );
          usort($active_users, fn($a, $b) => $b['order_count'] - $a['order_count']);
          ?>
          <?php if (empty($active_users)): ?>
            <p style="color: #666; font-style: italic;">No orders yet</p>
          <?php else: ?>
            <?php foreach ($active_users as $user): ?>
              <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;">
                <span><?php echo htmlspecialchars($user['username']); ?></span>
                <span style="font-weight: bold; color: #6f4e37;"><?php echo $user['order_count']; ?> orders</span>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
          -->
          <p style="color: #666; font-style: italic;">Orders functionality not yet implemented</p>
        </div>

        <!-- Recent Registrations -->
        <div>
          <h3 style="color: #28a745; border-bottom: 2px solid #28a745; padding-bottom: 10px;">
            <i class="fas fa-user-plus"></i> Recent Registrations
          </h3>
          <?php
          $recent_users = array_slice($users, 0, 5);
          ?>
          <?php foreach ($recent_users as $user): ?>
            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;">
              <span><?php echo htmlspecialchars($user['username']); ?></span>
              <span style="color: #666; font-size: 0.9rem;">
                <?php
                $days_ago = floor((time() - strtotime($user['created_at'])) / 86400);
                echo $days_ago === 0 ? 'Today' : $days_ago . 'd ago';
                ?>
              </span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Auto-refresh every 5 minutes
    setTimeout(function() {
      location.reload();
    }, 300000);
  </script>
</body>

</html>
