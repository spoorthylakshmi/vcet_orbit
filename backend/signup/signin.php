// Get form data
$email = $_POST['email'];
$pass = $_POST['password'];
$phone = $_POST['phone'];
$blood_group = $_POST['blood_group'];

// Check if email already exists
$check = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($check);

if ($result->num_rows > 0) {
  echo "<script>alert('Email already registered. Please log in.'); 
        window.location='loginpage.html';</script>";
} else {

  // Insert new user
  $sql = "INSERT INTO users (email, password, phone, blood_group)
          VALUES ('$email', '$pass', '$phone', '$blood_group')";

  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Signup successful! You can now log in.'); 
          window.location='loginpage.html';</script>";
  } else {
    echo "Error: " . $conn->error;
  }
}

$conn->close();
?>
