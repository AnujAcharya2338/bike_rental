<!-- Footer -->
<footer>
    <div class="footer-content">
        <!-- About Section -->
        <div class="footer-section">
            <h3>Bike Rental System</h3>
            <p>Your trusted platform for renting bikes in your city. Experience the freedom of two-wheeled adventures.</p>
            <div class="social-links">
                <a href="#" title="Facebook" aria-label="Facebook">f</a>
                <a href="#" title="Twitter" aria-label="Twitter">𝕏</a>
                <a href="#" title="Instagram" aria-label="Instagram">📷</a>
                <a href="#" title="LinkedIn" aria-label="LinkedIn">in</a>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="<?php echo htmlesc(BASE_URL); ?>">Home</a></li>
                <li><a href="<?php echo htmlesc(BASE_URL); ?>user/bikes.php">Browse Bikes</a></li>
                <li><a href="<?php echo htmlesc(BASE_URL); ?>user/login.php">User Login</a></li>
                <li><a href="<?php echo htmlesc(BASE_URL); ?>admin/login.php">Admin Login</a></li>
            </ul>
        </div>

        <!-- Support -->
        <div class="footer-section">
            <h3>Support</h3>
            <ul>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Terms & Conditions</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>

        <!-- Contact Info -->
        <div class="footer-section">
            <h3>Contact Info</h3>
            <p><strong>Email:</strong> <a href="mailto:info@bikerental.com">info@bikerental.com</a></p>
            <p><strong>Phone:</strong> <a href="tel:+1234567890">+1 (234) 567-8900</a></p>
            <p><strong>Address:</strong><br>123 Bike Street<br>Cycling City, CC 12345</p>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 Bike Rental System. All rights reserved.</p>
        <div class="flex-between">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Cookie Policy</a>
        </div>
    </div>
</footer>

<!-- Load footer styles -->
<link rel="stylesheet" href="<?php echo htmlesc(BASE_URL); ?>assets/css/footer.css">
