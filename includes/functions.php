<?php
// Validation and Helper Functions

// Escape output for security
function htmlesc($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Check if user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Redirect function
function redirect($url) {
    header("Location: " . htmlesc($url));
    exit();
}

// Get bike by ID
function getBikeById($conn, $bike_id) {
    $stmt = $conn->prepare("SELECT * FROM bikes WHERE id = ?");
    $stmt->bind_param("i", $bike_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Get all bikes
function getAllBikes($conn) {
    $result = $conn->query("SELECT * FROM bikes ORDER BY created_at DESC");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get user bookings
function getUserBookings($conn, $user_id) {
    $stmt = $conn->prepare("
        SELECT b.*, bikes.name AS bike_name, bikes.model, bikes.price_per_day 
        FROM bookings b
        JOIN bikes ON b.bike_id = bikes.id
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Get all bookings (admin)
function getAllBookings($conn) {
    $result = $conn->query("
        SELECT b.*, users.fullname, users.email, bikes.name AS bike_name, bikes.model
        FROM bookings b
        JOIN users ON b.user_id = users.id
        JOIN bikes ON b.bike_id = bikes.id
        ORDER BY b.created_at DESC
    ");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validate date format (YYYY-MM-DD)
function isValidDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

// Calculate rental days
function calculateRentalDays($pickup, $return) {
    $start = new DateTime($pickup);
    $end = new DateTime($return);
    $interval = $start->diff($end);
    return $interval->days + 1;
}

// Calculate total price
function calculateTotalPrice($price_per_day, $days) {
    return $price_per_day * $days;
}
?>
