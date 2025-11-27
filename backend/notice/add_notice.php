<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../../db_connect.php';
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST allowed']);
    exit;
}

$input = $_POST;
if (empty($input)) {
    $json = file_get_contents('php://input');
    $input = json_decode($json, true) ?? [];
}

$title = trim($input['title'] ?? '');
$content = trim($input['content'] ?? '');
$posted_by = trim($input['posted_by'] ?? 'anonymous');

// VALIDATION
if ($title === '' || $content === '') {
    http_response_code(400);
    echo json_encode(['error' => 'title and content are required']);
    exit;
}

// AUTO SET is_active = 1
$is_active = 1;

// INSERT into DB
$stmt = $mysqli->prepare(
    "INSERT INTO notices (title, content, posted_by, is_active) 
     VALUES (?, ?, ?, ?)"
);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Prepare failed: ' . $mysqli->error]);
    exit;
}

$stmt->bind_param('sssi', $title, $content, $posted_by, $is_active);
$ok = $stmt->execute();

if (!$ok) {
    http_response_code(500);
    echo json_encode(['error' => 'Insert failed: ' . $stmt->error]);
    exit;
}

$id = $stmt->insert_id;
$stmt->close();

echo json_encode(['success' => true, 'id' => $id, 'message' => 'Notice added']);
?>
