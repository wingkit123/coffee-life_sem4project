<?php
// admin/logout.php
session_start();
$_SESSION = array(); // Unset all of the session variables
session_destroy(); // Destroy the session
header("Location: login.php"); // Redirect to login page
exit;
?>