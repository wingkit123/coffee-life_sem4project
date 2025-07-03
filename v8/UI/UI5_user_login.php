<?php
// user_login.php
session_start(); // Start the session at the very beginning
require_once '../functions/functions.php';

// Determine where to redirect after successful login
$redirect_after_login = "UI7_user_profile.php"; // Default redirect to profile
if (isset($_GET['redirect'])) {
    $redirect_after_login = htmlspecialchars($_GET['redirect']);
}

// If user is already logged in, redirect to the determined page
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: " . $redirect_after_login);
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
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        if (loginUser($username, $password)) {
            // loginUser function handles session creation
            header("Location: " . $redirect_after_login); // Redirect to the determined page after successful login
            exit;
        } else {
            $login_err = "Invalid username or password.";
        }
    }
}

include '../header.php';
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="../css/user-login.css">

<body class="login-body">
    <div class="container login-container">
        <div class="login-header">
            <h1 style="color: white;">Welcome Back</h1>
            <p style="color: white;" >Login to your Coffee's Life account</p>
        </div>

        <?php if (!empty($login_err)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($login_err); ?>
            </div>
        <?php endif; ?>

        <div class="login-form-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . (isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''); ?>" method="post" class="login-form">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" id="username" name="username"
                        value="<?php echo htmlspecialchars($username); ?>"
                        autocomplete="username" required
                        placeholder="Enter your username">
                    <?php if ($username_err): ?>
                        <span class="error"><i class="fas fa-exclamation-triangle"></i> <?php echo $username_err; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password"
                        autocomplete="current-password" required
                        placeholder="Enter your password">
                    <?php if ($password_err): ?>
                        <span class="error"><i class="fas fa-exclamation-triangle"></i> <?php echo $password_err; ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login to Account
                </button>
            </form>
        </div>

        <div class="login-options">
            <div class="register-option">
                <h3><i class="fas fa-user-plus"></i> New to Coffee's Life?</h3>
                <p>Create an account to track your orders and earn rewards!</p>
                <a href="UI6_register.php" class="btn btn-secondary">
                    <i class="fas fa-user-plus"></i> Create Account
                </a>
            </div>

            <div class="guest-option">
                <h3><i class="fas fa-user-secret"></i> Continue as Guest</h3>
                <p>No account needed - proceed with your order quickly!</p>
                <a href="UI8_guest_checkout.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>" class="btn btn-outline">
                    <i class="fas fa-shopping-cart"></i> Guest Checkout
                </a>
            </div>
        </div>

        <div class="back-options">
            <a href="<?php echo isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : 'UI1_index.php'; ?>" class="back-link">
                <i class="fas fa-arrow-left"></i> Go Back
            </a>
        </div>
    </div>
</body>

</html>
