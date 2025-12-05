<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$bikes = getAllBikes($conn);
$logged_in = isUserLoggedIn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Bikes - Bike Rental System</title>
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/bikes.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="all">
        <div class="bikes-header">
            
            <h1>Available Bikes</h1>
            <p>Choose the perfect bike for your adventure</p>
        </div>

        <?php if (empty($bikes)): ?>
            <div class="empty-state">
                <p>No bikes available at the moment.</p>
            </div>
        <?php else: ?>
            <div class="bikes-grid">
                <?php foreach ($bikes as $bike): ?>
                    <div class="bike-card">
                        <?php if (!empty($bike['image_path']) && file_exists('../' . $bike['image_path'])): ?>
                            <img src="<?php echo htmlesc(BASE_URL . $bike['image_path']); ?>" alt="<?php echo htmlesc($bike['name']); ?>" class="bike-image">
                        <?php else: ?>
                            <div class="bike-image placeholder">
                                <p>No Image</p>
                            </div>
                        <?php endif; ?>

                        <div class="bike-content">
                            <h3><?php echo htmlesc($bike['name']); ?></h3>
                            <p class="bike-model"><?php echo htmlesc($bike['model']); ?></p>

                            <?php if (!empty($bike['description'])): ?>
                                <p class="bike-description"><?php echo htmlesc($bike['description']); ?></p>
                            <?php endif; ?>

                            <div class="bike-price">
                                <span class="price">Rs <?php echo number_format($bike['price_per_day'], 2); ?>/day</span>
                            </div>

                            <div class="bike-actions">
                                <a href="bike-details.php?id=<?php echo htmlesc($bike['id']); ?>" class="btn">View Details</a>
                                <?php if ($logged_in): ?>
                                    <a href="../bookings/create.php?bike_id=<?php echo htmlesc($bike['id']); ?>" class="btn btn-secondary">Book Now</a>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-secondary">Book Now</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

    </div>    <?php endif; ?>
<?php include '../includes/footer.php'; ?>
    


</body>
</html>
