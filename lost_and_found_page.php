<?php
include __DIR__ . '/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Campus Helper - Lost &amp; Found</title>

  <!-- Same font & icons as other pages -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root {
      --primary: #2563eb;
      --primary-dark: #1d4ed8;
      --primary-light: #3b82f6;
      --accent: #f59e0b;
      --accent-light: #fbbf24;
      --bg-main: #f3f6fb;
      --card-bg: #ffffff;
      --text-dark: #1e293b;
      --text-light: #64748b;
      --border-light: #e2e8f0;
      --shadow-soft: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body{
      background: linear-gradient(135deg, #f9fbff 0%, #eef2ff 40%, #e0f2fe 100%);
      font-family: "Poppins", sans-serif;
      color: var(--text-dark);
      overflow-x: hidden;
      padding-top: 90px; /* space for fixed navbar */
    }

    /* NAVBAR (same style as other modified pages) */
    .navbar{
      position: fixed;
      top: 0;
      width: 100%;
      background: rgba(255,255,255,0.96);
      backdrop-filter: blur(18px);
      border-bottom: 1px solid var(--border-light);
      z-index: 1000;
      padding: 0.9rem 0;
    }
    .nav-container{
      max-width: 1200px;
      margin: 0 auto;
      display:flex;
      justify-content:space-between;
      align-items:center;
      padding: 0 2rem;
    }
    .logo{
      font-size:1.5rem;
      font-weight:700;
      background:linear-gradient(135deg,var(--primary) 0%,var(--primary-light) 50%);
      -webkit-background-clip:text;
      -webkit-text-fill-color:transparent;
      background-clip:text;
      display:flex;
      align-items:center;
      gap:0.5rem;
    }
    .logo i{
      font-size:1.3rem;
    }
    .navbar ul{
      list-style:none;
      display:flex;
      gap:1.5rem;
      align-items:center;
    }
    .navbar ul li a{
      text-decoration:none;
      color:var(--text-light);
      padding:0.45rem 1.2rem;
      border-radius:999px;
      font-weight:500;
      font-size:0.95rem;
      transition:all 0.25s ease;
    }
    .navbar ul li a:hover{
      color:var(--primary);
      background:rgba(37,99,235,0.08);
    }
    .navbar ul li a.active{
      color:#ffffff;
      background:var(--primary);
      box-shadow:0 8px 20px rgba(37,99,235,0.35);
    }
    .hamburger{
      display:none;
      flex-direction:column;
      cursor:pointer;
      gap:4px;
    }
    .hamburger span{
      width:24px;
      height:3px;
      background:var(--primary);
      border-radius:999px;
      transition:0.3s;
    }

    /* PAGE LAYOUT */
    .page-wrapper{
      max-width:1200px;
      margin:0 auto 40px;
      padding:0 1.5rem 3rem;
    }

    /* SECTION HEADER */
    .lost-found{
      padding:2.5rem 1rem 2rem;
      text-align:center;
    }
    .lost-found h1{
      font-size:clamp(2.2rem,3.5vw,2.7rem);
      font-weight:700;
      color:var(--text-dark);
      margin-bottom:0.5rem;
    }
    .section-divider{
      width:80px;
      height:4px;
      background:linear-gradient(90deg,var(--accent),var(--accent-light));
      margin:0.7rem auto 1.4rem;
      border-radius:999px;
    }
    .lost-found > p{
      max-width:800px;
      margin:0.3rem auto 0;
      color:var(--text-light);
      font-size:0.95rem;
      line-height:1.7;
    }

    /* FORM */
    .lost-form{
      display:flex;
      flex-direction:column;
      gap:15px;
      max-width:520px;
      margin:25px auto 10px;
      background:var(--card-bg);
      padding:22px 22px 20px;
      border-radius:22px;
      box-shadow:var(--shadow-soft);
      border:1px solid rgba(148,163,184,0.25);
      text-align:left;
    }
    .lost-form select,
    .lost-form input,
    .lost-form textarea{
      padding:10px 12px;
      border:1px solid #d1d5db;
      border-radius:10px;
      font-family:"Poppins",sans-serif;
      font-size:0.9rem;
      outline:none;
      transition:border 0.2s ease, box-shadow 0.2s ease;
    }
    .lost-form textarea{
      min-height:90px;
      resize:vertical;
    }
    .lost-form select:focus,
    .lost-form input:focus,
    .lost-form textarea:focus{
      border-color:var(--primary);
      box-shadow:0 0 0 1px rgba(37,99,235,0.25);
    }
    .lost-form button{
      padding:10px 12px;
      background:var(--primary);
      color:#ffffff;
      border:none;
      border-radius:999px;
      cursor:pointer;
      font-weight:600;
      font-size:0.95rem;
      transition:background 0.2s ease, transform 0.1s ease, box-shadow 0.2s ease;
      box-shadow:0 8px 18px rgba(37,99,235,0.35);
    }
    .lost-form button:hover{
      background:var(--primary-dark);
      transform:translateY(-1px);
      box-shadow:0 12px 26px rgba(37,99,235,0.45);
    }

    /* GRID LAYOUT */
    .items-board{
      margin-top:30px;
      display:grid;
      grid-template-columns:repeat(2, 1fr);
      gap:24px;
      padding:0;
    }
    @media(max-width:900px){
      .page-wrapper{ padding:0 1rem 3rem; }
    }
    @media(max-width:768px){
      body{ padding-top:80px; }
      .items-board{
        grid-template-columns:1fr;
      }
      .nav-container{ padding:0 1rem; }
      .navbar ul{
        position:fixed;
        top:70px;
        right:-100%;
        width:100%;
        height:calc(100vh - 70px);
        background:#ffffff;
        flex-direction:column;
        justify-content:flex-start;
        align-items:center;
        padding-top:1.8rem;
        gap:1rem;
        transition:0.3s;
      }
      .navbar ul.active{ right:0; }
      .hamburger{ display:flex; }
    }

    /* CARD */
    .item-card{
      background:var(--card-bg);
      padding:16px 16px 14px;
      border-radius:20px;
      box-shadow:var(--shadow-soft);
      width:100%;
      text-align:left;
      border:1px solid rgba(148,163,184,0.25);
      position:relative;
      overflow:hidden;
      transition:transform 0.2s ease, box-shadow 0.2s ease, border 0.2s ease;
    }
    .item-card::before{
      content:"";
      position:absolute;
      top:0; left:0; right:0;
      height:3px;
      background:linear-gradient(90deg,var(--primary),var(--accent));
      opacity:0;
      transition:opacity 0.2s ease;
    }
    .item-card:hover{
      transform:translateY(-4px);
      box-shadow:0 16px 40px rgba(15,23,42,0.15);
      border-color:rgba(37,99,235,0.35);
    }
    .item-card:hover::before{
      opacity:1;
    }

    /* IMAGE */
    .item-card img{
      width:100%;
      height:190px;
      object-fit:cover;
      border-radius:14px;
      cursor:pointer;
      margin-bottom:10px;
    }

    /* STATUS BADGE */
    .badge{
      display:inline-block;
      padding:5px 12px;
      border-radius:999px;
      font-size:12px;
      font-weight:600;
      color:white;
      margin-bottom:10px;
      letter-spacing:0.03em;
    }
    .lost-badge{ background:#ef4444; }
    .found-badge{ background:#22c55e; }

    .item-card h3{
      font-size:1.05rem;
      margin:5px 0 6px;
      color:var(--text-dark);
    }
    .item-card p{
      font-size:0.9rem;
      color:var(--text-light);
      margin:2px 0;
      line-height:1.5;
    }

    /* MARK & DELETE BUTTONS (inline styles kept, just nicer base) */
    .item-card form button{
      font-family:"Poppins",sans-serif;
      font-size:0.85rem;
      border-radius:999px !important;
    }

    /* FOOTER */
    footer {
      text-align:center;
      background:#0f172a;
      color:white;
      padding:18px 0;
      margin-top:25px;
      font-size:0.9rem;
    }
    footer strong {
      color:#facc15;
      position:relative;
    }
    footer strong::after {
      content:"";
      position:absolute;
      left:0;
      bottom:-2px;
      width:0%;
      height:2px;
      background:#facc15;
      animation:underlineSlide 2s infinite alternate;
    }
    @keyframes underlineSlide {
      from { width:0%; }
      to   { width:100%; }
    }

    /* POPUP IMAGE */
    .image-popup{
      position:fixed;
      top:0; left:0; width:100%; height:100%;
      background:rgba(0,0,0,0.8);
      display:none;
      justify-content:center;
      align-items:center;
      z-index:9999;
    }
    .image-popup img{
      max-width:90%;
      max-height:90%;
      border-radius:12px;
      animation:fadeIn .3s ease-in-out;
      box-shadow:0 18px 45px rgba(0,0,0,0.6);
      background:#ffffff;
    }
    .close-btn{
      position:fixed;
      top:20px;
      right:25px;
      font-size:35px;
      color:white;
      cursor:pointer;
      font-weight:bold;
      text-shadow:0 4px 16px rgba(0,0,0,0.7);
    }
    @keyframes fadeIn{
      from{opacity:0; transform:scale(0.95);}
      to{opacity:1; transform:scale(1);}
    }
  </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="nav-container">
    <div class="logo">
      <i class="fas fa-rocket"></i>
      VCET Orbit
    </div>
    <ul id="navMenu">
      <li><a href="home.html">Home</a></li>
      <li><a href="about_page.html">About</a></li>
      <li><a href="index.html">Campus</a></li>
      <li><a href="notice.html">Notice Board</a></li>
      <li><a href="timetable.html">Time Table</a></li>
      <li><a href="contact.html">Contact</a></li>
      <li><a href="lost_and_found_page.php" class="active">Lost And Found</a></li>
    </ul>
    <div class="hamburger" id="hamburger">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
</nav>

<div class="page-wrapper">
  <section class="lost-found">
    <h1>Lost &amp; Found</h1>
    <div class="section-divider"></div>
    <p>This platform allows VCET students to quickly report missing items and submit found belongings in a clean and organized way.
Every entry includes item details, contact information, and a timestamp to make the process smooth and transparent.
Please make sure the information you submit is accurate, and check back often for updates.</p>

    <!-- SUBMISSION FORM -->
    <form action="backend/submit_lost.php" method="POST" enctype="multipart/form-data" class="lost-form">
      <select name="status" required>
        <option value="" disabled selected>-- Select Item Type --</option>
        <option value="Lost">Lost Item</option>
        <option value="Found">Found Item</option>
      </select>

      <input type="text" name="item_name" placeholder="Item name" required>
      <input type="text" name="location" placeholder="Location">
      <textarea name="description" placeholder="Description..."></textarea>
      <input type="file" name="image">
      <input type="text" name="contact" placeholder="Contact (phone/email)" required>

      <button type="submit">Submit</button>
    </form>

    <!-- ITEMS GRID -->
    <div class="items-board">

    <?php
      $query = "SELECT * FROM lost_found ORDER BY date DESC";
      $result = $mysqli->query($query);

      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {

              $img = $row['image_path'] ? "backend/uploads/" . $row['image_path'] : "";
    ?>

      <div class="item-card">

        <!-- STATUS BADGE -->
        <?php if ($row['status'] === "Lost"): ?>
          <span class="badge lost-badge">LOST</span>
        <?php else: ?>
          <span class="badge found-badge">FOUND</span>
        <?php endif; ?>

        <!-- IMAGE -->
        <?php if (!empty($row['image_path'])): ?>
          <img src="<?= $img ?>" onclick="showPopup('<?= $img ?>')">
        <?php endif; ?>

        <h3><?= htmlspecialchars($row['item_name']) ?></h3>
        <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($row['description'])) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
        <p><strong>Contact:</strong> <?= htmlspecialchars($row['contact']) ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($row['date']) ?></p>

        <!-- MARK AS FOUND -->
        <?php if ($row['status'] === "Lost"): ?>
          <form action="backend/mark_found.php" method="POST">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <button style="background:green;color:white;padding:8px 12px;border:none;border-radius:5px;margin-top:10px;">
              ✔ Mark As Found
            </button>
          </form>
        <?php endif; ?>

        <!-- DELETE -->
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
</div>

<footer>
  <p>© 2025 VCET ORBIT | Made for VCETians, by VCETians</p>
</footer>

<!-- POPUP -->
<div class="image-popup" id="imagePopup">
    <span class="close-btn" onclick="closePopup()">×</span>
    <img id="popupImg" src="">
</div>

<script>
function askAdmin(form){
    let pwd = prompt("Enter admin password:");
    if(!pwd) return false;
    form.password.value = pwd;
    return true;
}

function showPopup(src){
    document.getElementById("popupImg").src = src;
    document.getElementById("imagePopup").style.display = "flex";
}

function closePopup(){
    document.getElementById("imagePopup").style.display = "none";
}

// navbar mobile toggle
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('navMenu');
if (hamburger && navMenu) {
  hamburger.addEventListener('click', () => {
    navMenu.classList.toggle('active');
  });
  document.querySelectorAll('#navMenu a').forEach(link => {
    link.addEventListener('click', () => {
      navMenu.classList.remove('active');
    });
  });
}
</script>

</body>
</html>
