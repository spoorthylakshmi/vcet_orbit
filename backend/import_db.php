<?php
// Simple import script to create the `miniproject` database and tables from init_database.sql
// WARNING: Run this only once and then delete this file for security.

$host = 'localhost';
$user = 'root';
$pass = '';

$filepath = __DIR__ . '/init_database.sql';
if (!file_exists($filepath)) {
    echo "SQL file not found: $filepath";
    exit;
}

$sql = file_get_contents($filepath);
if ($sql === false) {
    echo "Failed to read SQL file.";
    exit;
}

$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_error) {
    die('Connect error: ' . $mysqli->connect_error);
}

// Execute multiple statements
if ($mysqli->multi_query($sql)) {
    do {
        if ($res = $mysqli->store_result()) {
            $res->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());

    echo "Database import completed successfully.<br>";
    echo "Please delete this file (backend/import_db.php) after use for security.";
} else {
    echo "Import failed: " . $mysqli->error;
}

$mysqli->close();
?>