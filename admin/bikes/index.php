<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$bikes = getAllBikes($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bikes - Admin Panel</title>
    <link rel="stylesheet" href="../../assets/css/common.css">
    <link rel="stylesheet" href="../../assets/css/admin-bikes.css">
</head>
<body>
    <!-- Admin Navigation -->
    <nav class="admin-navbar">
        <div class="container flex-between">
            <h2>Admin Panel - Bike Rental System</h2>
            <div class="nav-links">
                <span>Welcome, <?php echo htmlesc($_SESSION['admin_name']); ?></span>
                <a href="../dashboard.php">Dashboard</a>
                <a href="index.php" class="active">Manage Bikes</a>
                <a href="../../bookings/admin-list.php">Bookings</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="section-header">
            <h1>Manage Bikes</h1>
            <a href="create.php" class="btn btn-secondary">Add New Bike</a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success">
                <?php echo htmlesc($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($bikes)): ?>
            <div class="empty-state">
                <p>No bikes added yet.</p>
                <a href="create.php" class="btn">Add Your First Bike</a>
            </div>
        <?php else: ?>
            <div class="bikes-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Model</th>
                            <th>Price/Day</th>
                            <th>Image</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bikes as $bike): ?>
                            <tr>
                                <td><?php echo htmlesc($bike['id']); ?></td>
                                <td><?php echo htmlesc($bike['name']); ?></td>
                                <td><?php echo htmlesc($bike['model']); ?></td>
                                <td>Rs <?php echo number_format($bike['price_per_day'], 2); ?></td>
                                <td>
                                    <?php 
                                    $has_image = !empty($bike['image_path']) && file_exists('../../' . $bike['image_path']);
                                    echo $has_image ? '✓' : '✗';
                                    ?>
                                </td>
                                <td><?php echo htmlesc(date('M d, Y', strtotime($bike['created_at']))); ?></td>
                                <td class="action-buttons">
                                    <a href="edit.php?id=<?php echo htmlesc($bike['id']); ?>" class="btn btn-small">Edit</a>
                                    <a href="delete.php?id=<?php echo htmlesc($bike['id']); ?>" class="btn btn-danger btn-small" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>

    <?php include '../../includes/footer.php'; ?>
</body>
</html>
