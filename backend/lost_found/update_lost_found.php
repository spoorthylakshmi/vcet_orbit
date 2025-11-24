
<?php
include '../db_connect.php';

$id = $_POST["id"];
$status = $_POST["status"];

$sql = "UPDATE lost_found SET status='$status' WHERE id=$id";

if ($conn->query($sql)) {
    echo "Status updated";
} else {
    echo "SQL Error: " . $conn->error;
}

$conn->close();
?>
