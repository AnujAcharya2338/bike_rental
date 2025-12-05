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

// Get booking
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    redirect(BASE_URL . 'user/dashboard.php');
}

// Get bike info
$bike = getBikeById($conn, $booking['bike_id']);

$errors = [];
$total_price = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pickup_date = trim($_POST['pickup_date'] ?? '');
    $return_date = trim($_POST['return_date'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
    if (empty($pickup_date) || !isValidDate($pickup_date)) $errors[] = "Valid pickup date is required";
    if (empty($return_date) || !isValidDate($return_date)) $errors[] = "Valid return date is required";

    if (!empty($pickup_date) && !empty($return_date)) {
        if (strtotime($return_date) <= strtotime($pickup_date)) {
            $errors[] = "Return date must be after pickup date";
        }
    }

    // Update booking if no errors
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE bookings SET pickup_date=?, return_date=?, message=? WHERE id=? AND user_id=?");
        $stmt->bind_param("sssii", $pickup_date, $return_date, $message, $booking_id, $user_id);

        if ($stmt->execute()) {
            header("Location: ../user/dashboard.php?msg=Booking updated successfully!");
            exit;
        } else {
            $errors[] = "Failed to update booking. Please try again.";
        }
    }
}

// Calculate total if both dates are set
if (!empty($_POST['pickup_date']) && !empty($_POST['return_date'])) {
    $days = calculateRentalDays($_POST['pickup_date'], $_POST['return_date']);
    $total_price = calculateTotalPrice($bike['price_per_day'], $days);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking - Bike Rental System</title>
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/bookings.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container flex-between">
            <h2>Bike Rental System</h2>
            <div class="nav-links">
                <a href="../user/bikes.php">Browse Bikes</a>
                <a href="../user/dashboard.php">Dashboard</a>
                <a href="../user/logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <main class="hello">
        <div class="booking-header">
            <a href="../user/dashboard.php" class="back-link">← Back to Dashboard</a>
            <h1>Edit Booking for <?php echo htmlesc($bike['name']); ?></h1>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlesc($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="booking-form-wrapper">
            <div class="bike-summary">
                <h3>Booking Details</h3>
                <div class="summary-item">
                    <span>Bike:</span>
                    <strong><?php echo htmlesc($bike['name']); ?></strong>
                </div>
                <div class="summary-item">
                    <span>Status:</span>
                    <strong><?php echo ucfirst(htmlesc($booking['status'])); ?></strong>
                </div>
                <div class="summary-item">
                    <span>Price per Day:</span>
                    <strong>Rs <?php echo number_format($bike['price_per_day'], 2); ?></strong>
                </div>
                <?php if ($total_price > 0): ?>
                    <div class="summary-item total">
                        <span>Total Price:</span>
                        <strong>$<?php echo number_format($total_price, 2); ?></strong>
                    </div>
                <?php endif; ?>
            </div>

            <form method="POST" class="booking-form">
                <div class="form-group">
                    <label for="pickup_date">Pickup Date *</label>
                    <input type="date" id="pickup_date" name="pickup_date" value="<?php echo htmlesc($_POST['pickup_date'] ?? $booking['pickup_date']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="return_date">Return Date *</label>
                    <input type="date" id="return_date" name="return_date" value="<?php echo htmlesc($_POST['return_date'] ?? $booking['return_date']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="message">Additional Message</label>
                    <textarea id="message" name="message"><?php echo htmlesc($_POST['message'] ?? $booking['message']); ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-secondary">Update Booking</button>
                    <a href="../user/dashboard.php" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
