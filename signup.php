
<?php
include __DIR__ . '/db_connect.php';  // loads $mysqli

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? "");
    $password = trim($_POST['password'] ?? "");

    if ($email === "" || $password === "") {
        die("<script>alert('All fields are required.'); window.history.back();</script>");
    }

    // Insert only email + password
    $stmt = $mysqli->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful!'); window.location.href='login.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>
