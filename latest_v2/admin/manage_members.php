<?php
// admin/manage_members.php
require_once '../functions.php';
checkAdminAuth(); // Check if admin is logged in

$action = isset($_GET['action']) ? $_GET['action'] : 'view';
$member_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Mock member data (in real app, this would come from database)
$members = [
  ['id' => 1, 'username' => 'john_doe', 'email' => 'john@gmail.com', 'name' => 'John Doe', 'phone' => '+60 12-345 6789', 'join_date' => '2024-01-15', 'total_orders' => 23, 'total_spent' => 456.50, 'status' => 'Active', 'loyalty_points' => 230],
  ['id' => 2, 'username' => 'jane_smith', 'email' => 'jane@gmail.com', 'name' => 'Jane Smith', 'phone' => '+60 12-345 6790', 'join_date' => '2024-02-20', 'total_orders' => 15, 'total_spent' => 287.25, 'status' => 'Active', 'loyalty_points' => 144],
  ['id' => 3, 'username' => 'mike_brown', 'email' => 'mike@gmail.com', 'name' => 'Mike Brown', 'phone' => '+60 12-345 6791', 'join_date' => '2024-03-10', 'total_orders' => 8, 'total_spent' => 156.00, 'status' => 'Active', 'loyalty_points' => 78],
  ['id' => 4, 'username' => 'sarah_wilson', 'email' => 'sarah@gmail.com', 'name' => 'Sarah Wilson', 'phone' => '+60 12-345 6792', 'join_date' => '2024-04-05', 'total_orders' => 31, 'total_spent' => 623.75, 'status' => 'VIP', 'loyalty_points' => 312],
  ['id' => 5, 'username' => 'david_lee', 'email' => 'david@gmail.com', 'name' => 'David Lee', 'phone' => '+60 12-345 6793', 'join_date' => '2024-05-15', 'total_orders' => 2, 'total_spent' => 45.50, 'status' => 'Inactive', 'loyalty_points' => 23]
];

$success_msg = $error_msg = "";
$form_data = ['username' => '', 'email' => '', 'name' => '', 'phone' => '', 'status' => 'Active', 'loyalty_points' => 0];

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($action == 'add' || $action == 'edit') {
    $form_data['username'] = trim($_POST['username']);
    $form_data['email'] = trim($_POST['email']);
    $form_data['name'] = trim($_POST['name']);
    $form_data['phone'] = trim($_POST['phone']);
    $form_data['status'] = $_POST['status'];
    $form_data['loyalty_points'] = (int)$_POST['loyalty_points'];

    // Basic validation
    $errors = [];
    if (empty($form_data['username'])) $errors[] = "Username is required";
    if (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($form_data['name'])) $errors[] = "Full name is required";
    if (empty($form_data['phone'])) $errors[] = "Phone number is required";
    if ($form_data['loyalty_points'] < 0) $errors[] = "Loyalty points cannot be negative";

    if (empty($errors)) {
      if ($action == 'add') {
        $success_msg = "Member added successfully!";
      } else {
        $success_msg = "Member updated successfully!";
      }
      $form_data = ['username' => '', 'email' => '', 'name' => '', 'phone' => '', 'status' => 'Active', 'loyalty_points' => 0];
    } else {
      $error_msg = implode(", ", $errors);
    }
  }
}

// Get member for editing
$current_member = null;
if ($action == 'edit' && $member_id > 0) {
  foreach ($members as $member) {
    if ($member['id'] == $member_id) {
      $current_member = $member;
      if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $form_data = $member;
      }
      break;
    }
  }
}

