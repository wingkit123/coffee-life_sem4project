<?php
// admin/manage_staff.php
require_once '../functions.php';
checkAdminAuth(); // Check if admin is logged in

$action = isset($_GET['action']) ? $_GET['action'] : 'view';
$staff_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Mock staff data (in real app, this would come from database)
$staff_members = [
  ['id' => 1, 'name' => 'Alice Johnson', 'position' => 'Manager', 'email' => 'alice@beanscafe.com', 'phone' => '+60 12-345 6789', 'hire_date' => '2023-01-15', 'salary' => 4500, 'status' => 'Active'],
  ['id' => 2, 'name' => 'Bob Smith', 'position' => 'Barista', 'email' => 'bob@beanscafe.com', 'phone' => '+60 12-345 6790', 'hire_date' => '2023-03-20', 'salary' => 2800, 'status' => 'Active'],
  ['id' => 3, 'name' => 'Carol Brown', 'position' => 'Cashier', 'email' => 'carol@beanscafe.com', 'phone' => '+60 12-345 6791', 'hire_date' => '2023-06-10', 'salary' => 2500, 'status' => 'Active'],
  ['id' => 4, 'name' => 'David Wilson', 'position' => 'Barista', 'email' => 'david@beanscafe.com', 'phone' => '+60 12-345 6792', 'hire_date' => '2023-08-05', 'salary' => 2800, 'status' => 'On Leave']
];

$success_msg = $error_msg = "";
$form_data = ['name' => '', 'position' => '', 'email' => '', 'phone' => '', 'salary' => '', 'status' => 'Active'];

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($action == 'add' || $action == 'edit') {
    $form_data['name'] = trim($_POST['name']);
    $form_data['position'] = trim($_POST['position']);
    $form_data['email'] = trim($_POST['email']);
    $form_data['phone'] = trim($_POST['phone']);
    $form_data['salary'] = trim($_POST['salary']);
    $form_data['status'] = $_POST['status'];

    // Basic validation
    $errors = [];
    if (empty($form_data['name'])) $errors[] = "Name is required";
    if (empty($form_data['position'])) $errors[] = "Position is required";
    if (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($form_data['phone'])) $errors[] = "Phone number is required";
    if (!is_numeric($form_data['salary']) || $form_data['salary'] <= 0) $errors[] = "Valid salary is required";

    if (empty($errors)) {
      if ($action == 'add') {
        $success_msg = "Staff member added successfully!";
      } else {
        $success_msg = "Staff member updated successfully!";
      }
      $form_data = ['name' => '', 'position' => '', 'email' => '', 'phone' => '', 'salary' => '', 'status' => 'Active'];
    } else {
      $error_msg = implode(", ", $errors);
    }
  }
}

