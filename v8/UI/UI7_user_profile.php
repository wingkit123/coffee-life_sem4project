<?php
// user_profile.php
require_once '../functions/functions.php';
require_once '../config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
  header("Location: UI5_user_login.php");
  exit;
}

$user_id = $_SESSION['user_id'] ?? 0;
$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['update_profile'])) {
    // Handle profile update
    $email = trim($_POST['email']);
    $contact_number = trim($_POST['contact_number']);
    $address = trim($_POST['address']);

    // Validate input
    if (empty($email) || empty($contact_number)) {
      $message = 'Email and contact number are required.';
      $message_type = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $message = 'Please enter a valid email address.';
      $message_type = 'error';
    } else {
      try {
        $stmt = $pdo->prepare("UPDATE users SET email = ?, contact_number = ?, address = ? WHERE id = ?");
        if ($stmt->execute([$email, $contact_number, $address, $user_id])) {
          $message = 'Profile updated successfully!';
          $message_type = 'success';
        } else {
          $message = 'Error updating profile. Please try again.';
          $message_type = 'error';
        }
      } catch (Exception $e) {
        $message = 'Database error. Please try again.';
        $message_type = 'error';
      }
    }
  } elseif (isset($_POST['change_password'])) {
    // Handle password change
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
      $message = 'All password fields are required.';
      $message_type = 'error';
    } elseif ($new_password !== $confirm_password) {
      $message = 'New passwords do not match.';
      $message_type = 'error';
    } elseif (strlen($new_password) < 6) {
      $message = 'New password must be at least 6 characters long.';
      $message_type = 'error';
    } else {
      try {
        // Verify current password
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($current_password, $user['password'])) {
          // Update password
          $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
          $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
          if ($stmt->execute([$hashed_password, $user_id])) {
            $message = 'Password updated successfully!';
            $message_type = 'success';
          } else {
            $message = 'Error updating password. Please try again.';
            $message_type = 'error';
          }
        } else {
          $message = 'Current password is incorrect.';
          $message_type = 'error';
        }
      } catch (Exception $e) {
        $message = 'Database error. Please try again.';
        $message_type = 'error';
      }
    }
  }
}

// Fetch user data
try {
  $stmt = $pdo->prepare("SELECT username, email, contact_number, address, created_at FROM users WHERE id = ?");
  $stmt->execute([$user_id]);
  $user_data = $stmt->fetch();

  if (!$user_data) {
    header("Location: user_logout.php");
    exit;
  }
} catch (Exception $e) {
  die("Error fetching user data.");
}

include '../header.php';
?>

<link rel="stylesheet" href="../css/profile.css">

