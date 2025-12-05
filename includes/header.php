<?php
// Start session to check login status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Flags for login status
$logged_in = isset($_SESSION['user_id']);
$admin_logged_in = isset($_SESSION['admin_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bike Rental System</title>
    
</head>
<body>
    <style>
    
    .home-navbar {
    padding: 1rem 2rem;
    color: white;
    position: fixed;
    width:100%;
    background-color: var(--text-dark);
    z-index: 999;
    background:  #0D6EFD !important;


}

.flex-between {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;

}

.home-navbar h1 {
    font-size: 1.8rem;
    margin: 0;
    
}

.home-nav-links a {
    color: white;
    text-decoration: none;
    margin-left: 2rem;
    font-weight: 500;
    
}

.home-nav-links a::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 2px;
  background: linear-gradient(90deg, transparent, #A6A6A6, transparent);
  background-size: 300% 100%;
  opacity: 0;
  transition: opacity 0.5s;
}

.home-nav-links a:hover {
color: #A6A6A6;
}

.home-nav-links a:hover::after {
  opacity: 1;
  animation: shimmer111 2s linear infinite !important;
}


@keyframes shimmer111 {
  0% {
    background-position: 200% 0;
  }
  100% {
    background-position: -200% 0;
  }
}

.flex-between a{
    text-decoration: none;
}
</style>
    <!-- Navigation -->
 <nav class="home-navbar">
    <div class="flex-between">
        <!-- Logo / Home link -->
        <a href="/bike-rental-system/index.php"><h1>🚲 Bike Rental System</h1></a>
        
        <div class="home-nav-links">
            <!-- Always visible links -->
            <a href="/bike-rental-system/index.php">Home</a>
            <a href="/bike-rental-system/user/bikes.php">Browse Bikes</a>

            <?php if ($logged_in): ?>
                <a href="/bike-rental-system/user/dashboard.php">Dashboard</a>
                <a href="/bike-rental-system/user/logout.php">Logout</a>
            <?php elseif ($admin_logged_in): ?>
                <a href="/bike-rental-system/admin/dashboard.php">Admin Dashboard</a>
                <a href="/bike-rental-system/admin/logout.php">Logout</a>
            <?php else: ?>
                <a href="/bike-rental-system/user/login.php">User Login</a>
                <a href="/bike-rental-system/admin/login.php">Admin Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

    </nav>
    <main class="container">
