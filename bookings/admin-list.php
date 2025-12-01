<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$bookings = getAllBookings($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/admin-bookings.css">
</head>
<body>
    <!-- Admin Navigation -->
    <nav class="admin-navbar">
        <div class="container flex-between">
            <h2>Admin Panel - Bike Rental System</h2>
            <div class="nav-links">
                <span>Welcome, <?php echo htmlesc($_SESSION['admin_name']); ?></span>
                <a href="../admin/dashboard.php">Dashboard</a>
                <a href="../admin/bikes/index.php">Manage Bikes</a>
                <a href="../admin-list.php" class="active">Bookings</a>
                <a href="../admin/logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="section-header">
            <h1>Manage Bookings</h1>
            <span class="booking-count">Total: <?php echo count($bookings); ?></span>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success">
                <?php echo htmlesc($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($bookings)): ?>
            <div class="empty-state">
                <p>No bookings found</p>
            </div>
        <?php else: ?>
            <div class="bookings-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Bike</th>
                            <th>Pickup</th>
                            <th>Return</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlesc($booking['id']); ?></td>
                                <td><?php echo htmlesc($booking['fullname']); ?></td>
                                <td><?php echo htmlesc($booking['email']); ?></td>
                                <td><?php echo htmlesc($booking['bike_name']); ?></td>
                                <td><?php echo htmlesc($booking['pickup_date']); ?></td>
                                <td><?php echo htmlesc($booking['return_date']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo htmlesc($booking['status']); ?>">
                                        <?php echo ucfirst(htmlesc($booking['status'])); ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <?php if ($booking['status'] === 'pending'): ?>
                                        <a href="update-status.php?id=<?php echo htmlesc($booking['id']); ?>&status=confirmed" class="btn btn-small">Confirm</a>
                                        <a href="update-status.php?id=<?php echo htmlesc($booking['id']); ?>&status=cancelled" class="btn btn-danger btn-small">Cancel</a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