<div class="profile-container">
  <!-- Profile Header -->
  <div class="profile-header">
    <div class="profile-avatar">
      <i class="fas fa-user-circle"></i>
    </div>
    <div class="profile-info">
      <h1>Welcome, <?php echo htmlspecialchars($user_data['username']); ?>!</h1>
      <p class="member-since">Member since <?php echo date('F Y', strtotime($user_data['created_at'])); ?></p>
    </div>
    <div class="profile-actions">
      <a href="UI1_index.php" class="btn btn-secondary">
        <i class="fas fa-tachometer-alt"></i> Dashboard
      </a>
      <a href="user_logout.php" class="btn btn-outline">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
  </div>

  <!-- Message Display -->
  <?php if (!empty($message)): ?>
    <div class="message <?php echo $message_type; ?>">
      <i class="fas <?php echo $message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
      <?php echo htmlspecialchars($message); ?>
    </div>
  <?php endif; ?>

  <!-- Profile Tabs -->
  <div class="profile-tabs">
    <button class="tab-button active" onclick="showTab('profile')">
      <i class="fas fa-user"></i> Profile Information
    </button>
    <button class="tab-button" onclick="showTab('security')">
      <i class="fas fa-shield-alt"></i> Security
    </button>
    <button class="tab-button" onclick="showTab('preferences')">
      <i class="fas fa-cog"></i> Preferences
    </button>
  </div>

  <!-- Profile Information Tab -->
  <div id="profile-tab" class="tab-content active">
    <div class="profile-section">
      <h2><i class="fas fa-user"></i> Personal Information</h2>

      <form method="POST" class="profile-form">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username"
            value="<?php echo htmlspecialchars($user_data['username']); ?>"
            disabled>
          <small class="form-note">Username cannot be changed</small>
        </div>

        <div class="form-group">
          <label for="email">Email Address *</label>
          <input type="email" id="email" name="email"
            value="<?php echo htmlspecialchars($user_data['email']); ?>"
            required>
        </div>

        <div class="form-group">
          <label for="contact_number">Contact Number *</label>
          <input type="text" id="contact_number" name="contact_number"
            value="<?php echo htmlspecialchars($user_data['contact_number']); ?>"
            required>
        </div>

        <div class="form-group">
          <label for="address">Address</label>
          <textarea id="address" name="address" rows="3"
            placeholder="Enter your full address"><?php echo htmlspecialchars($user_data['address'] ?? ''); ?></textarea>
        </div>

        <button type="submit" name="update_profile" class="btn btn-primary">
          <i class="fas fa-save"></i> Update Profile
        </button>
      </form>
    </div>
  </div>

  <!-- Security Tab -->
  <div id="security-tab" class="tab-content">
    <div class="profile-section">
      <h2><i class="fas fa-shield-alt"></i> Change Password</h2>

      <form method="POST" class="profile-form">
        <div class="form-group">
          <label for="current_password">Current Password *</label>
          <input type="password" id="current_password" name="current_password" required>
        </div>

        <div class="form-group">
          <label for="new_password">New Password *</label>
          <input type="password" id="new_password" name="new_password"
            minlength="6" required>
          <small class="form-note">Minimum 6 characters</small>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm New Password *</label>
          <input type="password" id="confirm_password" name="confirm_password"
            minlength="6" required>
        </div>

        <button type="submit" name="change_password" class="btn btn-primary">
          <i class="fas fa-key"></i> Change Password
        </button>
      </form>
    </div>

    <div class="profile-section">
      <h2><i class="fas fa-info-circle"></i> Account Information</h2>
      <div class="info-grid">
        <div class="info-item">
          <label>Account Status</label>
          <span class="status active">Active</span>
        </div>
        <div class="info-item">
          <label>Member Since</label>
          <span><?php echo date('F j, Y', strtotime($user_data['created_at'])); ?></span>
        </div>
        <div class="info-item">
          <label>Last Login</label>
          <span>Today</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Preferences Tab -->
  <div id="preferences-tab" class="tab-content">
    <div class="profile-section">
      <h2><i class="fas fa-cog"></i> Account Preferences</h2>

      <div class="preference-group">
        <h3>Coffee Preferences</h3>
        <div class="preference-item">
          <label class="switch">
            <input type="checkbox" checked>
            <span class="slider"></span>
          </label>
          <div class="preference-text">
            <strong>Email notifications for new coffee arrivals</strong>
            <p>Get notified when we add new coffee blends to our menu</p>
          </div>
        </div>

        <div class="preference-item">
          <label class="switch">
            <input type="checkbox" checked>
            <span class="slider"></span>
          </label>
          <div class="preference-text">
            <strong>Special offers and promotions</strong>
            <p>Receive emails about discounts and special deals</p>
          </div>
        </div>

        <div class="preference-item">
          <label class="switch">
            <input type="checkbox">
            <span class="slider"></span>
          </label>
          <div class="preference-text">
            <strong>Order status updates via SMS</strong>
            <p>Get text messages about your order status</p>
          </div>
        </div>
      </div>

      <div class="preference-group">
        <h3>Quick Actions</h3>
        <div class="quick-actions-grid">
          <a href="UI2_menu.php" class="quick-action">
            <i class="fas fa-coffee"></i>
            <span>Browse Menu</span>
          </a>
          <a href="UI4_view_cart.php" class="quick-action">
            <i class="fas fa-shopping-cart"></i>
            <span>View Cart</span>
          </a>
          <a href="UI10_contact_us.php" class="quick-action">
            <i class="fas fa-envelope"></i>
            <span>Contact Us</span>
          </a>
          <a href="UI11_rating_comments.php" class="quick-action">
            <i class="fas fa-star"></i>
            <span>Leave Review</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => content.classList.remove('active'));

    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => button.classList.remove('active'));

    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.add('active');

    // Add active class to clicked button
    event.target.classList.add('active');
  }

  // Password confirmation validation
  document.addEventListener('DOMContentLoaded', function() {
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');

    function validatePassword() {
      if (newPassword.value !== confirmPassword.value) {
        confirmPassword.setCustomValidity('Passwords do not match');
      } else {
        confirmPassword.setCustomValidity('');
      }
    }

    if (newPassword && confirmPassword) {
      newPassword.addEventListener('input', validatePassword);
      confirmPassword.addEventListener('input', validatePassword);
    }

    // Auto-hide messages after 5 seconds
    const message = document.querySelector('.message');
    if (message) {
      setTimeout(() => {
        message.style.opacity = '0';
        setTimeout(() => message.remove(), 300);
      }, 5000);
    }
  });
</script>

<?php include '../footer.php'; ?>