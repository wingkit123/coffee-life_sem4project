<?php
// checkout_prompt.php
session_start(); // Start the session

// If user is already logged in or is a guest, they should go directly to the cart (or final checkout page)
// In this flow, we'll redirect them back to view_cart.php
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: view_cart.php");
    exit;
}
if (isset($_SESSION['is_guest']) && $_SESSION['is_guest'] === true) {
    header("Location: view_cart.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Options - BeanMarket</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Specific styles for checkout_prompt.php */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f2ee; /* Light coffee cream background */
        }

        .prompt-container {
            max-width: 500px;
            width: 100%;
            padding: 35px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .prompt-container h1 {
            color: #343a40;
            margin-bottom: 25px;
            font-size: 2.2em;
        }

        .prompt-container p {
            font-size: 1.1em;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .prompt-actions {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .prompt-actions .btn {
            padding: 15px;
            font-size: 1.2em;
            font-weight: bold;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .prompt-actions .btn.primary {
            background-color: #6F4E37; /* Coffee brown */
            color: white;
            border: none;
        }

        .prompt-actions .btn.primary:hover {
            background-color: #4A2B1D; /* Dark coffee color */
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .prompt-actions .btn.secondary {
            background-color: #e9ecef;
            color: #495057;
            border: 1px solid #ced4da;
        }

        .prompt-actions .btn.secondary:hover {
            background-color: #dee2e6;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .back-to-cart {
            margin-top: 30px;
            font-size: 0.95em;
        }

        .back-to-cart a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .back-to-cart a:hover {
            text-decoration: underline;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .prompt-container {
                margin: 20px;
                padding: 20px;
            }
            .prompt-container h1 {
                font-size: 1.8em;
            }
            .prompt-container p {
                font-size: 1em;
            }
            .prompt-actions .btn {
                font-size: 1.1em;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container prompt-container">
        <h1>How would you like to proceed?</h1>
        <p>
            To finalize your order, you can either log in to your existing account for a personalized experience, or continue as a guest.
        </p>
        <div class="prompt-actions">
            <a href="user_login.php?redirect=view_cart.php" class="btn primary">Login to Your Account</a>
            <a href="guest_checkout.php?redirect=view_cart.php" class="btn secondary">Continue as Guest</a>
        </div>
        <p class="back-to-cart">
            <a href="view_cart.php">No, I want to review my cart again.</a>
        </p>
    </div>
</body>
</html>
