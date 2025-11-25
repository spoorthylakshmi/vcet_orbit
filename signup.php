<?php
include 'db_connect.php'; // make sure this path is correct

$email = $_POST['email'];
$pass = $_POST['password'];

// Check if email already exists
$check = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($check);

if ($result->num_rows > 0) {
  echo "<script>alert('Email already registered. Please log in.'); 
        window.location='login.html';</script>";
} else {

  // Insert new user (FIXED)
  $sql = "INSERT INTO users (email, password)
          VALUES ('$email', '$pass')";

  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Signup successful! You can now log in.'); 
          window.location='login.html';</script>";
  } else {
    echo "Error: " . $conn->error;
  }
}

$conn->close();
?>
