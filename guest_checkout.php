<?php
// guest_checkout.php
session_start(); // Start the session at the very beginning
require_once 'functions.php'; // Include functions if needed for other validation

// Determine where to redirect after successful guest info entry
$redirect_after_guest = "menu.php"; // Default redirect
if (isset($_GET['redirect'])) {
    $redirect_after_guest = htmlspecialchars($_GET['redirect']);
}

// If user is already logged in or is already a guest, redirect to the determined page
if ((isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) || (isset($_SESSION['is_guest']) && $_SESSION['is_guest'] === true)) {
    header("Location: " . $redirect_after_guest);
    exit;
}

$contact_number = "";
$contact_number_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate contact number
    if (empty(trim($_POST["contact_number"]))) {
        $contact_number_err = "Please provide your mobile phone number to proceed as a guest.";
    } elseif (!preg_match("/^\+?\d{8,15}$/", trim($_POST["contact_number"]))) { // Simple regex for phone number validation
        $contact_number_err = "Please enter a valid mobile number.";
    } else {
        $contact_number = trim($_POST["contact_number"]);
        $_SESSION['is_guest'] = true;
        $_SESSION['guest_contact_number'] = $contact_number;
        $_SESSION['username'] = "Guest"; // Set a placeholder username for guests

        header("Location: " . $redirect_after_guest); // Redirect guests to the determined page
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Continue as Guest - BeanMarket</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Specific styles for guest_checkout.php */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f2ee; /* Light coffee cream background */
        }

        .guest-container {
            max-width: 450px;
            width: 100%;
            padding: 35px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .guest-container h2 {
            text-align: center;
            color: #343a40;
            margin-bottom: 30px;
            font-size: 2em;
        }

        .guest-container p {
            font-size: 0.95em;
            color: #666;
            margin-bottom: 25px;
            line-height: 1.6;
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

        .form-group input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-size: 1em;
            box-sizing: border-box;
        }

        .form-group input[type="text"]:focus {
            border-color: #8B4513; /* SaddleBrown on focus */
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
            background-color: #6F4E37; /* Coffee brown */
            color: white;
            border: none;
        }

        .form-group .btn:hover {
            background-color: #4A2B1D; /* Dark coffee color */
            transform: translateY(-2px);
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
            .guest-container {
                margin: 40px auto;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="guest-container">
        <h2>Continue as Guest</h2>
        <p>
            Please provide your mobile phone number. This helps us verify your order and contact you regarding any issues, ensuring a smooth experience. Your privacy is important to us.
        </p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . (isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''); ?>" method="post">
            <div class="form-group">
                <label for="contact_number">Mobile Phone Number</label>
                <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>" placeholder="e.g., +60123456789">
                <span class="error"><?php echo $contact_number_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn primary" value="Proceed to Cart">
            </div>
        </form>
        <div class="return-button">
            <a href="checkout_prompt.php">Return to Checkout Options</a>
        </div>
    </div>
</body>
</html>
