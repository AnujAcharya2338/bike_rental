<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$logged_in = isUserLoggedIn();
$admin_logged_in = isAdminLoggedIn();
$bikes = array_slice(getAllBikes($conn), 0, 6);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike Rental System - Home</title>
    <link rel="stylesheet" href="assets/css/common.css">
    <style>
       

        .hero {
            background-image: url('assets/images/bikes_home.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            width: 100%;                 
            height: 8
            00px;    
            color: white;
            padding: 12rem 1.5rem;
            text-align: center;
            margin-bottom: 4rem;
            width:100%;
        }

        .container {
            max-width: 100%;
            margin: 0;
            padding: 0 ; 
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: white;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, 0.95);
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .hero .btn {
            padding: 0.75rem 2rem;
            font-size: 1rem;
        }

        .featured-bikes {
            margin-bottom: 4rem;
            /* display:flex; */
            align-items:right;
        }

        .featured-bikes h2 {
            text-align: center;
            margin-bottom: 3rem;
            color: var(--text-dark);
        }

        .bikes-preview {
            display: flex;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .home-bike-card {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .home-bike-card:hover {
            transform: translateY(-8px);
        }

        .bike-img {
            width: 100%;
            height: 200px;
            background: var(--neutral-light);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bike-info {
            padding: 1.5rem;
        }

        .bike-info h3 {
            margin-bottom: 0.5rem;
        }

        .bike-price {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .hero .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="hero">
        <div class="container">
            <h1>Your Ultimate Bike Rental Solution</h1>
            <p>Discover amazing bikes and explore your city on two wheels</p>
            <div class="hero-buttons">
                <a href="user/bikes.php" class="btn">Browse All Bikes</a>
                <?php if (!$logged_in && !$admin_logged_in): ?>
                    <a href="user/signup.php" class="btn" style="background-color: white; color: var(--primary-color);">Get Started</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <main class="container">
        <!-- Featured Bikes -->
        <div class="featured-bikes">
            <h2>Featured Bikes</h2>
            <?php if (!empty($bikes)): ?>
                <div class="bikes-preview">
                    <?php foreach ($bikes as $bike): ?>
                        <div class="home-bike-card">
                            <div class="bike-img">
                                <?php if (!empty($bike['image_path']) && file_exists($bike['image_path'])): ?>
                                    <img src="<?php echo htmlesc(BASE_URL . $bike['image_path']); ?>" alt="<?php echo htmlesc($bike['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </div>
                            <div class="bike-info">
                                <h3><?php echo htmlesc($bike['name']); ?></h3>
                                <p class="text-muted"><?php echo htmlesc($bike['model']); ?></p>
                                <p class="bike-price">$<?php echo number_format($bike['price_per_day'], 2); ?>/day</p>
                                <a href="user/bike-details.php?id=<?php echo htmlesc($bike['id']); ?>" class="btn" style="display: block; text-align: center;">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div style="text-align: center;">
                    <a href="user/bikes.php" class="btn btn-secondary">See All Bikes</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
