<?php
include '../db_connect.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $item = $_POST["item_name"];
    $desc = $_POST["description"];
    $contact = $_POST["contact"];

    // Upload image
    $targetDir = "../../uploads/";
    $fileName = time() . "_" . basename($_FILES["photo"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
        $photo_path = "uploads/" . $fileName;

        $sql = "INSERT INTO lost_found (item_name, description, contact, status, photo)
                VALUES ('$item', '$desc', '$contact', 'lost', '$photo_path')";

        if ($conn->query($sql) === TRUE) {
            echo "Item reported successfully";
        } else {
            echo "DB Error: " . $conn->error;
        }
    } else {
        echo "Image upload failed.";
    }
}

$conn->close();
?>



