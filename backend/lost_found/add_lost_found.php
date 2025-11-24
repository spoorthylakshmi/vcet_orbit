<?php
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
        echo "SQL Error: " . $conn->error;
    }
}

$conn->close();
?>



