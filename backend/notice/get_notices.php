<?php
header('Content-Type: application/json; charset=utf-8');

// FIXED PATH (db_connect.php is in miniproject/)
require_once __DIR__ . '/../../db_connect.php';

// ?all=1 will return all records
$all = isset($_GET['all']) && ($_GET['all'] === '1' || strtolower($_GET['all']) === 'true');

if ($all) {
    $sql = "SELECT id, title, content, posted_by, created_at, expires_at, is_active 
            FROM notices 
            ORDER BY created_at DESC";
    $stmt = $mysqli->prepare($sql);
} else {
    $sql = "SELECT id, title, content, posted_by, created_at, expires_at, is_active
            FROM notices
            WHERE is_active = 1 AND (expires_at IS NULL OR expires_at > NOW())
            ORDER BY created_at DESC";
    $stmt = $mysqli->prepare($sql);
}

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Prepare failed: ' . $mysqli->error]);
    exit;
}

$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Output
echo json_encode(['notices' => $rows]);
