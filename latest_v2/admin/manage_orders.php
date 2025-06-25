<?php
// admin/manage_orders.php
require_once '../functions.php';
checkAdminAuth(); // Check if admin is logged in

$action = isset($_GET['action']) ? $_GET['action'] : 'view';
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Mock order data (in real app, this would come from database)
$orders = [
  ['id' => 1, 'customer_name' => 'John Doe', 'customer_email' => 'john@gmail.com', 'order_date' => '2024-12-20 14:30:00', 'items' => 'Espresso x2, Croissant x1', 'total_amount' => 25.50, 'status' => 'Completed', 'payment_method' => 'Credit Card'],
  ['id' => 2, 'customer_name' => 'Jane Smith', 'customer_email' => 'jane@gmail.com', 'order_date' => '2024-12-20 15:45:00', 'items' => 'Latte x1, Muffin x2', 'total_amount' => 18.75, 'status' => 'Processing', 'payment_method' => 'Cash'],
  ['id' => 3, 'customer_name' => 'Mike Brown', 'customer_email' => 'mike@gmail.com', 'order_date' => '2024-12-20 16:20:00', 'items' => 'Cappuccino x1, Cake x1', 'total_amount' => 32.00, 'status' => 'Pending', 'payment_method' => 'Online Banking'],
  ['id' => 4, 'customer_name' => 'Sarah Wilson', 'customer_email' => 'sarah@gmail.com', 'order_date' => '2024-12-19 11:15:00', 'items' => 'Americano x3, Sandwich x2', 'total_amount' => 45.25, 'status' => 'Completed', 'payment_method' => 'Credit Card'],
  ['id' => 5, 'customer_name' => 'David Lee', 'customer_email' => 'david@gmail.com', 'order_date' => '2024-12-19 13:30:00', 'items' => 'Mocha x1, Cookie x3', 'total_amount' => 28.50, 'status' => 'Cancelled', 'payment_method' => 'Cash']
];

$success_msg = $error_msg = "";

// Handle status updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
  $order_id = (int)$_POST['order_id'];
  $new_status = $_POST['status'];

  // In real app, update database
  $success_msg = "Order #$order_id status updated to $new_status successfully!";
}

