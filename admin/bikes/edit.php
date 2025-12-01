<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect(BASE_URL . 'admin/bikes/index.php');
}

$bike = getBikeById($conn, (int)$_GET['id']);

if (!$bike) {
    redirect(BASE_URL . 'admin/bikes/index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price_per_day = trim($_POST['price_per_day'] ?? '');
    $bike_id = (int)$_GET['id'];

    // Validation
    if (empty($name)) $errors[] = "Bike name is required";
    if (empty($model)) $errors[] = "Model is required";
    if (empty($price_per_day) || !is_numeric($price_per_day) || $price_per_day <= 0) $errors[] = "Valid price is required";

    // Handle file upload
    $image_path = $bike['image_path'];
    if (!empty($_FILES['image']['name'])) {
        $file = $_FILES['image'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_error = $file['error'];
        $file_size = $file['size'];

        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if ($file_error !== UPLOAD_ERR_OK) {
            $errors[] = "File upload error";
        } elseif (!in_array($file_ext, $allowed_ext)) {
            $errors[] = "Invalid file type. Only JPG, PNG, and GIF allowed";
        } elseif ($file_size > 5000000) {
            $errors[] = "File too large (max 5MB)";
        } else {
            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0755, true);
            }

            // Delete old image if exists
            if (!empty($bike['image_path']) && file_exists('../../' . $bike['image_path'])) {
                unlink('../../' . $bike['image_path']);
            }

            $new_filename = time() . '_' . bin2hex(random_bytes(5)) . '.' . $file_ext;
            $upload_path = UPLOAD_DIR . $new_filename;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                $image_path = 'assets/images/bikes/' . $new_filename;
            } else {
                $errors[] = "Failed to upload file";
            }
        }
    }

    // Update bike if no errors
  // Update bike if no errors
if (empty($errors)) {

    $stmt = $conn->prepare("UPDATE bikes 
        SET name=?, model=?, description=?, price_per_day=?, image_path=? 
        WHERE id=?");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $price_float = (float)$price_per_day;

    if (!$stmt->bind_param("sssdsi", 
        $name, 
        $model, 
        $description, 
        $price_float, 
        $image_path, 
        $bike_id
    )) {
        die("Bind failed: " . $stmt->error);
    }

    if ($stmt->execute()) {
        header("Location: index.php?msg=Bike updated successfully");
        exit;
    } else {
        die("Execute failed: " . $stmt->error);
    }
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bike - Admin Panel</title>
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

    <main class="container">
        <div class="form-header">
            <h1>Edit Bike</h1>
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
                    <input type="text" id="name" name="name" value="<?php echo htmlesc($_POST['name'] ?? $bike['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="model">Model *</label>
                    <input type="text" id="model" name="model" value="<?php echo htmlesc($_POST['model'] ?? $bike['model']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?php echo htmlesc($_POST['description'] ?? $bike['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="price_per_day">Price per Day (NRS) *</label>
                    <input type="number" id="price_per_day" name="price_per_day" step="0.01" min="0" value="<?php echo htmlesc($_POST['price_per_day'] ?? $bike['price_per_day']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="image">Bike Image</label>
                    <?php if (!empty($bike['image_path']) && file_exists('../../' . $bike['image_path'])): ?>
                        <div class="current-image">
                            <img src="<?php echo htmlesc(BASE_URL . $bike['image_path']); ?>" alt="<?php echo htmlesc($bike['name']); ?>" class="preview-img">
                            <p>Current Image</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="image" name="image" accept="image/*">
                    <small>Leave empty to keep current image. Allowed: JPG, PNG, GIF (Max 5MB)</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-secondary">Update Bike</button>
                    <a href="index.php" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <?php include '../../includes/footer.php'; ?>
</body>
</html>
