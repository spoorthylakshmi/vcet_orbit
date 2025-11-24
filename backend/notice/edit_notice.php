<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../config.php'; // must define $ADMIN_KEY

session_start();

// Check admin: allow if session indicates admin, otherwise require admin_key param
$loggedInAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
$input = $_POST;
if (empty($input)) {
    $json = file_get_contents('php://input');
    $input = json_decode($json, true) ?? [];
}

if (!$loggedInAdmin) {
    $provided_key = $input['admin_key'] ?? ($input['adminKey'] ?? null);
    if (!$provided_key || $provided_key !== $ADMIN_KEY) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized: admin required']);
        exit;
    }
}

// Validate id
$id = $input['id'] ?? null;
if ($id === null || !is_numeric($id) || (int)$id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Valid "id" is required']);
    exit;
}
$id = (int)$id;

// Allowed fields to update
$allowed = [
    'title' => 's',
    'content' => 's',
    'expires_at' => 's', // format: "YYYY-MM-DD HH:MM:SS" or empty/null to set NULL
    'is_active' => 'i'   // 0 or 1
];

// Build SET clauses dynamically based on provided fields
$setParts = [];
$params = [];
$types = '';

foreach ($allowed as $field => $type) {
    if (array_key_exists($field, $input)) {
        $value = $input[$field];
        // Normalize is_active to int
        if ($field === 'is_active') {
            $value = (int)$value;
        }
        // Normalize expires_at: allow empty -> NULL
        if ($field === 'expires_at') {
            $value = trim((string)$value);
            if ($value === '') {
                $value = null; // will be bound as null below
            } else {
                // basic validation
                $d = date_create_from_format('Y-m-d H:i:s', $value);
                if ($d === false) {
                    http_response_code(400);
                    echo json_encode(['error' => 'expires_at must be "YYYY-MM-DD HH:MM:SS" or empty']);
                    exit;
                }
            }
        }
        $setParts[] = "`$field` = ?";
        $params[] = $value;
        $types .= $type;
    }
}

if (count($setParts) === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'No updatable field provided. Provide one of: title, content, expires_at, is_active']);
    exit;
}

$sql = "UPDATE notices SET " . implode(', ', $setParts) . " WHERE id = ?";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Prepare failed: ' . $mysqli->error]);
    exit;
}

// Bind params dynamically: append id param at end
$types .= 'i'; // for id
$params[] = $id;

// mysqli_stmt::bind_param requires references
$bindNames = [];
$bindNames[] = $types;
for ($i = 0; $i < count($params); $i++) {
    // Create variable references
    $bindNames[] = &$params[$i];
}

// Use call_user_func_array to bind
call_user_func_array([$stmt, 'bind_param'], $bindNames);

$execOk = $stmt->execute();
if ($execOk === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Execute failed: ' . $stmt->error]);
    $stmt->close();
    exit;
}

if ($stmt->affected_rows === 0) {
    // Could be no change or id not found — check if id exists
    $check = $mysqli->prepare("SELECT id FROM notices WHERE id = ?");
    $check->bind_param('i', $id);
    $check->execute();
    $res = $check->get_result();
    if ($res && $res->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Notice not found (invalid id)']);
    } else {
        echo json_encode(['success' => true, 'message' => 'No changes made (values identical)']);
    }
    $check->close();
    $stmt->close();
    exit;
}

$stmt->close();
echo json_encode(['success' => true, 'message' => 'Notice updated']);
