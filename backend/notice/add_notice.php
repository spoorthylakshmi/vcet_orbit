<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST allowed']);
    exit;
}

// Accept JSON or form data
$input = $_POST;
if (empty($input)) {
    $json = file_get_contents('php://input');
    $input = json_decode($json, true) ?? [];
}

$title = trim($input['title'] ?? '');
$content = trim($input['content'] ?? '');
$posted_by = trim($input['posted_by'] ?? 'anonymous');
$expires_at = trim($input['expires_at'] ?? null); // optional, format: YYYY-MM-DD HH:MM:SS

if ($title === '' || $content === '') {
    http_response_code(400);
    echo json_encode(['error' => 'title and content are required']);
    exit;
}

// If expires_at present, basic validation (allow null)
if ($expires_at === '') $expires_at = null;
if ($expires_at !== null) {
    $d = date_create_from_format('Y-m-d H:i:s', $expires_at);
    if ($d === false) {
        http_response_code(400);
        echo json_encode(['error' => 'expires_at must be in format YYYY-MM-DD HH:MM:SS or omitted']);
        exit;
    }
}

$stmt = $mysqli->prepare("INSERT INTO notices (title, content, posted_by, expires_at) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Prepare failed: ' . $mysqli->error]);
    exit;
}
$stmt->bind_param('ssss', $title, $content, $posted_by, $expires_at);
$ok = $stmt->execute();
if (!$ok) {
    http_response_code(500);
    echo json_encode(['error' => 'Insert failed: ' . $stmt->error]);
    exit;
}

$id = $stmt->insert_id;
$stmt->close();

echo json_encode(['success' => true, 'id' => $id, 'message' => 'Notice added']);
