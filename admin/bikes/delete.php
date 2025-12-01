<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect(BASE_URL . 'admin/bikes/index.php');
}

$bike_id = (int)$_GET['id'];
$bike = getBikeById($conn, $bike_id);

if (!$bike) {
    redirect(BASE_URL . 'admin/bikes/index.php');
}

// Delete bike image if exists
if (!empty($bike['image_path']) && file_exists('../../' . $bike['image_path'])) {
    unlink('../../' . $bike['image_path']);
}

// Delete bike from database
$stmt = $conn->prepare("DELETE FROM bikes WHERE id = ?");
$stmt->bind_param("i", $bike_id);

if ($stmt->execute()) {
    header("Location: index.php?msg=Bike deleted successfully");
} else {
    header("Location: index.php?msg=Failed to delete bike");
}
exit;
?>
