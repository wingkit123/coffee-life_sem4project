<?php
// user_login.php
session_start(); // Start the session at the very beginning
require_once 'functions.php';

// Determine where to redirect after successful login
$redirect_after_login = "user_dashboard.php"; // Default redirect to dashboard
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - BeanMarket</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Specific styles for user_login.php */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f2ee;
            /* Light coffee cream background */
        }

        .login-container {
            max-width: 450px;
            width: 100%;
            padding: 35px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .login-container h2 {
            text-align: center;
            color: #343a40;
            margin-bottom: 30px;
            font-size: 2em;
        }

        .login-error {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-size: 1em;
            box-sizing: border-box;
            /* Ensures padding doesn't affect total width */
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="password"]:focus {
            border-color: #8B4513;
            /* SaddleBrown on focus */
            outline: none;
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.25);
        }

        .form-group .error {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
            display: block;
        }

        .form-group .btn {
            width: 100%;
            padding: 12px;
            font-size: 1.1em;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn.primary {
            background-color: #6F4E37;
            /* Coffee brown */
            color: white;
            border: none;
        }

        .btn.primary:hover {
            background-color: #4A2B1D;
            /* Dark coffee color */
            transform: translateY(-2px);
        }

        .link {
            margin-top: 25px;
            text-align: center;
            font-size: 0.95em;
            color: #666;
        }

        .link a {
            font-weight: bold;
            color: #8B4513;
            /* SaddleBrown */
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }

        .return-button {
            margin-top: 20px;
        }

        .return-button a {
            display: inline-block;
            background-color: #6c757d;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .return-button a:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            text-decoration: none;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .login-container {
                margin: 40px auto;
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Login to Your Account</h2>
        <?php
        if (!empty($login_err)) {
            echo '<p class="login-error">' . $login_err . '</p>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . (isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''); ?>" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" autocomplete="username">
                <span class="error"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" autocomplete="current-password">
                <span class="error"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn primary" value="Login">
            </div>
            <p class="link">Don't have an account? <a href="register.php">Register here</a>.</p>
        </form>
        <div class="return-button">
            <a href="checkout_prompt.php">Return to Checkout Options</a>
        </div>
    </div>
</body>

</html>