// Calculate statistics
$total_orders = count($orders);
$pending_orders = count(array_filter($orders, function ($o) {
  return $o['status'] == 'Pending';
}));
$processing_orders = count(array_filter($orders, function ($o) {
  return $o['status'] == 'Processing';
}));
$completed_orders = count(array_filter($orders, function ($o) {
  return $o['status'] == 'Completed';
}));
$total_revenue = array_sum(array_column(array_filter($orders, function ($o) {
  return $o['status'] == 'Completed';
}), 'total_amount'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Orders - Admin Dashboard</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #6F4E37 0%, #8B4513 100%);
      margin: 0;
      padding: 0;
      min-height: 100vh;
    }

    .admin-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 20px;
    }

    .admin-header {
      background: rgba(255, 255, 255, 0.95);
      padding: 20px;
      border-radius: 15px;
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .admin-nav {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }

    .nav-btn {
      background: #6F4E37;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s;
      font-weight: bold;
    }

    .nav-btn:hover,
    .nav-btn.active {
      background: #8B4513;
    }

    .content-section {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .section-title {
      font-size: 1.8em;
      color: #6F4E37;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
    }

    .section-title i {
      margin-right: 10px;
    }

    .order-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: linear-gradient(135deg, #fff, #f8f9fa);
      padding: 25px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
      border-left: 4px solid #6F4E37;
      position: relative;
      overflow: hidden;
    }

    .stat-card.pending {
      border-left-color: #ffc107;
    }

    .stat-card.processing {
      border-left-color: #007bff;
    }

    .stat-card.completed {
      border-left-color: #28a745;
    }

    .stat-card.revenue {
      border-left-color: #6f42c1;
    }

    .stat-number {
      font-size: 2.2em;
      font-weight: bold;
      color: #6F4E37;
      margin-bottom: 5px;
    }

    .stat-label {
      color: #666;
      font-weight: 500;
    }

    .stat-icon {
      position: absolute;
      top: 15px;
      right: 15px;
      font-size: 1.5em;
      color: rgba(111, 78, 55, 0.3);
    }

    .orders-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background: white;
    }

    .orders-table th,
    .orders-table td {
      padding: 15px 10px;
      text-align: left;
      border-bottom: 1px solid #dee2e6;
    }

    .orders-table th {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      font-weight: bold;
      color: #333;
      position: sticky;
      top: 0;
      cursor: pointer;
      user-select: none;
    }

    .orders-table th:hover {
      background: linear-gradient(135deg, #e9ecef, #dee2e6);
    }

    .orders-table tr:hover {
      background: #f8f9fa;
    }

    .status-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.85em;
      font-weight: bold;
      text-align: center;
      display: inline-block;
      min-width: 80px;
    }

    .status-pending {
      background: #fff3cd;
      color: #856404;
    }

    .status-processing {
      background: #cce5ff;
      color: #004085;
    }

    .status-completed {
      background: #d4edda;
      color: #155724;
    }

    .status-cancelled {
      background: #f8d7da;
      color: #721c24;
    }

    .btn {
      padding: 8px 15px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      display: inline-block;
      margin: 2px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 12px;
    }

    .btn-primary {
      background: linear-gradient(135deg, #007bff, #0056b3);
      color: white;
    }

    .btn-success {
      background: linear-gradient(135deg, #28a745, #1e7e34);
      color: white;
    }

    .btn-warning {
      background: linear-gradient(135deg, #ffc107, #e0a800);
      color: #212529;
    }

    .btn-danger {
      background: linear-gradient(135deg, #dc3545, #c82333);
      color: white;
    }

    .btn-info {
      background: linear-gradient(135deg, #17a2b8, #138496);
      color: white;
    }

    .btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    }

    .order-details {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 8px;
      border-left: 4px solid #6F4E37;
    }

    .order-items {
      font-size: 0.9em;
      color: #666;
      margin-bottom: 5px;
    }

    .order-amount {
      font-weight: bold;
      color: #28a745;
      font-size: 1.1em;
    }

    .filter-section {
      background: #f8f9fa;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      border: 1px solid #dee2e6;
    }

    .filter-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      align-items: end;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      color: #333;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }

    .quick-actions {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
      background-color: white;
      margin: 10% auto;
      padding: 30px;
      border-radius: 10px;
      width: 80%;
      max-width: 600px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover {
      color: #000;
    }

    .alert {
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
    }

    .alert-success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    @media (max-width: 768px) {
      .admin-header {
        flex-direction: column;
        gap: 15px;
      }

      .order-stats {
        grid-template-columns: repeat(2, 1fr);
      }

      .filter-grid {
        grid-template-columns: 1fr;
      }

      .orders-table {
        font-size: 0.8em;
      }

      .quick-actions {
        justify-content: center;
      }
    }

    .fade-in {
      animation: fadeIn 0.5s ease-in;
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

    .pulse {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.05);
      }

      100% {
        transform: scale(1);
      }
    }
  </style>
</head>

<body>
  <div class="admin-container">
    <!-- Admin Header -->
    <div class="admin-header fade-in">
      <h1><i class="fas fa-shopping-cart"></i> Order Management</h1>
      <div class="admin-nav">
        <a href="dashboard.php" class="nav-btn"><i class="fas fa-dashboard"></i> Dashboard</a>
        <a href="manage_staff.php" class="nav-btn"><i class="fas fa-users"></i> Staff</a>
        <a href="manage_members.php" class="nav-btn"><i class="fas fa-user-friends"></i> Members</a>
        <a href="manage_orders.php" class="nav-btn active"><i class="fas fa-shopping-cart"></i> Orders</a>
        <a href="sales_report.php" class="nav-btn"><i class="fas fa-chart-bar"></i> Reports</a>
        <a href="logout.php" class="nav-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>

    <!-- Order Statistics -->
    <div class="order-stats fade-in">
      <div class="stat-card pulse">
        <i class="fas fa-shopping-bag stat-icon"></i>
        <div class="stat-number"><?php echo $total_orders; ?></div>
        <div class="stat-label">Total Orders</div>
      </div>
      <div class="stat-card pending">
        <i class="fas fa-clock stat-icon"></i>
        <div class="stat-number"><?php echo $pending_orders; ?></div>
        <div class="stat-label">Pending Orders</div>
      </div>
      <div class="stat-card processing">
        <i class="fas fa-cog stat-icon"></i>
        <div class="stat-number"><?php echo $processing_orders; ?></div>
        <div class="stat-label">Processing</div>
      </div>
      <div class="stat-card completed">
        <i class="fas fa-check-circle stat-icon"></i>
        <div class="stat-number"><?php echo $completed_orders; ?></div>
        <div class="stat-label">Completed</div>
      </div>
      <div class="stat-card revenue">
        <i class="fas fa-dollar-sign stat-icon"></i>
        <div class="stat-number">$<?php echo number_format($total_revenue, 0); ?></div>
        <div class="stat-label">Revenue</div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="content-section fade-in">
      <div class="quick-actions">
        <button onclick="refreshOrders()" class="btn btn-primary">
          <i class="fas fa-sync"></i> Refresh Orders
        </button>
        <button onclick="exportOrders()" class="btn btn-success">
          <i class="fas fa-download"></i> Export Orders
        </button>
        <button onclick="printOrders()" class="btn btn-info">
          <i class="fas fa-print"></i> Print Orders
        </button>
        <button onclick="showNewOrderModal()" class="btn btn-warning">
          <i class="fas fa-plus"></i> Add New Order
        </button>
      </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section fade-in">
      <h3><i class="fas fa-filter"></i> Filter & Search Orders</h3>
      <div class="filter-grid">
        <div class="form-group">
          <label for="searchOrders">Search Orders:</label>
          <input type="text" id="searchOrders" placeholder="Search by customer name, email, or order ID...">
        </div>
        <div class="form-group">
          <label for="filterStatus">Filter by Status:</label>
          <select id="filterStatus">
            <option value="">All Statuses</option>
            <option value="Pending">Pending</option>
            <option value="Processing">Processing</option>
            <option value="Completed">Completed</option>
            <option value="Cancelled">Cancelled</option>
          </select>
        </div>
        <div class="form-group">
          <label for="filterDate">Filter by Date:</label>
          <input type="date" id="filterDate">
        </div>
        <div class="form-group">
          <label for="filterPayment">Payment Method:</label>
          <select id="filterPayment">
            <option value="">All Methods</option>
            <option value="Credit Card">Credit Card</option>
            <option value="Cash">Cash</option>
            <option value="Online Banking">Online Banking</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Success Message -->
    <?php if (!empty($success_msg)): ?>
      <div class="alert alert-success fade-in"><?php echo $success_msg; ?></div>
    <?php endif; ?>

    <!-- Orders List -->
    <div class="content-section fade-in">
      <div class="section-title">
        <i class="fas fa-list"></i>
        Order Directory
      </div>

      <div style="overflow-x: auto;">
        <table class="orders-table" id="ordersTable">
          <thead>
            <tr>
              <th onclick="sortTable(0)">Order ID <i class="fas fa-sort"></i></th>
              <th onclick="sortTable(1)">Customer <i class="fas fa-sort"></i></th>
              <th onclick="sortTable(2)">Order Date <i class="fas fa-sort"></i></th>
              <th onclick="sortTable(3)">Items <i class="fas fa-sort"></i></th>
              <th onclick="sortTable(4)">Amount <i class="fas fa-sort"></i></th>
              <th onclick="sortTable(5)">Payment <i class="fas fa-sort"></i></th>
              <th onclick="sortTable(6)">Status <i class="fas fa-sort"></i></th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order): ?>
              <tr>
                <td><strong>#<?php echo $order['id']; ?></strong></td>
                <td>
                  <div>
                    <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong><br>
                    <small style="color: #666;"><?php echo htmlspecialchars($order['customer_email']); ?></small>
                  </div>
                </td>
                <td><?php echo date('M j, Y g:i A', strtotime($order['order_date'])); ?></td>
                <td>
                  <div class="order-items"><?php echo htmlspecialchars($order['items']); ?></div>
                </td>
                <td>
                  <div class="order-amount">$<?php echo number_format($order['total_amount'], 2); ?></div>
                </td>
                <td><?php echo $order['payment_method']; ?></td>
                <td>
                  <span class="status-badge <?php echo 'status-' . strtolower($order['status']); ?>">
                    <?php echo $order['status']; ?>
                  </span>
                </td>
                <td>
                  <button onclick="viewOrderDetails(<?php echo $order['id']; ?>)" class="btn btn-info">
                    <i class="fas fa-eye"></i> View
                  </button>
                  <button onclick="updateOrderStatus(<?php echo $order['id']; ?>, '<?php echo $order['status']; ?>')" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Update
                  </button>
                  <button onclick="printOrder(<?php echo $order['id']; ?>)" class="btn btn-primary">
                    <i class="fas fa-print"></i>
                  </button>
                  <button onclick="deleteOrder(<?php echo $order['id']; ?>)" class="btn btn-danger">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Update Status Modal -->
  <div id="updateStatusModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2><i class="fas fa-edit"></i> Update Order Status</h2>
      <form method="post" id="updateStatusForm">
        <input type="hidden" id="modalOrderId" name="order_id">
        <div class="form-group">
          <label for="modalStatus">New Status:</label>
          <select id="modalStatus" name="status" required>
            <option value="Pending">Pending</option>
            <option value="Processing">Processing</option>
            <option value="Completed">Completed</option>
            <option value="Cancelled">Cancelled</option>
          </select>
        </div>
        <div style="margin-top: 20px;">
          <button type="submit" name="update_status" class="btn btn-success">
            <i class="fas fa-save"></i> Update Status
          </button>
          <button type="button" onclick="closeModal()" class="btn btn-primary">
            <i class="fas fa-times"></i> Cancel
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Search and filter functionality
    document.getElementById('searchOrders').addEventListener('input', filterTable);
    document.getElementById('filterStatus').addEventListener('change', filterTable);
    document.getElementById('filterDate').addEventListener('change', filterTable);
    document.getElementById('filterPayment').addEventListener('change', filterTable);

    function filterTable() {
      const searchTerm = document.getElementById('searchOrders').value.toLowerCase();
      const statusFilter = document.getElementById('filterStatus').value;
      const dateFilter = document.getElementById('filterDate').value;
      const paymentFilter = document.getElementById('filterPayment').value;
      const table = document.getElementById('ordersTable');
      const rows = table.getElementsByTagName('tr');

      for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let showRow = true;

        // Search filter
        if (searchTerm) {
          const orderID = cells[0].textContent.toLowerCase();
          const customer = cells[1].textContent.toLowerCase();

          if (!orderID.includes(searchTerm) && !customer.includes(searchTerm)) {
            showRow = false;
          }
        }

        // Status filter
        if (statusFilter && !cells[6].textContent.includes(statusFilter)) {
          showRow = false;
        }

        // Payment filter
        if (paymentFilter && !cells[5].textContent.includes(paymentFilter)) {
          showRow = false;
        }

        row.style.display = showRow ? '' : 'none';
      }
    }

    // Sort functionality
    let sortDirection = {};

    function sortTable(columnIndex) {
      const table = document.getElementById('ordersTable');
      const tbody = table.getElementsByTagName('tbody')[0];
      const rows = Array.from(tbody.getElementsByTagName('tr'));

      sortDirection[columnIndex] = !sortDirection[columnIndex];
      const ascending = sortDirection[columnIndex];

      rows.sort((a, b) => {
        const aValue = a.getElementsByTagName('td')[columnIndex].textContent.trim();
        const bValue = b.getElementsByTagName('td')[columnIndex].textContent.trim();

        let comparison = 0;

        if (columnIndex === 0 || columnIndex === 4) { // ID, Amount
          const aNum = parseFloat(aValue.replace(/[^0-9.-]/g, ''));
          const bNum = parseFloat(bValue.replace(/[^0-9.-]/g, ''));
          comparison = aNum - bNum;
        } else if (columnIndex === 2) { // Date
          comparison = new Date(aValue) - new Date(bValue);
        } else {
          comparison = aValue.localeCompare(bValue);
        }

        return ascending ? comparison : -comparison;
      });

      rows.forEach(row => tbody.appendChild(row));
    }

    // Modal functions
    function updateOrderStatus(orderId, currentStatus) {
      document.getElementById('modalOrderId').value = orderId;
      document.getElementById('modalStatus').value = currentStatus;
      document.getElementById('updateStatusModal').style.display = 'block';
    }

    function closeModal() {
      document.getElementById('updateStatusModal').style.display = 'none';
    }

    // Action functions
    function viewOrderDetails(orderId) {
      alert(`Viewing details for order #${orderId}\\n(In a real application, this would show detailed order information)`);
    }

    function printOrder(orderId) {
      alert(`Printing order #${orderId}...`);
    }

    function deleteOrder(orderId) {
      if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        alert('Order deleted successfully! (In a real application, this would delete from the database)');
      }
    }

    function refreshOrders() {
      location.reload();
    }

    function exportOrders() {
      alert('Exporting orders... (In a real application, this would generate a CSV/Excel file)');
    }

    function printOrders() {
      window.print();
    }

    function showNewOrderModal() {
      alert('Opening new order form... (In a real application, this would open an order creation interface)');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
      const modal = document.getElementById('updateStatusModal');
      if (event.target == modal) {
        modal.style.display = 'none';
      }
    }

    // Auto-refresh orders every 30 seconds
    setInterval(function() {
      // In real app, this would fetch new orders via AJAX
      console.log('Checking for new orders...');
    }, 30000);
  </script>
</body>

</html>