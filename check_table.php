<?php
require_once "db_connect.php";

$res = $mysqli->query("SHOW COLUMNS FROM notices");

while ($row = $res->fetch_assoc()) {
    echo $row['Field'] . "<br>";
}
?>
