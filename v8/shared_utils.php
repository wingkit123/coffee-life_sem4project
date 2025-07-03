<?php
/**
 * ===== SHARED UTILITIES AND HELPERS =====
 * Common functions used across multiple UI files
 * This reduces code duplication and provides consistency
 */

/**
 * Get the current page name for navigation highlighting
 * @return string The current page filename
 */
function getCurrentPage() {
    return basename($_SERVER['PHP_SELF']);
}

/**
 * Get dynamic paths based on current directory
 * @return array Associative array with path prefixes
 */
function getPagePaths() {
    $isUIFolder = (strpos($_SERVER['REQUEST_URI'], '/UI/') !== false);
    
    return [
        'css' => $isUIFolder ? '../css/' : 'css/',
        'js' => $isUIFolder ? '../js/' : 'js/',
        'ui' => $isUIFolder ? '' : 'UI/',
        'logo' => $isUIFolder ? '../logo1.png' : 'logo1.png',
        'uploads' => $isUIFolder ? '../uploads/' : 'uploads/',
        'functions' => $isUIFolder ? '../' : '',
        'config' => $isUIFolder ? '../config.php' : 'config.php',
        'header' => $isUIFolder ? '../header.php' : 'header.php',
        'footer' => $isUIFolder ? '../footer.php' : 'footer.php'
    ];
}

/**
 * Display alert messages with consistent styling
 * @param string $message The message to display
 * @param string $type The alert type (success, error, warning, info)
 * @param bool $dismissible Whether the alert can be dismissed
 */
function displayAlert($message, $type = 'info', $dismissible = true) {
    $dismissClass = $dismissible ? 'alert-dismissible' : '';
    echo "<div class='alert alert-{$type} {$dismissClass}'>";
    echo htmlspecialchars($message);
    if ($dismissible) {
        echo "<button type='button' class='close' onclick='this.parentElement.remove()'>&times;</button>";
    }
    echo "</div>";
}

/**
 * Start session if not already started
 */
function ensureSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Check if user is logged in
 * @return bool True if user is logged in
 */
function isUserLoggedIn() {
    ensureSession();
    return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
}

/**
 * Check if user is guest
 * @return bool True if user is continuing as guest
 */
function isGuest() {
    ensureSession();
    return isset($_SESSION['is_guest']) && $_SESSION['is_guest'] === true;
}

/**
 * Get cart item count
 * @return int Number of items in cart
 */
function getCartCount() {
    ensureSession();
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        return array_sum($_SESSION['cart']);
    }
    return 0;
}

/**
 * Validate and sanitize input
 * @param string $input The input to sanitize
 * @param string $type The type of validation (email, text, number, etc.)
 * @return string|false The sanitized input or false if invalid
 */
function validateInput($input, $type = 'text') {
    $input = trim($input);
    
    switch ($type) {
        case 'email':
            return filter_var($input, FILTER_VALIDATE_EMAIL);
        case 'number':
            return filter_var($input, FILTER_VALIDATE_INT);
        case 'float':
            return filter_var($input, FILTER_VALIDATE_FLOAT);
        case 'url':
            return filter_var($input, FILTER_VALIDATE_URL);
        case 'text':
        default:
            return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Format currency for display
 * @param float $amount The amount to format
 * @param string $currency The currency symbol
 * @return string Formatted currency string
 */
function formatCurrency($amount, $currency = 'RM') {
    return $currency . ' ' . number_format($amount, 2);
}

/**
 * Generate a secure random token
 * @param int $length The length of the token
 * @return string The generated token
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Log error messages consistently
 * @param string $message The error message
 * @param string $file The file where error occurred
 * @param int $line The line number where error occurred
 */
function logError($message, $file = '', $line = 0) {
    $logMessage = date('Y-m-d H:i:s') . " - ERROR: {$message}";
    if ($file) {
        $logMessage .= " in {$file}";
    }
    if ($line) {
        $logMessage .= " on line {$line}";
    }
    error_log($logMessage);
}

/**
 * Redirect with message
 * @param string $location The URL to redirect to
 * @param string $message Optional message to set in session
 * @param string $messageType Type of message (success, error, warning, info)
 */
function redirectWithMessage($location, $message = '', $messageType = 'info') {
    if ($message) {
        ensureSession();
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $messageType;
    }
    header("Location: {$location}");
    exit;
}

/**
 * Display and clear flash messages
 */
function displayFlashMessage() {
    ensureSession();
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        displayAlert($message, $type);
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
    }
}

/**
 * Get page title based on current page
 * @param string $currentPage The current page filename
 * @return string The page title
 */
function getPageTitle($currentPage) {
    $titles = [
        'UI1_index.php' => 'Home - Coffee\'s Life',
        'UI2_menu.php' => 'Menu - Coffee\'s Life',
        'UI3_product_details.php' => 'Product Details - Coffee\'s Life',
        'UI4_view_cart.php' => 'Shopping Cart - Coffee\'s Life',
        'UI5_user_login.php' => 'Login - Coffee\'s Life',
        'UI6_register.php' => 'Register - Coffee\'s Life',
        'UI7_user_profile.php' => 'Profile - Coffee\'s Life',
        'UI8_guest_checkout.php' => 'Checkout - Coffee\'s Life',
        'UI9_about_us.php' => 'About Us - Coffee\'s Life',
        'UI10_contact_us.php' => 'Contact Us - Coffee\'s Life',
        'UI11_rating_comments.php' => 'Reviews - Coffee\'s Life'
    ];
    
    return $titles[$currentPage] ?? 'Coffee\'s Life - Premium Coffee & Snacks';
}

/**
 * Generate navigation active class
 * @param string $currentPage Current page filename
 * @param string $targetPage Target page filename
 * @return string 'active' if pages match, empty string otherwise
 */
function getActiveClass($currentPage, $targetPage) {
    return ($currentPage === $targetPage) ? 'active' : '';
}

/**
 * Include CSS files with proper paths
 * @param array $cssFiles Array of CSS filenames
 * @param string $basePath Optional base path override
 */
function includeCSSFiles($cssFiles, $basePath = null) {
    if (!$basePath) {
        $paths = getPagePaths();
        $basePath = $paths['css'];
    }
    
    foreach ($cssFiles as $cssFile) {
        echo "<link rel=\"stylesheet\" href=\"{$basePath}{$cssFile}\">\n";
    }
}

/**
 * Include JavaScript files with proper paths
 * @param array $jsFiles Array of JS filenames
 * @param string $basePath Optional base path override
 */
function includeJSFiles($jsFiles, $basePath = null) {
    if (!$basePath) {
        $paths = getPagePaths();
        $basePath = $paths['js'];
    }
    
    foreach ($jsFiles as $jsFile) {
        echo "<script src=\"{$basePath}{$jsFile}\"></script>\n";
    }
}
?>
