<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

// Get statistics
$users_result = $conn->query("SELECT COUNT(*) as count FROM users");
$users_count = $users_result->fetch_assoc()['count'];

$bikes_result = $conn->query("SELECT COUNT(*) as count FROM bikes");
$bikes_count = $bikes_result->fetch_assoc()['count'];

$bookings_result = $conn->query("SELECT COUNT(*) as count FROM bookings");
$bookings_count = $bookings_result->fetch_assoc()['count'];

$pending_result = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'");
$pending_count = $pending_result->fetch_assoc()['count'];

// Get recent bookings
$recent_bookings = getAllBookings($conn);
$recent_bookings = array_slice($recent_bookings, 0, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bike Rental System</title>
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css">
</head>
<body>
    <!-- Admin Navigation -->
    <nav class="admin-navbar">
        <div class="container flex-between">
            <h2>Admin Panel - Bike Rental System</h2>
            <div class="nav-links">
                <span>Welcome, <?php echo htmlesc($_SESSION['admin_name']); ?></span>
                <a href="dashboard.php" class="active">Dashboard</a>
                <a href="bikes/index.php">Manage Bikes</a>
                <a href="../bookings/admin-list.php">Bookings</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="dashboard-title">
            <h1>Admin Dashboard</h1>
            <p>Welcome to your bike rental management system</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <h3>Total Users</h3>
                    <span class="stat-icon users-icon">👥</span>
                </div>
                <p class="stat-number"><?php echo $users_count; ?></p>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3>Total Bikes</h3>
                    <span class="stat-icon bikes-icon">🚲</span>
                </div>
                <p class="stat-number"><?php echo $bikes_count; ?></p>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3>Total Bookings</h3>
                    <span class="stat-icon bookings-icon">📅</span>
                </div>
                <p class="stat-number"><?php echo $bookings_count; ?></p>
            </div>

            <div class="stat-card attention">
                <div class="stat-header">
                    <h3>Pending Bookings</h3>
                    <span class="stat-icon pending-icon">⏳</span>
                </div>
                <p class="stat-number"><?php echo $pending_count; ?></p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>Quick Actions</h2>
            <div class="actions-grid">
                <a href="bikes/create.php" class="action-card">
                    <span class="action-icon">➕</span>
                    <h3>Add New Bike</h3>
                    <p>Add a new bike to your inventory</p>
                </a>
                <a href="bikes/index.php" class="action-card">
                    <span class="action-icon">🔧</span>
                    <h3>Manage Bikes</h3>
                    <p>Edit or delete existing bikes</p>
                </a>
                <a href="../bookings/admin-list.php" class="action-card">
                    <span class="action-icon">📊</span>
                    <h3>View Bookings</h3>
                    <p>Manage all user bookings</p>
                </a>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="recent-section">
            <div class="section-header">
                <h2>Recent Bookings</h2>
                <a href="../bookings/admin-list.php" class="btn btn-small">View All</a>
            </div>

            <?php if (empty($recent_bookings)): ?>
                <p class="text-muted">No bookings yet</p>
            <?php else: ?>
                <div class="recent-bookings-table">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Bike</th>
                                <th>Pickup Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_bookings as $booking): ?>
                                <tr>
                                    <td><?php echo htmlesc($booking['fullname']); ?></td>
                                    <td><?php echo htmlesc($booking['bike_name']); ?></td>
                                    <td><?php echo htmlesc($booking['pickup_date']); ?></td>
                                    <td><?php echo htmlesc($booking['return_date']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo htmlesc($booking['status']); ?>">
                                            <?php echo ucfirst(htmlesc($booking['status'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="../bookings/admin-list.php" class="btn btn-small">Manage</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
