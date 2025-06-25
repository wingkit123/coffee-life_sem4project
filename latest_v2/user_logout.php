<?php
// user_logout.php
session_start(); // Start the session
$_SESSION = array(); // Unset all of the session variables
session_destroy(); // Destroy the session
header("Location: user_login.php"); // Redirect to user login page
exit;
?>