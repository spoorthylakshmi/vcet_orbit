<?php
<<<<<<< HEAD
include '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item = $_POST["item_name"];
    $desc = $_POST["description"];
    $contact = $_POST["contact"];

    $sql = "INSERT INTO lost_found (item_name, description, contact, status)
            VALUES ('$item', '$desc', '$contact', 'lost')";

    if ($conn->query($sql) === TRUE) {
        echo "Item reported successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}

=======
// backend/add_lost_found.php
include __DIR__ . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request.";
    exit;
}

$item = isset($_POST['item_name']) ? trim($_POST['item_name']) : '';
$desc = isset($_POST['description']) ? trim($_POST['description']) : '';
$contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';

if ($item === '' || $contact === '') {
    echo "Missing required fields.";
    exit;
}

$imagePath = null;
if (!empty($_FILES['photo']['name']) && is_uploaded_file($_FILES['photo']['tmp_name'])) {
    $uploadsDir = __DIR__ . '/../../uploads/';
    if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0777, true);

    $orig = basename($_FILES['photo']['name']);
    $ext = pathinfo($orig, PATHINFO_EXTENSION);
    $filename = time() . '_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
    $target = $uploadsDir . $filename;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
        // use relative path from web root
        $imagePath = 'uploads/' . $filename;
    } else {
        echo "Image upload failed.";
        exit;
    }
}

$stmt = $conn->prepare("INSERT INTO lost_found (item_name, description, contact, status, image_path) VALUES (?, ?, ?, 'Lost', ?)");
if (!$stmt) {
    echo "Prepare failed: " . $conn->error;
    exit;
}
$stmt->bind_param('ssss', $item, $desc, $contact, $imagePath);
if ($stmt->execute()) {
    echo "Item reported successfully";
} else {
    echo "DB Error: " . $stmt->error;
}
$stmt->close();
>>>>>>> 50627e0d05734aa3e46218613e8f5099b22e9fb2
$conn->close();
?>
