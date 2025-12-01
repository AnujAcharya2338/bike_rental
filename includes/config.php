<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bike_rental_system');

// Create connection using mysqli
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

// Session configuration
session_start();

// Define base URL
define('BASE_URL', 'http://localhost/bike-rental-system/');

// Define upload directory
define('UPLOAD_DIR', __DIR__ . '/../assets/images/bikes/');
define('UPLOAD_URL', BASE_URL . 'assets/images/bikes/');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
?>
