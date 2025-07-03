<?php
// header.php - Universal header for BeanMarket website
// Include shared utilities
require_once 'shared_utils.php';

// Start session if not already started
ensureSession();

// Include functions if not already included
if (!function_exists('connectDatabase')) {
  try {
    require_once 'functions/functions.php';
  } catch (Exception $e) {
    // If database connection fails, continue without functions.php
    logError("Database connection failed in header.php: " . $e->getMessage());
  }
}

// Get current page name and paths
$current_page = getCurrentPage();
$paths = getPagePaths();
$page_title = getPageTitle($current_page);

// Set specific page titles
switch ($current_page) {
  case 'UI1_index.php':
    $page_title = "Coffee's Life - Home | BeanMarket";
    break;
  case 'UI9_about_us.php':
    $page_title = "About Us - BeanMarket";
    break;
  case 'UI2_menu.php':
    $page_title = "Menu - Coffee & Snacks | BeanMarket";
    break;
  case 'UI10_contact_us.php':
    $page_title = "Contact Us - BeanMarket";
    break;
  case 'UI5_user_login.php':
    $page_title = "Login / Register - BeanMarket";
    break;
  case 'UI7_user_profile.php':
    $page_title = "Dashboard - BeanMarket";
    break;
  case 'UI4_view_cart.php':
    $page_title = "Shopping Cart - BeanMarket";
    break;
  case 'UI11_rating_comments.php':
    $page_title = "Reviews - BeanMarket";
    break;
  default:
    $page_title = "BeanMarket - Coffee & Snacks";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($page_title); ?></title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="favicon.ico">

  <!-- Stylesheets -->
  <!-- Coffee's Life - Modular CSS Structure -->
  <link rel="stylesheet" href="<?php echo $paths['css']; ?>global.css">
  <link rel="stylesheet" href="<?php echo $paths['css']; ?>header.css">
  <link rel="stylesheet" href="<?php echo $paths['css']; ?>home_page.css">
  <link rel="stylesheet" href="<?php echo $paths['css']; ?>components.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <!-- Global JavaScript -->
  <script src="<?php echo $paths['js']; ?>global.js"></script>
</head>

<body>
  <!-- Universal Background Music - Commented out as per simplification -->
  <!-- <audio id="backgroundMusic" loop>
    <source src="https://www.soundjay.com/misc/sounds/cafe-ambience.mp3" type="audio/mpeg">
    <source src="https://www.bensound.com/bensound-music/bensound-jazzyfrenchy.mp3" type="audio/mpeg">
    Your browser does not support the audio element.
  </audio> -->

  <header class="hero"> <!-- Always use 'hero' class for a consistent header as in index.php -->
    <div class="hero-content"> <!-- Always use 'hero-content' class -->
      <!-- Logo Section -->
      <div class="logo-section">
        <a href="<?php echo $paths['ui']; ?>UI1_index.php" class="logo-home-link">
          <img src="<?php echo $paths['logo']; ?>" alt="Coffee's Life - BeanMarket Logo" width="360" height="80" loading="lazy">
        </a>
      </div>

      <!-- Universal Controls - Commented out as per simplification -->
      <!-- <div class="header-controls">
        Music Control Button
        <div class="music-control">
          <button id="musicToggle" class="music-btn" onclick="toggleMusic()" aria-label="Toggle background music">
            <i class="fas fa-music" id="musicIcon"></i>
            <span id="musicText">Play Cafe Ambience</span>
          </button>
        </div>
      </div> -->


    </div>
  </header>

  <!-- Universal Menu Toggle Button -->
  <button class="menu-toggle" onclick="toggleMenu()" aria-label="Toggle navigation menu">
    <i class="fas fa-bars"></i>
    <span>Menu</span>
  </button>

  <!-- Universal Side Navigation Menu -->
  <nav class="side-menu" id="sideMenu">
    <div class="menu-header">
      <h3><i class="fas fa-coffee"></i> Coffee's Life</h3>
      <button class="menu-close" onclick="toggleMenu()" aria-label="Close menu">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <ul>
      <li><a href="<?php echo $paths['ui']; ?>UI1_index.php" class="<?php echo getActiveClass($current_page, 'UI1_index.php'); ?>">
          <i class="fas fa-home"></i> Home</a></li>
      <li><a href="<?php echo $paths['ui']; ?>UI2_menu.php" class="<?php echo getActiveClass($current_page, 'UI2_menu.php'); ?>">
          <i class="fas fa-coffee"></i> Menu</a></li>
      <li><a href="<?php echo $paths['ui']; ?>UI9_about_us.php" class="<?php echo getActiveClass($current_page, 'UI9_about_us.php'); ?>">
          <i class="fas fa-info-circle"></i> About Us</a></li>
      <li><a href="<?php echo $paths['ui']; ?>UI10_contact_us.php" class="<?php echo getActiveClass($current_page, 'UI10_contact_us.php'); ?>">
          <i class="fas fa-envelope"></i> Contact Us</a></li>
      <li><a href="<?php echo $paths['ui']; ?>UI11_rating_comments.php" class="<?php echo getActiveClass($current_page, 'UI11_rating_comments.php'); ?>">
          <i class="fas fa-star"></i> Reviews</a></li>

      <!-- Cart Section -->
      <li class="cart-section">
        <a href="<?php echo $paths['ui']; ?>UI4_view_cart.php" class="<?php echo getActiveClass($current_page, 'UI4_view_cart.php'); ?>">
          <i class="fas fa-shopping-cart"></i>
          Cart
          <?php
          // Display cart count if items exist
          $cartCount = getCartCount();
          if ($cartCount > 0) {
            echo "<span class='cart-count'>$cartCount</span>";
          }
          ?>
        </a>
      </li>

      <!-- User Account Section -->
      <?php if (isUserLoggedIn()): ?>
        <li><a href="<?php echo $paths['ui']; ?>UI7_user_profile.php" class="<?php echo getActiveClass($current_page, 'UI7_user_profile.php'); ?>">
            <i class="fas fa-user-circle"></i> Dashboard</a></li>
        <li><a href="<?php echo $paths['ui']; ?>user_logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout</a></li>
      <?php else: ?>
        <li><a href="<?php echo $paths['ui']; ?>UI5_user_login.php" class="<?php echo getActiveClass($current_page, 'UI5_user_login.php'); ?>">
            <i class="fas fa-sign-in-alt"></i> Login / Register</a></li>
      <?php endif; ?>
    </ul>
  </nav>
