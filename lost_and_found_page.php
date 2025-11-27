<?php include __DIR__ . '/db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Campus Helper - Lost & Found</title>
  <style>
  body{ background-color:#f3f0f9; font-family:Arial,sans-serif; }
  .navbar { display:flex; justify-content:space-between; align-items:center; background:#004080; padding:15px 30px; }
  p{ font-size: medium; }
  .navbar .logo { font-size:20px; font-weight:bold; color:white; }
  .navbar ul { list-style:none; display:flex; gap:20px; }
  .navbar ul li a { text-decoration:none; color:white; padding:8px 15px; border-radius:5px; }
  .navbar ul li a:hover, .navbar ul li a.active { background:#0066cc; }

  .lost-found { padding:40px; text-align:center; }
  .lost-form { display:flex; flex-direction:column; gap:15px; max-width:500px; margin:20px auto;
               background:#f1f1f1; padding:20px; border-radius:12px; box-shadow:0 4px 8px rgba(0,0,0,.1); }
  .lost-form input, .lost-form textarea { padding:10px; border:1px solid #ccc; border-radius:8px; }
  .lost-form button { padding:10px; background:#004080; color:white; border:none; border-radius:8px;
                      cursor:pointer; }
  .lost-form button:hover { background:#0066cc; }

  .items-board { margin-top:40px; }
  .item-card { background:#fff; padding:15px; margin:15px auto; border-radius:10px; max-width:500px;
               box-shadow:0 4px 6px rgba(0,0,0,.1); }
  .item-card img { width:100%; height:auto; border-radius:10px; }

  footer { text-align:center; background:#004080; color:white; padding:15px 0; margin-top:40px; }
  </style>
</head>

<body>

  <nav class="navbar">
    <div class="logo">VCET ORBIT</div>
    <ul>
      <li><a href="home.html">Home</a></li>
      <li><a href="about page.html">About</a></li>
      <li><a href="index.html">Campus</a></li>
      <li><a href="notice.html">Notice Board</a></li>
      <li><a href="timetable.html">Time Table</a></li>
      <li><a href="contact.html">Contact</a></li>
      <li><a href="lost_and_found_page.php" class="active">Lost And Found</a></li>
    </ul>
  </nav>

  <section class="lost-found">
    <h1>Lost & Found</h1>

    <!-- Correct path -->
    <form action="backend/submit_lost.php" method="POST" enctype="multipart/form-data" class="lost-form">
      <input type="text" name="item_name" placeholder="Item name" required>
      <input type="text" name="location" placeholder="Location">
      <textarea name="description" placeholder="Description..."></textarea>
      <input type="file" name="image">
      <input type="text" name="contact" placeholder="Contact (phone/email)" required>
      <button type="submit">Submit</button>
    </form>

    <div class="items-board">
      <h2>Recent Reports</h2>

      <?php
        $query = "SELECT * FROM lost_found ORDER BY date DESC";
        $result = $mysqli->query($query);

        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {

            $img = $row['image_path'] ? "backend/uploads/" . $row['image_path'] : "";
      ?>
        <div class="item-card">

          <?php if (!empty($row['image_path'])): ?>
            <img src="<?= $img ?>">
          <?php endif; ?>

          <h3><?= htmlspecialchars($row['item_name']) ?></h3>
          <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($row['description'])) ?></p>
          <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
          <p><strong>Contact:</strong> <?= htmlspecialchars($row['contact']) ?></p>
          <p><strong>Status:</strong> <?= htmlspecialchars($row['status']) ?></p>
          <p><strong>Date:</strong> <?= htmlspecialchars($row['date']) ?></p>

          <?php if ($row['status'] === "Lost"): ?>
            <form action="backend/mark_found.php" method="POST">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <button style="background:green;color:white;padding:8px 12px;border:none;border-radius:5px;margin-top:10px;">
                ✔ Mark As Found
              </button>
            </form>
          <?php endif; ?>

          <!-- Delete -->
          <form action="backend/delete_item.php" method="POST" onsubmit="return askAdmin(this)">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <input type="hidden" name="password">
            <button style="background:red;color:white;padding:8px 12px;border:none;border-radius:5px;margin-top:10px;">
              🗑 Delete
            </button>
          </form>

        </div>

      <?php
          }
        } else {
          echo "<p>No reports yet.</p>";
        }
      ?>

    </div>
  </section>

  <footer>
    <p>© 2025 VCET ORBIT | Made for VCETians, by VCETians</p>
  </footer>

<script>
function askAdmin(form) {
    let pwd = prompt("Enter admin password:");
    if (!pwd) return false;
    form.password.value = pwd;
    return true;
}
</script>

</body>
</html>
