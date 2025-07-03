<?php
// Database configuration
$host = "localhost";     // or your host
$dbname = "coffee_life";
$username = "root";      // your DB username
$password = "";          // your DB password

// Create PDO connection (for reviews system)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("PDO Database connection failed: " . $e->getMessage());
    if (isset($_ENV['ENVIRONMENT']) && $_ENV['ENVIRONMENT'] === 'production') {
        die("Database connection error. Please try again later.");
    } else {
        die("PDO Connection failed: " . $e->getMessage());
    }
}

// Create MySQLi connection (for legacy compatibility)
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Log the error for development/debugging (don't show to users in production)
    error_log("Database connection failed: " . $conn->connect_error);

    // For production, show a generic error message
    if (isset($_ENV['ENVIRONMENT']) && $_ENV['ENVIRONMENT'] === 'production') {
        die("Database connection error. Please try again later.");
    } else {
        // For development, show detailed error
        die("Connection failed: " . $conn->connect_error);
    }
}

// Set charset to UTF-8 for security
$conn->set_charset("utf8");
