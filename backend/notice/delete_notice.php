<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../config.php';
// minimal admin key protection - CHANGE THIS BEFORE SHARING
$ADMIN_KEY = 'ssmk@987'; // change locally

// Accept POST JSON or form-data or GET (for quick testing)
$method = $_SERVER['REQUEST_METHOD'];
$data = [];
if ($method === 'POST') {
    $data = $_POST;
    if (empty($data)) {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true) ?? [];
    }
} else {
    // GET fallback
    $data = $_GET;
}

$admin_key = $data['admin_key'] ?? null;
$id = $data['id'] ?? null;

if ($admin_key !== $ADMIN_KEY) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized: invalid admin_key']);
    exit;
}

if (!is_numeric($id) || (int)$id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid id']);
    exit;
}

$id = (int)$id;

// Soft delete recommended: set is_active = 0
$stmt = $mysqli->prepare("UPDATE notices SET is_active = 0 WHERE id = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Prepare failed: ' . $mysqli->error]);
    exit;
}

$stmt->bind_param('i', $id);
$ok = $stmt->execute();
if (!$ok) {
    http_response_code(500);
    echo json_encode(['error' => 'Delete failed: ' . $stmt->error]);
    exit;
}

if ($stmt->affected_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'No row updated (id not found or already inactive)']);
} else {
    echo json_encode(['success' => true, 'message' => 'Notice marked inactive']);
}
$stmt->close();
