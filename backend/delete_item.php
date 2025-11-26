<?php
include __DIR__ . '/../db_connect.php';

define('ADMIN_PASSWORD', 'vcetadmin123');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $password = $_POST['password'] ?? '';

    if ($password === ADMIN_PASSWORD) {

        $stmt = $conn->prepare("DELETE FROM lost_found WHERE id=?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<script>alert('Item deleted successfully'); 
                  window.location='../lost_and_found_page.php';</script>";
        } else {
            echo "<script>alert('Error deleting item: " . addslashes($conn->error) . "'); 
                  window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Wrong password or only admin can delete'); 
              window.history.back();</script>";
    }

    $conn->close();
}
?>
