<?php
<<<<<<< HEAD
include '../db_connect.php';

$id = $_POST["id"];
$status = $_POST["status"];

$sql = "UPDATE lost_found SET status='$status' WHERE id=$id";

if ($conn->query($sql)) {
    echo "Status updated";
} else {
    echo "Error updating";
}

=======
// backend/update_lost_found.php
include __DIR__ . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request method.";
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$status = isset($_POST['status']) ? trim($_POST['status']) : '';

if ($id <= 0 || $status === '') {
    echo "Missing required parameters.";
    exit;
}

$stmt = $conn->prepare("UPDATE lost_found SET status = ? WHERE id = ?");
if (!$stmt) {
    echo "Prepare failed: " . $conn->error;
    exit;
}
$stmt->bind_param('si', $status, $id);
if ($stmt->execute()) {
    echo "Status updated";
} else {
    echo "SQL Error: " . $stmt->error;
}
$stmt->close();
>>>>>>> 50627e0d05734aa3e46218613e8f5099b22e9fb2
$conn->close();
?>
