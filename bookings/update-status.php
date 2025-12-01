<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['status'])) {
    redirect(BASE_URL . 'bookings/admin-list.php');
}

$booking_id = (int)$_GET['id'];
$new_status = trim($_GET['status']);

// Validate status
$valid_statuses = ['pending', 'confirmed', 'cancelled'];
if (!in_array($new_status, $valid_statuses)) {
    redirect(BASE_URL . 'bookings/admin-list.php');
}

// Update booking status
$stmt = $conn->prepare("UPDATE bookings SET status=? WHERE id=?");
$stmt->bind_param("si", $new_status, $booking_id);

if ($stmt->execute()) {
    header("Location: admin-list.php?msg=Booking status updated to " . ucfirst($new_status));
} else {
    header("Location: admin-list.php?msg=Failed to update booking status");
}
exit;
?>
