
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "vcet_orbit";

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");
?>

