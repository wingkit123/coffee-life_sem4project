<?php
// register.php
require_once 'functions.php';

$username = $email = $contact_number = $password = $confirm_password = $address = ""; // Added $address
$username_err = $email_err = $contact_number_err = $password_err = $confirm_password_err = $address_err = ""; // Added $address_err

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } elseif (checkUserExists('username', trim($_POST["username"]))) {
        $username_err = "This username is already taken.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email format.";
    } elseif (checkUserExists('email', trim($_POST["email"]))) {
        $email_err = "This email is already registered.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate contact number
    if (empty(trim($_POST["contact_number"]))) {
        $contact_number_err = "Please enter a contact number.";
    } elseif (checkUserExists('contact_number', trim($_POST["contact_number"]))) {
        $contact_number_err = "This contact number is already registered.";
    } else {
        $contact_number = trim($_POST["contact_number"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate Address (Optional: make it required if needed)
    if (empty(trim($_POST["address"]))) {
        $address_err = "Please enter your address."; // Or leave empty if optional
    } else {
        $address = trim($_POST["address"]);
    }

    // If no errors, proceed to register user
    if (empty($username_err) && empty($email_err) && empty($contact_number_err) && empty($password_err) && empty($confirm_password_err) && empty($address_err)) { // Added address_err
        if (registerUser($username, $password, $email, $contact_number, $address)) { // Added $address
            header("Location: user_login.php");
            exit;
        } else {
            // Consider displaying a more user-friendly message, e.g., "Registration failed. Please try again."
            // Instead of echoing directly, you might store it in a session variable or a variable to display in HTML.
            echo "Something went wrong. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Specific styles for register.php */
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .register-container h2 {
            text-align: center;
            color: #343a40;
            margin-bottom: 25px;
        }

        .link {
            margin-top: 25px;
            text-align: center;
        }

        .link a {
            font-weight: bold;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .register-container {
                margin: 20px auto;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register New Account</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                <span class="error"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <span class="error"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>">
                <span class="error"><?php echo $contact_number_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <span class="error"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password">
                <span class="error"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="4"><?php echo htmlspecialchars($address); ?></textarea>
                <span class="error"><?php echo $address_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn success" value="Register">
            </div>
            <p class="link">Already have an account? <a href="user_login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
