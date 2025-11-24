<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // respond to preflight quickly
    http_response_code(204);
    exit;
}

// ... rest of add_notice.php

// backend/notice/add_notice.php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

require_once __DIR__ . '/../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'POST required']);
    exit;
}

$input = $_POST;
if (empty($input)) {
    $json = file_get_contents('php://input');
    $decoded = json_decode($json, true);
    if (is_array($decoded)) $input = $decoded;
}

$title = trim($input['title'] ?? '');
$content = trim($input['content'] ?? '');
if ($title === '' || $content === '') {
    http_response_code(422);
    echo json_encode(['error' => 'Missing title or content']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO notices (title, content) VALUES (?, ?)");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Prepare failed', 'detail' => $conn->error]);
    exit;
}
$stmt->bind_param("ss", $title, $content);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'id' => $stmt->insert_id]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Insert failed', 'detail' => $stmt->error]);
}
$stmt->close();
$conn->close();
