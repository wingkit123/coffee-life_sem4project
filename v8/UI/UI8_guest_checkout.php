<?php
// UI8_guest_checkout.php - Guest checkout page
session_start();
require_once '../functions/functions.php';

// Determine where to redirect after successful guest info entry
$redirect_after_guest = "UI4_view_cart.php"; // Default redirect to cart
if (isset($_GET['redirect'])) {
    $redirect_after_guest = htmlspecialchars($_GET['redirect']);
}

// If user is already logged in or is already a guest, redirect to the determined page
if ((isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) || 
    (isset($_SESSION['is_guest']) && $_SESSION['is_guest'] === true)) {
    header("Location: " . $redirect_after_guest);
    exit;
}

$contact_number = "";
$contact_number_err = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate contact number
    if (empty(trim($_POST["contact_number"]))) {
        $contact_number_err = "Please provide your mobile phone number to proceed as a guest.";
    } elseif (!preg_match("/^\+?\d{8,15}$/", trim($_POST["contact_number"]))) {
        $contact_number_err = "Please enter a valid mobile number (8-15 digits).";
    } else {
        $contact_number = trim($_POST["contact_number"]);
        $_SESSION['is_guest'] = true;
        $_SESSION['guest_contact_number'] = $contact_number;
        $_SESSION['username'] = "Guest";

        $success_message = "Guest information saved successfully!";
        header("Location: " . $redirect_after_guest);
        exit;
    }
}

include '../header.php';
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="../css/user-login.css">
<link rel="stylesheet" href="../css/components.css">

<main class="auth-main">
    <div class="auth-container">
        <div class="auth-header">
            
            <h1><i class="fas fa-user-friends auth-icon"></i>Continue as Guest</h1>
            <p class="auth-subtitle">Quick checkout without creating an account</p>
        </div>

        <?php if (!empty($contact_number_err)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $contact_number_err; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <div class="auth-card">
            <div class="auth-form-section">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . (isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''); ?>" method="post" class="auth-form">
                    
                    <div class="form-group">
                        <label for="contact_number">
                            <i class="fas fa-phone"></i>
                            Mobile Phone Number *
                        </label>
                        <input type="tel" 
                               id="contact_number" 
                               name="contact_number" 
                               value="<?php echo htmlspecialchars($contact_number); ?>" 
                               placeholder="e.g., +60123456789 or 0123456789"
                               class="form-input <?php echo (!empty($contact_number_err)) ? 'error' : ''; ?>"
                               required>
                        <small class="form-help">
                            <i class="fas fa-info-circle"></i>
                            We'll use this number to contact you about your order if needed
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="fas fa-shopping-cart"></i>
                        Proceed to Cart
                    </button>
                </form>
            </div>

            <div class="auth-options">
                <div class="divider">
                    
                </div>
                
                <div class="auth-links">
                    <a href="UI5_user_login.php?redirect=UI4_view_cart.php" class="btn btn-outline btn-full">
                        <i class="fas fa-arrow-left"></i>
                        Back to Login Options
                    </a>
                    
                    <a href="UI5_user_login.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>" class="btn btn-secondary btn-full">
                        <i class="fas fa-user-plus"></i>
                        Create Account Instead
                    </a>
                </div>
            </div>
        </div>

        <div class="auth-info">
            <div class="info-card">
                <h3><i class="fas fa-shield-alt"></i> Why do we need your phone number?</h3>
                <ul>
                    <li><i class="fas fa-check"></i> Order confirmation and updates</li>
                    <li><i class="fas fa-check"></i> Delivery coordination (if applicable)</li>
                    <li><i class="fas fa-check"></i> Issue resolution support</li>
                    <li><i class="fas fa-check"></i> Your privacy is protected</li>
                </ul>
            </div>
        </div>
    </div>
</main>

<?php include '../footer.php'; ?>
