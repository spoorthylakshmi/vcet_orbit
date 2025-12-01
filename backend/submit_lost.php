<?php
include __DIR__ . '/../db_connect.php';

// Get form inputs
$item = $_POST['item_name'];
$location = $_POST['location'];
$desc = $_POST['description'];
$contact = $_POST['contact'];
$status = $_POST['status'];   // <-- User-selected Lost or Found
$date = date("Y-m-d H:i:s");

// Handle image upload
$imgName = null;

if (!empty($_FILES['image']['name'])) {
    $imgName = time() . "_" . basename($_FILES['image']['name']);
    $target = __DIR__ . "/uploads/" . $imgName;
    move_uploaded_file($_FILES['image']['tmp_name'], $target);
}

// Insert into database
$stmt = $mysqli->prepare("
    INSERT INTO lost_found (item_name, location, description, contact, status, date, image_path) 
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("sssssss", $item, $location, $desc, $contact, $status, $date, $imgName);
$stmt->execute();
$stmt->close();

// Redirect back
header("Location: ../lost_and_found_page.php");
exit;
?>
