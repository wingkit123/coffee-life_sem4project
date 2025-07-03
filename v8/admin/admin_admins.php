<?php
// admin_admins.php - Admin Account Management
session_start();
require_once '../functions/functions.php';

// Check admin authentication
checkAdminAuth();

$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_admin'])) {
        // Add new admin
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        // Validation
        if (empty($username) || empty($password) || empty($confirm_password)) {
            $message = 'All fields are required.';
            $message_type = 'error';
        } elseif ($password !== $confirm_password) {
            $message = 'Passwords do not match.';
            $message_type = 'error';
        } elseif (strlen($password) < 6) {
            $message = 'Password must be at least 6 characters long.';
            $message_type = 'error';
        } else {
            if (addAdmin($username, $password)) {
                $message = 'Admin added successfully!';
                $message_type = 'success';
            } else {
                $message = 'Failed to add admin. Username might already exist.';
                $message_type = 'error';
            }
        }
    } elseif (isset($_POST['delete_admin'])) {
        // Delete admin
        $id = intval($_POST['id']);
        
        // Don't allow deletion of current admin
        if ($id == $_SESSION['admin_id']) {
            $message = 'You cannot delete your own account.';
            $message_type = 'error';
        } else {
            if (deleteAdmin($id)) {
                $message = 'Admin deleted successfully!';
                $message_type = 'success';
            } else {
                $message = 'Failed to delete admin. Cannot delete the last admin account.';
                $message_type = 'error';
            }
        }
    } elseif (isset($_POST['change_password'])) {
        // Change admin password
        $id = intval($_POST['id']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        if (empty($new_password) || empty($confirm_password)) {
            $message = 'All fields are required.';
            $message_type = 'error';
        } elseif ($new_password !== $confirm_password) {
            $message = 'Passwords do not match.';
            $message_type = 'error';
        } elseif (strlen($new_password) < 6) {
            $message = 'Password must be at least 6 characters long.';
            $message_type = 'error';
        } else {
            if (updateAdminPassword($id, $new_password)) {
                $message = 'Password updated successfully!';
                $message_type = 'success';
            } else {
                $message = 'Failed to update password.';
                $message_type = 'error';
            }
        }
    }
}

// Get all admins
$admins = getAllAdmins();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management - Coffee's Life</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="admin-body">
    <div class="admin-container">
        <!-- Admin Header -->
        <div class="admin-header">
            <h1><i class="fas fa-user-shield"></i><span style="color:white"> Admin Management</span></h1>
        </div>

        <!-- Navigation -->
        <nav class="admin-nav">
            <ul>
        <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="admin_products.php"><i class="fas fa-coffee"></i> Products</a></li>
        <!-- <li><a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li> -->
        <li><a href="admin_users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="admin_admins.php" class="active"><i class="fas fa-user-shield"></i> Admins</a></li>
                <li><a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>

        <!-- Messages -->
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <i class="fas <?php echo $message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Add New Admin Form -->
        <div class="admin-card">
            <h2><i class="fas fa-user-plus"></i> Add New Admin</h2>

            <form action="admin_admins.php" method="post" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required minlength="6">
                        <small>Password must be at least 6 characters long</small>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password *</label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" name="add_admin" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Add Admin
                    </button>
                </div>
            </form>
        </div>

        <!-- Admins List -->
        <div class="admin-card">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> All Admins</h2>
            </div>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($admins)): ?>
                            <tr>
                                <td colspan="4" class="text-center">No admins found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($admins as $admin): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($admin['id']); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($admin['username']); ?>
                                        <?php if ($admin['id'] == $_SESSION['admin_id']): ?>
                                            <span class="badge">You</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($admin['created_at'])); ?></td>
                                    <td class="actions">
                                        <!-- Change Password Button -->
                                        <button type="button" class="btn-small btn-primary" 
                                                onclick="showPasswordForm(<?php echo $admin['id']; ?>)" title="Change Password">
                                            <i class="fas fa-key"></i>
                                        </button>
                                        
                                        <!-- Delete Button (not for current admin) -->
                                        <?php if ($admin['id'] != $_SESSION['admin_id']): ?>
                                            <form action="admin_admins.php" method="post" style="display: inline;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this admin?');">
                                                <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
                                                <button type="submit" name="delete_admin" class="btn-small btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                
                                <!-- Hidden password change form -->
                                <tr id="password-form-<?php echo $admin['id']; ?>" style="display: none;">
                                    <td colspan="4">
                                        <form action="admin_admins.php" method="post" class="inline-form">
                                            <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
                                            <div class="form-row" style="align-items: end;">
                                                <div class="form-group">
                                                    <label for="new_password_<?php echo $admin['id']; ?>">New Password</label>
                                                    <input type="password" id="new_password_<?php echo $admin['id']; ?>" 
                                                           name="new_password" required minlength="6">
                                                </div>
                                                <div class="form-group">
                                                    <label for="confirm_password_<?php echo $admin['id']; ?>">Confirm Password</label>
                                                    <input type="password" id="confirm_password_<?php echo $admin['id']; ?>" 
                                                           name="confirm_password" required minlength="6">
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" name="change_password" class="btn btn-primary">
                                                        <i class="fas fa-save"></i> Update
                                                    </button>
                                                    <button type="button" class="btn btn-secondary" 
                                                            onclick="hidePasswordForm(<?php echo $admin['id']; ?>)">
                                                        <i class="fas fa-times"></i> Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide messages after 5 seconds
        setTimeout(function() {
            var messages = document.querySelectorAll('.message');
            messages.forEach(function(message) {
                message.style.opacity = '0';
                setTimeout(function() {
                    message.style.display = 'none';
                }, 300);
            });
        }, 5000);

        function showPasswordForm(adminId) {
            var form = document.getElementById('password-form-' + adminId);
            form.style.display = form.style.display === 'none' ? 'table-row' : 'none';
        }

        function hidePasswordForm(adminId) {
            var form = document.getElementById('password-form-' + adminId);
            form.style.display = 'none';
        }

        // Password confirmation validation
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                const passwordField = form.querySelector('input[name="password"], input[name="new_password"]');
                const confirmField = form.querySelector('input[name="confirm_password"]');
                
                if (passwordField && confirmField) {
                    confirmField.addEventListener('input', function() {
                        if (passwordField.value !== confirmField.value) {
                            confirmField.setCustomValidity('Passwords do not match');
                        } else {
                            confirmField.setCustomValidity('');
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
