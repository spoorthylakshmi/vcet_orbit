
<?php
include 'db_connect.php';

$item_name = $_POST['item_name'];
$location = $_POST['location'];
$description = $_POST['description'];
$contact = $_POST['contact'];

$imageName = NULL;
$imagePath = NULL;

// ------------------ IMAGE UPLOAD ------------------
if (!empty($_FILES["image"]["name"])) {

    $targetDir = "uploads/";

    // Create uploads folder if not exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $imageName = time() . "_" . basename($_FILES["image"]["name"]);
    $imagePath = $targetDir . $imageName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
        // Successfully uploaded
    } else {
        echo "<script>alert('Image upload failed!'); window.location='lost_and_found_page.php';</script>";
        exit;
    }
}

// ------------------ INSERT INTO DATABASE ------------------
$sql = "INSERT INTO lost_found (item_name, location, description, contact, status, date, image, image_path)
        VALUES ('$item_name', '$location', '$description', '$contact', 'Lost', NOW(), '$imageName', '$imagePath')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Item submitted successfully!'); window.location='lost_and_found_page.php';</script>";
} else {
    echo "Database Error: " . $conn->error;
}
?>









