<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tentang Kami - ML Gallery</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background: linear-gradient(135deg, #0a0a0a, #1a1a2e);
  color: #f1f1f1;
  font-family: 'Poppins', sans-serif;
}
.navbar {
  background: #111;
  border-bottom: 2px solid #0ff;
  box-shadow: 0 0 15px rgba(0,255,255,0.3);
}
h1, h3 { color: #0ff; text-shadow: 0 0 10px #0ff; }
.section {
  padding: 60px 0;
  text-align: center;
}
.card {
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.2);
  color: #eee;
  border-radius: 15px;
  transition: 0.4s;
}
.card:hover {
  transform: translateY(-10px);
  box-shadow: 0 0 20px #0ff;
}
footer {
  text-align: center;
  padding: 20px;
  color: #aaa;
  background: #0d0d0d;
  margin-top: 60px;
}
</style>
</head>
<body>
  
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand" href="#">⚔️ ML Gallery</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">Tentang Kami</a></li>
        <li class="nav-item"><a class="nav-link" href="heroes.php">Heros</a></li>
        <li class="nav-item"><a class="nav-link" href="skins.php">Skins</a></li>
        <li class="nav-item"><a class="nav-link" href="emblem.php">Emblem</a></li>
        <li class="nav-item"><a class="nav-link" href="tier.php">Tier</a></li>
        <li class="nav-item"><a class="nav-link" href="report.php">Laporan</a></li>
        <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
        <li class="nav-item"><a class="nav-link text-danger fw-bold" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="section mt-5">
  <div class="container mt-5">
    <h1 class="mb-4">Tentang ML Gallery</h1>
    <p class="lead mb-5">ML Gallery adalah platform digital yang memudahkan pemain Mobile Legends untuk mengelola koleksi Hero, Skin, Emblem, dan Tier secara personal dengan tampilan modern dan interaktif.</p>
    <div class="row justify-content-center g-4">
      <div class="col-md-4">
        <div class="card p-4">
          <h3>Misi Kami</h3>
          <p>Menghadirkan pengalaman terbaik dalam pengelolaan data Mobile Legends dengan sistem CRUD yang cepat, elegan, dan mudah digunakan.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4">
          <h3>Tim Pengembang</h3>
          <p>Dikembangkan oleh <strong>Bagus Dwi Surya Baskoro</strong> dengan dedikasi untuk menghadirkan sistem web yang intuitif dan fungsional.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4">
          <h3>Teknologi</h3>
          <p>Menggunakan PHP, MySQL, Bootstrap, dan efek CSS/JS modern dengan dukungan desain bertema <em>Mobile Legends</em>.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<footer>
  <p>© 2025 ML Gallery System | All Rights Reserved</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
