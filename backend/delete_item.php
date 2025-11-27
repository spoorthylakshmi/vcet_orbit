<?php
include __DIR__ . '/../db_connect.php';
include __DIR__ . '/../backend/config.php';  // admin password file

$id = $_POST['id'];
$pwd = $_POST['password'];

if ($pwd !== $ADMIN_KEY) {
    die("Wrong admin password");
}

$stmt = $mysqli->prepare("DELETE FROM lost_found WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: ../lost_and_found_page.php");
exit;
