<?php
include 'conn.php'; // FIXED include

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Check if email exists
  $sql = "SELECT * FROM users WHERE email='$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verify password (plain text check for now)
    if ($row['password'] === $password) {
      echo "
      <!DOCTYPE html>
      <html>
      <head>
        <meta charset='UTF-8'>
        <title>Login Successful</title>
        <style>
          body {
            background-color: #f6fff8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Poppins, sans-serif;
          }
          h2 {
            color: green;
            font-size: 28px;
            opacity: 0;
            animation: fadeIn 1.2s ease-in forwards;
          }
          @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
          }
        </style>
      </head>
      <body>
        <h2>Login successful 🎉</h2>
        <script>
          setTimeout(function() {
            window.location.href = 'dashboard.php';
          }, 1500);
        </script>
      </body>
      </html>";
    } else {
      echo "<script>alert('Incorrect password'); window.history.back();</script>";
    }
  } else {
    echo "<script>alert('No account found. Please sign up first.'); window.history.back();</script>";
  }
}

$conn->close();
?>
