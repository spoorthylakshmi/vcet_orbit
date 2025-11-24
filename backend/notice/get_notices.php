<?php
// allow Live Server (or any origin) to fetch JSON from this API
header('Access-Control-Allow-Origin: *'); // permissive for demo; for production restrict to known origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

// turn off display_errors to avoid HTML in JSON
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// ... rest of your get_notices.php code follows

// backend/notice/get_notices.php
header('Content-Type: application/json; charset=utf-8');
// disable display of PHP warnings to browser
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

require_once __DIR__ . '/../db_connect.php';

$sql = "SELECT id, title, content, date, pinned FROM notices ORDER BY pinned DESC, date DESC LIMIT 200";
$res = $conn->query($sql);

$notices = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        // ensure fields exist
        $row['date'] = $row['date'] ?? null;
        $row['pinned'] = isset($row['pinned']) ? (int)$row['pinned'] : 0;
        $notices[] = $row;
    }
}

echo json_encode($notices);
$conn->close();
