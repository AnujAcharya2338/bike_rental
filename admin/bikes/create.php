<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price_per_day = trim($_POST['price_per_day'] ?? '');

    // Validation
    if (empty($name)) $errors[] = "Bike name is required";
    if (empty($model)) $errors[] = "Model is required";
    if (empty($price_per_day) || !is_numeric($price_per_day) || $price_per_day <= 0) $errors[] = "Valid price is required";

    // Handle file upload
    $image_path = null;
    if (!empty($_FILES['image']['name'])) {
        $file = $_FILES['image'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_error = $file['error'];
        $file_size = $file['size'];

        // Validate file
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if ($file_error !== UPLOAD_ERR_OK) {
            $errors[] = "File upload error";
        } elseif (!in_array($file_ext, $allowed_ext)) {
            $errors[] = "Invalid file type. Only JPG, PNG, and GIF allowed";
        } elseif ($file_size > 5000000) { // 5MB
            $errors[] = "File too large (max 5MB)";
        } else {
            // Create upload directory if it doesn't exist
            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0755, true);
            }

            // Generate unique filename
            $new_filename = time() . '_' . bin2hex(random_bytes(5)) . '.' . $file_ext;
            $upload_path = UPLOAD_DIR . $new_filename;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                $image_path = 'assets/images/bikes/' . $new_filename;
            } else {
                $errors[] = "Failed to upload file";
            }
        }
    }

    // Insert bike if no errors
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO bikes (name, model, description, price_per_day, image_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssds", $name, $model, $description, $price_per_day, $image_path);

        if ($stmt->execute()) {
            header("Location: index.php?msg=Bike added successfully");
            exit;
        } else {
            $errors[] = "Failed to add bike. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Bike - Admin Panel</title>
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
                <a href="index.php">Manage Bikes</a>
                <a href="../../bookings/admin-list.php">Bookings</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <main class="hello">
        <div class="form-header">
            <h1>Add New Bike</h1>
            <a href="index.php" class="back-link">← Back to Bikes</a>
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

        <div class="form-container">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Bike Name *</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlesc($_POST['name'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="model">Model *</label>
                    <input type="text" id="model" name="model" value="<?php echo htmlesc($_POST['model'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?php echo htmlesc($_POST['description'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="price_per_day">Price per Day (NRS) *</label>
                    <input type="number" id="price_per_day" name="price_per_day" step="0.01" min="0" value="<?php echo htmlesc($_POST['price_per_day'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="image">Bike Image</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <small>Allowed: JPG, PNG, GIF (Max 5MB)</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-secondary">Add Bike</button>
                    <a href="index.php" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <?php include '../../includes/footer.php'; ?>
</body>
</html>
