<?php
// admin_logout.php - Admin logout functionality
session_start();

// Clear admin session
$_SESSION = array();
session_destroy();

// Redirect to admin login
header("Location: admin_login.php");
exit;
