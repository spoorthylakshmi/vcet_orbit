<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../config.php'; // must define $ADMIN_KEY

// Accept POST JSON or form-data (also support simple GET for quick checks)
$method = $_SERVER['REQUEST_METHOD'];
$data = [];
if ($method === 'POST') {
    $data = $_POST;
    if (empty($data)) {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true) ?? [];
    }
} else {
    // allow GET for quick manual testing (not recommended for production)
    $data = $_GET;
}

// admin protection
$admin_key = $data['admin_key'] ?? ($data['adminKey'] ?? null);
if (!$admin_key || $admin_key !== $ADMIN_KEY) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized: invalid admin_key']);
    exit;
}

// validate id
$id = $data['id'] ?? null;
if (!is_numeric($id) || (int)$id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid id']);
    exit;
}
$id = (int)$id;

// Prepare update (soft-restore)
$stmt = $mysqli->prepare("UPDATE notices SET is_active = 1 WHERE id = ?");
if (!$stmt) {
    http_response_code(500);
    // optional: log error to file instead of exposing raw error
    // error_log("Restore prepare failed: " . $mysqli->error, 3, __DIR__ . '/../logs/error.log');
    echo json_encode(['error' => 'Prepare failed: ' . $mysqli->error]);
    exit;
}

$stmt->bind_param('i', $id);
$ok = $stmt->execute();
if ($ok === false) {
    http_response_code(500);
    // error_log("Restore execute failed: " . $stmt->error, 3, __DIR__ . '/../logs/error.log');
    echo json_encode(['error' => 'Execute failed: ' . $stmt->error]);
    $stmt->close();
    exit;
}

if ($stmt->affected_rows === 0) {
    // either id not found or already active
    // check whether id exists
    $check = $mysqli->prepare("SELECT is_active FROM notices WHERE id = ?");
    if ($check) {
        $check->bind_param('i', $id);
        $check->execute();
        $res = $check->get_result();
        if ($res && $res->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Notice not found (invalid id)']);
        } else {
            $row = $res->fetch_assoc();
            if ($row && isset($row['is_active']) && (int)$row['is_active'] === 1) {
                echo json_encode(['success' => false, 'message' => 'Notice already active']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No changes made']);
            }
        }
        $check->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'No rows updated (id not found or already active)']);
    }
    $stmt->close();
    exit;
}

$stmt->close();
echo json_encode(['success' => true, 'message' => 'Notice restored (is_active = 1)']);
