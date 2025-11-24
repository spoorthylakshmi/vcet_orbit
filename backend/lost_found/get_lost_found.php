<?php
<<<<<<< HEAD
include '../db_connect.php';

$sql = "SELECT * FROM lost_found ORDER BY date DESC";
$result = $conn->query($sql);

$items = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

echo json_encode($items);
=======
// backend/get_lost_found.php
include __DIR__ . '/db_connect.php';

header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT id, item_name, location, description, contact, status, date, image_path FROM lost_found ORDER BY date DESC";
$result = $conn->query($sql);

$items = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Normalize paths for front-end: keep relative path as-is (e.g., "uploads/xxx.jpg")
        if (!empty($row['image_path'])) {
            // Ensure consistency - do not add ../../ here; front-end should use the relative path directly
            $row['image_path'] = $row['image_path'];
        }
        $items[] = $row;
    }
}
echo json_encode($items, JSON_UNESCAPED_UNICODE);
>>>>>>> 50627e0d05734aa3e46218613e8f5099b22e9fb2
$conn->close();
?>
