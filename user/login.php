<?php

require_once '../includes/config.php';
require_once '../includes/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $errors[] = "Email and password are required";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, fullname, password_hash FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['fullname'];
                header("Location: dashboard.php");
                exit;
            } else {
                $errors[] = "Invalid password";
            }
        } else {
            $errors[] = "Email not found";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - Bike Rental System</title>
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/user-auth.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
<div class="auth-container">
    
        <div class="auth-card">
            <h1>User Login</h1>
            <p class="auth-subtitle">Access your account</p>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <?php echo htmlesc($errors[0]); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_msg'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlesc($_SESSION['success_msg']); unset($_SESSION['success_msg']); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlesc($_POST['email'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-block">Log In</button>
            </form>

            <p class="auth-footer">
                Don't have an account? <a href="signup.php">Sign up here</a>
            </p>
        </div>
            </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
