<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isUserLoggedIn()) {
    redirect(BASE_URL . 'user/login.php');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect(BASE_URL . 'user/dashboard.php');
}

$user_id = $_SESSION['user_id'];
$booking_id = (int)$_GET['id'];

// Get booking first
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    redirect(BASE_URL . 'user/dashboard.php');
}

// Only allow deletion of pending bookings
if ($booking['status'] !== 'pending') {
    redirect(BASE_URL . 'user/dashboard.php?msg=Only pending bookings can be cancelled');
}

// Delete booking
$stmt = $conn->prepare("DELETE FROM bookings WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $booking_id, $user_id);

if ($stmt->execute()) {
    header("Location: ../user/dashboard.php?msg=Booking cancelled successfully");
} else {
    header("Location: ../user/dashboard.php?msg=Failed to cancel booking");
}
exit;
?>
