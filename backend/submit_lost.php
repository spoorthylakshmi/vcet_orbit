<?php
// backend/submit_lost.php
include __DIR__ . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../lost_and_found_page.php');
    exit;
}

// Basic sanitization
$item_name = isset($_POST['item_name']) ? trim($_POST['item_name']) : '';
$location  = isset($_POST['location']) ? trim($_POST['location']) : null;
$description = isset($_POST['description']) ? trim($_POST['description']) : null;
$contact = isset($_POST['contact']) ? trim($_POST['contact']) : null;

if ($item_name === '' || $contact === '') {
    echo "<script>alert('Please provide item name and contact.'); window.location='../lost_and_found_page.php';</script>";
    exit;
}

$imagePath = null;

// Image upload handling
if (!empty($_FILES['image']['name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
    $uploadsDir = __DIR__ . '/../uploads/'; // physical dir
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0777, true);
    }

    // sanitize filename
    $originalName = basename($_FILES['image']['name']);
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
    $safeName = time() . '_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
    $destination = $uploadsDir . $safeName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
        // store relative path used by front-end
        $imagePath = 'uploads/' . $safeName;
    } else {
        echo "<script>alert('Image upload failed.'); window.location='../lost_and_found_page.php';</script>";
        exit;
    }
}

// Insert using prepared statement
$stmt = $conn->prepare("INSERT INTO lost_found (item_name, location, description, contact, status, date, image_path) VALUES (?, ?, ?, ?, 'Lost', NOW(), ?)");
if (!$stmt) {
    echo "Prepare failed: " . $conn->error;
    exit;
}
$stmt->bind_param('sssss', $item_name, $location, $description, $contact, $imagePath);
if ($stmt->execute()) {
    echo "<script>alert('Item submitted successfully!'); window.location='../lost_and_found_page.php';</script>";
} else {
    echo "Database Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