// Get staff member for editing
$current_staff = null;
if ($action == 'edit' && $staff_id > 0) {
  foreach ($staff_members as $staff) {
    if ($staff['id'] == $staff_id) {
      $current_staff = $staff;
      if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $form_data = $staff;
      }
      break;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Staff - Admin Dashboard</title>
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
      max-width: 1200px;
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

    .action-buttons {
      margin-bottom: 20px;
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

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* Table Styles */
    .staff-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .staff-table th,
    .staff-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .staff-table th {
      background: #f8f9fa;
      font-weight: bold;
      color: #333;
      position: sticky;
      top: 0;
    }

    .staff-table tr:hover {
      background: #f5f5f5;
    }

    .status-badge {
      padding: 5px 10px;
      border-radius: 15px;
      font-size: 0.85em;
      font-weight: bold;
    }

    .status-active {
      background: #d4edda;
      color: #155724;
    }

    .status-leave {
      background: #fff3cd;
      color: #856404;
    }

    .status-inactive {
      background: #f8d7da;
      color: #721c24;
    }

    /* Form Styles */
    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
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

    .staff-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      border-left: 4px solid #6F4E37;
    }

    .stat-number {
      font-size: 2em;
      font-weight: bold;
      color: #6F4E37;
    }

    .stat-label {
      color: #666;
      margin-top: 5px;
    }

    .search-filter {
      background: #f8f9fa;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
    }

    .search-group {
      display: flex;
      gap: 15px;
      align-items: end;
    }

    .search-input {
      flex: 1;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    @media (max-width: 768px) {
      .admin-header {
        flex-direction: column;
        gap: 15px;
      }

      .admin-nav {
        flex-wrap: wrap;
        justify-content: center;
      }

      .form-grid {
        grid-template-columns: 1fr;
      }

      .search-group {
        flex-direction: column;
        align-items: stretch;
      }

      .staff-table {
        font-size: 0.9em;
      }
    }

    /* Animation */
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
  </style>
</head>

<body>
  <div class="admin-container">
    <!-- Admin Header -->
    <div class="admin-header fade-in">
      <h1><i class="fas fa-users"></i> Staff Management</h1>
      <div class="admin-nav">
        <a href="dashboard.php" class="nav-btn"><i class="fas fa-dashboard"></i> Dashboard</a>
        <a href="manage_staff.php" class="nav-btn active"><i class="fas fa-users"></i> Staff</a>
        <a href="manage_members.php" class="nav-btn"><i class="fas fa-user-friends"></i> Members</a>
        <a href="manage_orders.php" class="nav-btn"><i class="fas fa-shopping-cart"></i> Orders</a>
        <a href="sales_report.php" class="nav-btn"><i class="fas fa-chart-bar"></i> Reports</a>
        <a href="logout.php" class="nav-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>

    <?php if ($action == 'view'): ?>
      <!-- Staff Statistics -->
      <div class="staff-stats fade-in">
        <div class="stat-card">
          <div class="stat-number"><?php echo count($staff_members); ?></div>
          <div class="stat-label">Total Staff</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?php echo count(array_filter($staff_members, function ($s) {
                                      return $s['status'] == 'Active';
                                    })); ?></div>
          <div class="stat-label">Active Staff</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?php echo count(array_filter($staff_members, function ($s) {
                                      return $s['status'] == 'On Leave';
                                    })); ?></div>
          <div class="stat-label">On Leave</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">$<?php echo number_format(array_sum(array_column($staff_members, 'salary')), 0); ?></div>
          <div class="stat-label">Total Payroll</div>
        </div>
      </div>

      <!-- Staff List -->
      <div class="content-section fade-in">
        <div class="section-title">
          <i class="fas fa-list"></i>
          Staff Directory
        </div>

        <div class="action-buttons">
          <a href="?action=add" class="btn btn-success">
            <i class="fas fa-plus"></i> Add New Staff
          </a>
          <button onclick="exportStaff()" class="btn btn-primary">
            <i class="fas fa-download"></i> Export List
          </button>
        </div>

        <!-- Search and Filter -->
        <div class="search-filter">
          <div class="search-group">
            <div>
              <label for="searchStaff">Search Staff:</label>
              <input type="text" id="searchStaff" class="search-input" placeholder="Search by name, position, or email...">
            </div>
            <div>
              <label for="filterStatus">Filter by Status:</label>
              <select id="filterStatus" class="search-input">
                <option value="">All Statuses</option>
                <option value="Active">Active</option>
                <option value="On Leave">On Leave</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
            <div>
              <label for="filterPosition">Filter by Position:</label>
              <select id="filterPosition" class="search-input">
                <option value="">All Positions</option>
                <option value="Manager">Manager</option>
                <option value="Barista">Barista</option>
                <option value="Cashier">Cashier</option>
              </select>
            </div>
          </div>
        </div>

        <div style="overflow-x: auto;">
          <table class="staff-table" id="staffTable">
            <thead>
              <tr>
                <th onclick="sortTable(0)">ID <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(1)">Name <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(2)">Position <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(3)">Email <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(4)">Phone <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(5)">Hire Date <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(6)">Salary <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(7)">Status <i class="fas fa-sort"></i></th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($staff_members as $staff): ?>
                <tr>
                  <td><?php echo $staff['id']; ?></td>
                  <td><strong><?php echo htmlspecialchars($staff['name']); ?></strong></td>
                  <td><?php echo htmlspecialchars($staff['position']); ?></td>
                  <td><?php echo htmlspecialchars($staff['email']); ?></td>
                  <td><?php echo htmlspecialchars($staff['phone']); ?></td>
                  <td><?php echo date('M j, Y', strtotime($staff['hire_date'])); ?></td>
                  <td>$<?php echo number_format($staff['salary'], 0); ?></td>
                  <td>
                    <span class="status-badge <?php echo 'status-' . strtolower(str_replace(' ', '', $staff['status'])); ?>">
                      <?php echo $staff['status']; ?>
                    </span>
                  </td>
                  <td>
                    <a href="?action=edit&id=<?php echo $staff['id']; ?>" class="btn btn-warning" style="padding: 5px 10px; margin: 2px;">
                      <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="deleteStaff(<?php echo $staff['id']; ?>)" class="btn btn-danger" style="padding: 5px 10px; margin: 2px;">
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
      <!-- Add/Edit Staff Form -->
      <div class="content-section fade-in">
        <div class="section-title">
          <i class="fas fa-<?php echo $action == 'add' ? 'plus' : 'edit'; ?>"></i>
          <?php echo $action == 'add' ? 'Add New Staff Member' : 'Edit Staff Member'; ?>
        </div>

        <?php if (!empty($success_msg)): ?>
          <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <?php if (!empty($error_msg)): ?>
          <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <form method="post" id="staffForm">
          <div class="form-grid">
            <div class="form-group">
              <label for="name">Full Name *</label>
              <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($form_data['name']); ?>" required>
            </div>

            <div class="form-group">
              <label for="position">Position *</label>
              <select id="position" name="position" required>
                <option value="">Select Position</option>
                <option value="Manager" <?php echo $form_data['position'] == 'Manager' ? 'selected' : ''; ?>>Manager</option>
                <option value="Assistant Manager" <?php echo $form_data['position'] == 'Assistant Manager' ? 'selected' : ''; ?>>Assistant Manager</option>
                <option value="Barista" <?php echo $form_data['position'] == 'Barista' ? 'selected' : ''; ?>>Barista</option>
                <option value="Cashier" <?php echo $form_data['position'] == 'Cashier' ? 'selected' : ''; ?>>Cashier</option>
                <option value="Kitchen Staff" <?php echo $form_data['position'] == 'Kitchen Staff' ? 'selected' : ''; ?>>Kitchen Staff</option>
                <option value="Cleaner" <?php echo $form_data['position'] == 'Cleaner' ? 'selected' : ''; ?>>Cleaner</option>
              </select>
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
              <label for="salary">Monthly Salary (RM) *</label>
              <input type="number" id="salary" name="salary" value="<?php echo htmlspecialchars($form_data['salary']); ?>" min="1000" step="100" required>
            </div>

            <div class="form-group">
              <label for="status">Employment Status</label>
              <select id="status" name="status">
                <option value="Active" <?php echo $form_data['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                <option value="On Leave" <?php echo $form_data['status'] == 'On Leave' ? 'selected' : ''; ?>>On Leave</option>
                <option value="Inactive" <?php echo $form_data['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
              </select>
            </div>
          </div>

          <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-success">
              <i class="fas fa-save"></i> <?php echo $action == 'add' ? 'Add Staff Member' : 'Update Staff Member'; ?>
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
    // Search functionality
    document.getElementById('searchStaff').addEventListener('input', filterTable);
    document.getElementById('filterStatus').addEventListener('change', filterTable);
    document.getElementById('filterPosition').addEventListener('change', filterTable);

    function filterTable() {
      const searchTerm = document.getElementById('searchStaff').value.toLowerCase();
      const statusFilter = document.getElementById('filterStatus').value;
      const positionFilter = document.getElementById('filterPosition').value;
      const table = document.getElementById('staffTable');
      const rows = table.getElementsByTagName('tr');

      for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let showRow = true;

        // Search filter
        if (searchTerm) {
          const name = cells[1].textContent.toLowerCase();
          const position = cells[2].textContent.toLowerCase();
          const email = cells[3].textContent.toLowerCase();

          if (!name.includes(searchTerm) && !position.includes(searchTerm) && !email.includes(searchTerm)) {
            showRow = false;
          }
        }

        // Status filter
        if (statusFilter && cells[7].textContent.trim() !== statusFilter) {
          showRow = false;
        }

        // Position filter
        if (positionFilter && cells[2].textContent.trim() !== positionFilter) {
          showRow = false;
        }

        row.style.display = showRow ? '' : 'none';
      }
    }

    // Sort table functionality
    let sortDirection = {};

    function sortTable(columnIndex) {
      const table = document.getElementById('staffTable');
      const tbody = table.getElementsByTagName('tbody')[0];
      const rows = Array.from(tbody.getElementsByTagName('tr'));

      const isNumeric = columnIndex === 0 || columnIndex === 6; // ID and Salary columns
      const isDate = columnIndex === 5; // Hire Date column

      // Toggle sort direction
      sortDirection[columnIndex] = !sortDirection[columnIndex];
      const ascending = sortDirection[columnIndex];

      rows.sort((a, b) => {
        const aValue = a.getElementsByTagName('td')[columnIndex].textContent.trim();
        const bValue = b.getElementsByTagName('td')[columnIndex].textContent.trim();

        let comparison = 0;

        if (isNumeric) {
          const aNum = parseFloat(aValue.replace(/[^0-9.-]/g, ''));
          const bNum = parseFloat(bValue.replace(/[^0-9.-]/g, ''));
          comparison = aNum - bNum;
        } else if (isDate) {
          comparison = new Date(aValue) - new Date(bValue);
        } else {
          comparison = aValue.localeCompare(bValue);
        }

        return ascending ? comparison : -comparison;
      });

      // Reorder rows
      rows.forEach(row => tbody.appendChild(row));
    }

    // Delete staff confirmation
    function deleteStaff(staffId) {
      if (confirm('Are you sure you want to delete this staff member? This action cannot be undone.')) {
        alert('Staff member deleted successfully! (In a real application, this would delete from the database)');
        // In real app: window.location.href = '?action=delete&id=' + staffId;
      }
    }

    // Export functionality
    function exportStaff() {
      alert('Exporting staff list... (In a real application, this would generate a CSV/Excel file)');
    }

    // Form validation
    if (document.getElementById('staffForm')) {
      document.getElementById('staffForm').addEventListener('submit', function(e) {
        const phone = document.getElementById('phone').value;
        const phoneRegex = /^[+]?[0-9\s\-()]{10,}$/;

        if (!phoneRegex.test(phone)) {
          alert('Please enter a valid phone number');
          e.preventDefault();
          return false;
        }

        const salary = parseFloat(document.getElementById('salary').value);
        if (salary < 1000) {
          alert('Minimum salary should be RM 1000');
          e.preventDefault();
          return false;
        }
      });
    }

    // Auto-format phone number
    if (document.getElementById('phone')) {
      document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.startsWith('60')) {
          value = '+60 ' + value.substring(2, 4) + '-' + value.substring(4, 7) + ' ' + value.substring(7);
        }
        e.target.value = value;
      });
    }
  </script>
</body>

</html>