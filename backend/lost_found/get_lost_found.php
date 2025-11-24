<?php
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
$conn->close();
?>


