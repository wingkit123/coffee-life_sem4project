<?php
$host = "localhost";     // or your host
$dbname = "beans_cafe";
$username = "root";      // your DB username
$password = "";          // your DB password

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
