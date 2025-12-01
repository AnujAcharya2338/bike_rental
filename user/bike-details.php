<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect(BASE_URL . 'user/bikes.php');
}

$bike = getBikeById($conn, (int)$_GET['id']);

if (!$bike) {
    redirect(BASE_URL . 'user/bikes.php');
}

$logged_in = isUserLoggedIn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlesc($bike['name']); ?> - Bike Rental System</title>
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/bike-details.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        
        <div class="container flex-between">
            <h2>Bike Rental System</h2>
            <div class="nav-links">
                <a href="bikes.php">Browse Bikes</a>
                <?php if ($logged_in): ?>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container">
        <a href="bikes.php" class="back-link">← Back to Bikes</a>

        <div class="bike-detail">
            <div class="bike-image-section">
                <?php if (!empty($bike['image_path']) && file_exists('../' . $bike['image_path'])): ?>
                    <img src="<?php echo htmlesc(BASE_URL . $bike['image_path']); ?>" alt="<?php echo htmlesc($bike['name']); ?>" class="detail-image">
                <?php else: ?>
                    <div class="detail-image placeholder">
                        <p>No Image Available</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bike-info-section">
                <h1><?php echo htmlesc($bike['name']); ?></h1>
                <p class="bike-model"><?php echo htmlesc($bike['model']); ?></p>

                <div class="bike-price-detail">
                    <span class="price-label">Price per Day:</span>
                    <span class="price">$<?php echo number_format($bike['price_per_day'], 2); ?></span>
                </div>

                <?php if (!empty($bike['description'])): ?>
                    <div class="bike-description-detail">
                        <h3>Description</h3>
                        <p><?php echo htmlesc($bike['description']); ?></p>
                    </div>
                <?php endif; ?>

                <div class="bike-action-detail">
                    <?php if ($logged_in): ?>
                        <a href="../bookings/create.php?bike_id=<?php echo htmlesc($bike['id']); ?>" class="btn">Book This Bike</a>
                    <?php else: ?>
                        <a href="login.php" class="btn">Login to Book</a>
                    <?php endif; ?>
                    <a href="bikes.php" class="btn">Back to Bikes</a>
                </div>
            </div>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
