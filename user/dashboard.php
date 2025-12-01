<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isUserLoggedIn()) {
    redirect(BASE_URL . 'user/login.php');
}

$user_id = $_SESSION['user_id'];
$bookings = getUserBookings($conn, $user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Bike Rental System</title>
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/user-dashboard.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container flex-between">
            <h2>Bike Rental System</h2>
            <div class="nav-links">
                <a href="bikes.php">Browse Bikes</a>
                <a href="dashboard.php" class="active">Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="dashboard-header">
            <h1>Welcome, <?php echo htmlesc($_SESSION['user_name']); ?>!</h1>
            <p>Manage your bike bookings here</p>
        </div>

        <div class="bookings-section">
            <h2>Your Bookings</h2>

            <?php if (empty($bookings)): ?>
                <div class="empty-state">
                    <p>You haven't made any bookings yet.</p>
                    <a href="bikes.php" class="btn">Browse Available Bikes</a>
                </div>
            <?php else: ?>
                <div class="bookings-grid">
                    <?php foreach ($bookings as $booking): ?>
                        <div class="booking-card">
                            <div class="booking-header">
                                <h3><?php echo htmlesc($booking['bike_name']); ?></h3>
                                <span class="status-badge status-<?php echo htmlesc($booking['status']); ?>">
                                    <?php echo ucfirst(htmlesc($booking['status'])); ?>
                                </span>
                            </div>

                            <div class="booking-details">
                                <p><strong>Model:</strong> <?php echo htmlesc($booking['model']); ?></p>
                                <p><strong>Pickup Date:</strong> <?php echo htmlesc($booking['pickup_date']); ?></p>
                                <p><strong>Return Date:</strong> <?php echo htmlesc($booking['return_date']); ?></p>
                                <p><strong>Price per Day:</strong> $<?php echo number_format($booking['price_per_day'], 2); ?></p>
                                <?php 
                                    $days = calculateRentalDays($booking['pickup_date'], $booking['return_date']);
                                    $total = calculateTotalPrice($booking['price_per_day'], $days);
                                ?>
                                <p><strong>Total Days:</strong> <?php echo $days; ?></p>
                                <p><strong>Total Price:</strong> $<?php echo number_format($total, 2); ?></p>

                                <?php if (!empty($booking['message'])): ?>
                                    <p><strong>Message:</strong> <?php echo htmlesc($booking['message']); ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="booking-actions">
                                <?php if ($booking['status'] === 'pending'): ?>
                                    <a href="../bookings/edit.php?id=<?php echo htmlesc($booking['id']); ?>" class="btn btn-small">Edit</a>
                                    <a href="../bookings/delete.php?id=<?php echo htmlesc($booking['id']); ?>" class="btn btn-danger btn-small" onclick="return confirm('Are you sure?')">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
