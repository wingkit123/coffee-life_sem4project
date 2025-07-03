<?php
// admin_login.php - Database-based Admin Login
session_start();
require_once '../config.php';

// If admin is already logged in, redirect to dashboard (unless force parameter is used)
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true && !isset($_GET['force'])) {
  header("Location: admin_dashboard.php");
  exit;
}

$username = $password = "";
$username_err = $password_err = "";
$login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate username
  if (empty(trim($_POST["username"]))) {
    $username_err = "Please enter username.";
  } else {
    $username = trim($_POST["username"]);
  }

  // Validate password
  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter password.";
  } else {
    $password = trim($_POST["password"]);
  }

  // Admin authentication using database
  if (empty($username_err) && empty($password_err)) {
    try {
      // Prepare statement to find admin by username
      $stmt = $pdo->prepare("SELECT id, username, password FROM admin WHERE username = ?");
      $stmt->execute([$username]);
      $admin = $stmt->fetch();

      if ($admin && password_verify($password, $admin['password'])) {
        // Valid admin credentials
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_id'] = $admin['id'];

        // Regenerate session ID for security
        session_regenerate_id(true);

        header("Location: admin_dashboard.php");
        exit;
      } else {
        $login_err = "Invalid username or password.";
      }
    } catch (PDOException $e) {
      error_log("Admin login error: " . $e->getMessage());
      $login_err = "Login system temporarily unavailable. Please try again later.";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - Coffee's Life</title>
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="admin-body">
  <div class="admin-login-container">
    <div class="admin-login-header">
      <h1><i class="fas fa-shield-alt"></i> Admin Login</h1>
      <p>Coffee's Life Administration Panel</p>
    </div>

    <?php if (!empty($login_err)): ?>
      <div class="message error">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo htmlspecialchars($login_err); ?>
      </div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group">
        <label for="username">
          <i class="fas fa-user"></i> Username
        </label>
        <input type="text" id="username" name="username"
          value="<?php echo htmlspecialchars($username); ?>"
          placeholder="Enter admin username" required>
        <?php if ($username_err): ?>
          <span class="error"><?php echo $username_err; ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="password">
          <i class="fas fa-lock"></i> Password
        </label>
        <input type="password" id="password" name="password"
          placeholder="Enter admin password" required>
        <?php if ($password_err): ?>
          <span class="error"><?php echo $password_err; ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary" style="width: 100%;">
          <i class="fas fa-sign-in-alt"></i> Login to Admin Panel
        </button>
      </div>
    </form>

    <div style="text-align: center; margin-top: 20px;">
      <a href="index.php" style="color: #6f4e37; text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Back to Website
      </a>
    </div>
  </div>

  <script>
    // Auto-hide messages after 5 seconds
    setTimeout(function() {
      const messages = document.querySelectorAll('.message');
      messages.forEach(message => {
        message.style.opacity = '0';
        setTimeout(() => message.remove(), 300);
      });
    }, 5000);
  </script>
</body>

</html>
