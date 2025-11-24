<?php
include '../db_connect.php';


$sql = "SELECT * FROM lost_found ORDER BY date DESC";
$result = $conn->query($sql);

$items = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        // Fix image path if it exists
        if (!empty($row['photo'])) {
            $row['photo'] = "../../" . $row['photo']; 
            // Example output: "../../uploads/170000000_img.jpg"
        }

        $items[] = $row;
    }
}

echo json_encode($items);
$conn->close();
?>