// Calculate statistics
$total_members = count($members);
$active_members = count(array_filter($members, function ($m) {
  return $m['status'] == 'Active' || $m['status'] == 'VIP';
}));
$vip_members = count(array_filter($members, function ($m) {
  return $m['status'] == 'VIP';
}));
$total_revenue = array_sum(array_column($members, 'total_spent'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Members - Admin Dashboard</title>
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

    .member-stats {
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

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 50px;
      height: 50px;
      background: rgba(111, 78, 55, 0.1);
      border-radius: 0 0 0 50px;
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

    .btn {
      padding: 12px 20px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      display: inline-block;
      margin-right: 10px;
      margin-bottom: 10px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
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
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .members-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background: white;
    }

    .members-table th,
    .members-table td {
      padding: 15px 10px;
      text-align: left;
      border-bottom: 1px solid #dee2e6;
    }

    .members-table th {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      font-weight: bold;
      color: #333;
      position: sticky;
      top: 0;
      cursor: pointer;
      user-select: none;
    }

    .members-table th:hover {
      background: linear-gradient(135deg, #e9ecef, #dee2e6);
    }

    .members-table tr:hover {
      background: #f8f9fa;
    }

    .status-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.85em;
      font-weight: bold;
      text-align: center;
      display: inline-block;
      min-width: 70px;
    }

    .status-active {
      background: #d4edda;
      color: #155724;
    }

    .status-vip {
      background: #fff3cd;
      color: #856404;
      border: 2px solid #ffc107;
    }

    .status-inactive {
      background: #f8d7da;
      color: #721c24;
    }

    .search-filter-section {
      background: #f8f9fa;
      padding: 25px;
      border-radius: 10px;
      margin-bottom: 20px;
      border: 1px solid #dee2e6;
    }

    .filter-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      align-items: end;
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

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 12px;
      border: 2px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
      transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus {
      outline: none;
      border-color: #6F4E37;
      box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.1);
    }

    .member-profile {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .member-avatar {
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, #6F4E37, #8B4513);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      font-size: 1.2em;
    }

    .member-info h4 {
      margin: 0;
      color: #333;
      font-size: 1em;
    }

    .member-info p {
      margin: 2px 0 0 0;
      color: #666;
      font-size: 0.9em;
    }

    .loyalty-points {
      background: linear-gradient(135deg, #ffc107, #ffca2c);
      color: #212529;
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 0.8em;
      font-weight: bold;
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

    .alert-danger {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .action-buttons {
      margin-bottom: 20px;
    }

    @media (max-width: 768px) {
      .admin-header {
        flex-direction: column;
        gap: 15px;
      }

      .member-stats {
        grid-template-columns: repeat(2, 1fr);
      }

      .filter-grid {
        grid-template-columns: 1fr;
      }

      .members-table {
        font-size: 0.9em;
      }

      .member-profile {
        flex-direction: column;
        text-align: center;
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
      <h1><i class="fas fa-user-friends"></i> Member Management</h1>
      <div class="admin-nav">
        <a href="dashboard.php" class="nav-btn"><i class="fas fa-dashboard"></i> Dashboard</a>
        <a href="manage_staff.php" class="nav-btn"><i class="fas fa-users"></i> Staff</a>
        <a href="manage_members.php" class="nav-btn active"><i class="fas fa-user-friends"></i> Members</a>
        <a href="manage_orders.php" class="nav-btn"><i class="fas fa-shopping-cart"></i> Orders</a>
        <a href="sales_report.php" class="nav-btn"><i class="fas fa-chart-bar"></i> Reports</a>
        <a href="logout.php" class="nav-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>

    <?php if ($action == 'view'): ?>
      <!-- Member Statistics -->
      <div class="member-stats fade-in">
        <div class="stat-card pulse">
          <i class="fas fa-users stat-icon"></i>
          <div class="stat-number"><?php echo $total_members; ?></div>
          <div class="stat-label">Total Members</div>
        </div>
        <div class="stat-card">
          <i class="fas fa-user-check stat-icon"></i>
          <div class="stat-number"><?php echo $active_members; ?></div>
          <div class="stat-label">Active Members</div>
        </div>
        <div class="stat-card">
          <i class="fas fa-crown stat-icon"></i>
          <div class="stat-number"><?php echo $vip_members; ?></div>
          <div class="stat-label">VIP Members</div>
        </div>
        <div class="stat-card">
          <i class="fas fa-dollar-sign stat-icon"></i>
          <div class="stat-number">$<?php echo number_format($total_revenue, 0); ?></div>
          <div class="stat-label">Total Revenue</div>
        </div>
        <div class="stat-card">
          <i class="fas fa-gift stat-icon"></i>
          <div class="stat-number"><?php echo array_sum(array_column($members, 'loyalty_points')); ?></div>
          <div class="stat-label">Total Loyalty Points</div>
        </div>
      </div>

      <!-- Search and Filter Section -->
      <div class="search-filter-section fade-in">
        <h3><i class="fas fa-search"></i> Search & Filter Members</h3>
        <div class="filter-grid">
          <div class="form-group">
            <label for="searchMembers">Search Members:</label>
            <input type="text" id="searchMembers" placeholder="Search by name, username, or email...">
          </div>
          <div class="form-group">
            <label for="filterStatus">Filter by Status:</label>
            <select id="filterStatus">
              <option value="">All Statuses</option>
              <option value="Active">Active</option>
              <option value="VIP">VIP</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
          <div class="form-group">
            <label for="sortBy">Sort by:</label>
            <select id="sortBy">
              <option value="name">Name</option>
              <option value="join_date">Join Date</option>
              <option value="total_orders">Total Orders</option>
              <option value="total_spent">Total Spent</option>
              <option value="loyalty_points">Loyalty Points</option>
            </select>
          </div>
          <div>
            <button onclick="resetFilters()" class="btn btn-primary">
              <i class="fas fa-refresh"></i> Reset Filters
            </button>
          </div>
        </div>
      </div>

      <!-- Members List -->
      <div class="content-section fade-in">
        <div class="section-title">
          <i class="fas fa-list"></i>
          Member Directory
        </div>

        <div class="action-buttons">
          <a href="?action=add" class="btn btn-success">
            <i class="fas fa-plus"></i> Add New Member
          </a>
          <button onclick="exportMembers()" class="btn btn-primary">
            <i class="fas fa-download"></i> Export Members
          </button>
          <button onclick="sendBulkEmail()" class="btn btn-info">
            <i class="fas fa-envelope"></i> Send Bulk Email
          </button>
          <button onclick="loyaltyReport()" class="btn btn-warning">
            <i class="fas fa-chart-line"></i> Loyalty Report
          </button>
        </div>

        <div style="overflow-x: auto;">
          <table class="members-table" id="membersTable">
            <thead>
              <tr>
                <th onclick="sortTable(0)">ID <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(1)">Member <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(2)">Contact <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(3)">Join Date <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(4)">Orders <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(5)">Total Spent <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(6)">Status <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(7)">Loyalty Points <i class="fas fa-sort"></i></th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($members as $member): ?>
                <tr>
                  <td><strong>#<?php echo $member['id']; ?></strong></td>
                  <td>
                    <div class="member-profile">
                      <div class="member-avatar">
                        <?php echo strtoupper(substr($member['name'], 0, 1)); ?>
                      </div>
                      <div class="member-info">
                        <h4><?php echo htmlspecialchars($member['name']); ?></h4>
                        <p>@<?php echo htmlspecialchars($member['username']); ?></p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>
                      <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($member['email']); ?><br>
                      <i class="fas fa-phone"></i> <?php echo htmlspecialchars($member['phone']); ?>
                    </div>
                  </td>
                  <td><?php echo date('M j, Y', strtotime($member['join_date'])); ?></td>
                  <td><strong><?php echo $member['total_orders']; ?></strong> orders</td>
                  <td><strong>$<?php echo number_format($member['total_spent'], 2); ?></strong></td>
                  <td>
                    <span class="status-badge <?php echo 'status-' . strtolower($member['status']); ?>">
                      <?php if ($member['status'] == 'VIP'): ?>
                        <i class="fas fa-crown"></i>
                      <?php endif; ?>
                      <?php echo $member['status']; ?>
                    </span>
                  </td>
                  <td>
                    <span class="loyalty-points">
                      <i class="fas fa-star"></i> <?php echo $member['loyalty_points']; ?>
                    </span>
                  </td>
                  <td>
                    <a href="?action=edit&id=<?php echo $member['id']; ?>" class="btn btn-warning" style="padding: 5px 10px; margin: 2px;">
                      <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="viewMemberDetails(<?php echo $member['id']; ?>)" class="btn btn-info" style="padding: 5px 10px; margin: 2px;">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="deleteMember(<?php echo $member['id']; ?>)" class="btn btn-danger" style="padding: 5px 10px; margin: 2px;">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    <?php elseif ($action == 'add' || $action == 'edit'): ?>
      <!-- Add/Edit Member Form -->
      <div class="content-section fade-in">
        <div class="section-title">
          <i class="fas fa-<?php echo $action == 'add' ? 'plus' : 'edit'; ?>"></i>
          <?php echo $action == 'add' ? 'Add New Member' : 'Edit Member'; ?>
        </div>

        <?php if (!empty($success_msg)): ?>
          <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <?php if (!empty($error_msg)): ?>
          <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <form method="post" id="memberForm">
          <div class="filter-grid">
            <div class="form-group">
              <label for="username">Username *</label>
              <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($form_data['username']); ?>" required>
            </div>

            <div class="form-group">
              <label for="name">Full Name *</label>
              <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($form_data['name']); ?>" required>
            </div>

            <div class="form-group">
              <label for="email">Email Address *</label>
              <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($form_data['email']); ?>" required>
            </div>

            <div class="form-group">
              <label for="phone">Phone Number *</label>
              <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($form_data['phone']); ?>" required>
            </div>

            <div class="form-group">
              <label for="status">Member Status</label>
              <select id="status" name="status">
                <option value="Active" <?php echo $form_data['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                <option value="VIP" <?php echo $form_data['status'] == 'VIP' ? 'selected' : ''; ?>>VIP</option>
                <option value="Inactive" <?php echo $form_data['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
              </select>
            </div>

            <div class="form-group">
              <label for="loyalty_points">Loyalty Points</label>
              <input type="number" id="loyalty_points" name="loyalty_points" value="<?php echo $form_data['loyalty_points']; ?>" min="0">
            </div>
          </div>

          <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-success">
              <i class="fas fa-save"></i> <?php echo $action == 'add' ? 'Add Member' : 'Update Member'; ?>
            </button>
            <a href="?" class="btn btn-primary">
              <i class="fas fa-arrow-left"></i> Back to List
            </a>
          </div>
        </form>
      </div>
    <?php endif; ?>
  </div>

  <script>
    // Search and filter functionality
    document.getElementById('searchMembers').addEventListener('input', filterTable);
    document.getElementById('filterStatus').addEventListener('change', filterTable);

    function filterTable() {
      const searchTerm = document.getElementById('searchMembers').value.toLowerCase();
      const statusFilter = document.getElementById('filterStatus').value;
      const table = document.getElementById('membersTable');
      const rows = table.getElementsByTagName('tr');

      for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let showRow = true;

        // Search filter
        if (searchTerm) {
          const name = cells[1].textContent.toLowerCase();
          const email = cells[2].textContent.toLowerCase();

          if (!name.includes(searchTerm) && !email.includes(searchTerm)) {
            showRow = false;
          }
        }

        // Status filter
        if (statusFilter && !cells[6].textContent.includes(statusFilter)) {
          showRow = false;
        }

        row.style.display = showRow ? '' : 'none';
      }
    }

    // Sort functionality
    let sortDirection = {};

    function sortTable(columnIndex) {
      const table = document.getElementById('membersTable');
      const tbody = table.getElementsByTagName('tbody')[0];
      const rows = Array.from(tbody.getElementsByTagName('tr'));

      sortDirection[columnIndex] = !sortDirection[columnIndex];
      const ascending = sortDirection[columnIndex];

      rows.sort((a, b) => {
        const aValue = a.getElementsByTagName('td')[columnIndex].textContent.trim();
        const bValue = b.getElementsByTagName('td')[columnIndex].textContent.trim();

        let comparison = 0;

        if (columnIndex === 0 || columnIndex === 4 || columnIndex === 7) { // ID, Orders, Loyalty Points
          const aNum = parseFloat(aValue.replace(/[^0-9.-]/g, ''));
          const bNum = parseFloat(bValue.replace(/[^0-9.-]/g, ''));
          comparison = aNum - bNum;
        } else if (columnIndex === 3) { // Join Date
          comparison = new Date(aValue) - new Date(bValue);
        } else if (columnIndex === 5) { // Total Spent
          const aNum = parseFloat(aValue.replace(/[^0-9.-]/g, ''));
          const bNum = parseFloat(bValue.replace(/[^0-9.-]/g, ''));
          comparison = aNum - bNum;
        } else {
          comparison = aValue.localeCompare(bValue);
        }

        return ascending ? comparison : -comparison;
      });

      rows.forEach(row => tbody.appendChild(row));
    }

    // Action functions
    function viewMemberDetails(memberId) {
      alert(`Viewing details for member #${memberId}\\n(In a real application, this would open a detailed view)`);
    }

    function deleteMember(memberId) {
      if (confirm('Are you sure you want to delete this member? This action cannot be undone.')) {
        alert('Member deleted successfully! (In a real application, this would delete from the database)');
      }
    }

    function exportMembers() {
      alert('Exporting member list... (In a real application, this would generate a CSV/Excel file)');
    }

    function sendBulkEmail() {
      alert('Opening bulk email composer... (In a real application, this would open an email interface)');
    }

    function loyaltyReport() {
      alert('Generating loyalty report... (In a real application, this would generate a detailed loyalty analysis)');
    }

    function resetFilters() {
      document.getElementById('searchMembers').value = '';
      document.getElementById('filterStatus').value = '';
      filterTable();
    }

    // Form validation
    if (document.getElementById('memberForm')) {
      document.getElementById('memberForm').addEventListener('submit', function(e) {
        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();

        if (username.length < 3) {
          alert('Username must be at least 3 characters long');
          e.preventDefault();
          return false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
          alert('Please enter a valid email address');
          e.preventDefault();
          return false;
        }

        const phoneRegex = /^[+]?[0-9\s\-()]{10,}$/;
        if (!phoneRegex.test(phone)) {
          alert('Please enter a valid phone number');
          e.preventDefault();
          return false;
        }
      });
    }
  </script>
</body>

</html>