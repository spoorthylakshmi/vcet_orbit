<?php
include __DIR__ . '/../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    // Delete item
    $stmt = $conn->prepare("DELETE FROM lost_found WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Item deleted successfully'); 
              window.location='../lost_and_found_page.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
