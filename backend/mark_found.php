<?php
include __DIR__ . '/../db_connect.php';

$id = $_POST['id'];

$stmt = $mysqli->prepare("UPDATE lost_found SET status='Found' WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: ../lost_and_found_page.php");
exit;
