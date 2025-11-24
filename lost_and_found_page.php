<?php include 'conn.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Campus Helper - Lost & Found</title>

  <style>
  body{
    background-color:#f3f0f9;
    font-family:Arial,sans-serif;
  }

  .navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #004080;
    padding: 15px 30px;
  }

  p{ font-size: medium; font-family: Arial, sans-serif; }

  .navbar .logo {
    font-size: 20px;
    font-weight: bold;
    color: white;
  }

  .navbar ul {
    list-style: none;
    display: flex;
    gap: 20px;
  }

  .navbar ul li a {
    text-decoration: none;
    color: white;
    padding: 8px 15px;
    border-radius: 5px;
    transition: 0.3s;
  }

  .navbar ul li a:hover, .navbar ul li a.active {
    background: #0066cc;
  }

  .lost-found {
    padding: 40px;
    text-align: center;
  }

  .lost-found h1 { color: #004080; }

  .lost-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    max-width: 500px;
    margin: 20px auto;
    background: #f1f1f1;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
  }

  .lost-form input, .lost-form textarea, .lost-form button {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
  }

  .lost-form textarea { resize: none; height: 80px; }

  .lost-form button {
    background: #004080;
    color: white;
    border: none;
    cursor: pointer;
  }

  .lost-form button:hover { background: #0066cc; }

  .items-board { margin-top: 40px; }

  .item-card {
    background: #fff;
    padding: 15px;
    margin: 15px auto;
    border-radius: 10px;
    max-width: 500px;
    text-align: left;
    box-shadow: 0px 4px 6px rgba(0,0,0,0.1);
  }

  .item-card img {
    width: 100%;
    max-height: 220px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 10px;
  }

  footer {
    text-align: center;
    background: #004080;
    color: white;
    padding: 15px 0;
    margin-top: 40px;
  }
  footer strong { color: #ffd700; }
  </style>
</head>
<body>

  <nav class="navbar">
    <div class="logo">VCET ORBIT</div>
    <ul>
      <li><a href="home.html">🏠️Home</a></li>
      <li><a href="about page.html">🌎️About</a></li>
      <li><a href="index.html">🏫️Campus</a></li>
      <li><a href="notice.html">📢️Notice Board</a></li>
      <li><a href="timetable.html">📑️Time Table</a></li>
      <li><a href="contact.html">📞️Contact</a></li>
      <li><a href="lost_and_found_page.php" class="active">🔎️Lost And Found</a></li>
    </ul>
  </nav>

  <section class="lost-found">
    <h1>🔎 Lost & Found</h1>
    <p>Welcome to the <strong>VCET Lost & Found Corner</strong> 🔍</p>

    <form action="submit_lost.php" method="POST" enctype="multipart/form-data" class="lost-form">
      <input type="text" name="item_name" placeholder="Item name" required>
      <input type="text" name="location" placeholder="Location" required>
      <textarea name="description" placeholder="Description..." required></textarea>
      <input type="file" name="image" accept="image/*">
      <input type="text" name="contact" placeholder="Contact (phone/email)" required>
      <button type="submit">Submit</button>
    </form>

    <div class="items-board">
      <h2>📌 Recent Reports</h2>

      <?php
      $query = "SELECT * FROM add_lost_found ORDER BY id DESC";
      $result = $conn->query($query);

      while ($row = $result->fetch_assoc()) {
      ?>
        <div class="item-card">
          <?php if (!empty($row['image_path'])) { ?>
            <img src="<?= $row['image_path'] ?>" alt="Item Image">
          <?php } ?>
          <h3><?= $row['item_name'] ?></h3>
          <p><strong>Description:</strong> <?= $row['description'] ?></p>
          <p><strong>Location:</strong> <?= $row['location'] ?></p>
          <p><strong>Contact:</strong> <?= $row['contact'] ?></p>
          <p><strong>Status:</strong> <?= $row['status'] ?></p>
          <p><strong>Date:</strong> <?= $row['date'] ?></p>
        </div>
      <?php } ?>
    </div>
  </section>

  <footer>
    <p>© 2025 VCET ORBIT | 👩‍💻 Made for <strong>VCETians</strong>, by <strong>VCETians</strong></p>
  </footer>

</body>
</html>
