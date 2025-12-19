<?php
include 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$username = $_SESSION['username'] ?? 'Pengguna';
$user_id = $_SESSION['user_id'];

// Hitung jumlah data
$heroCount = $conn->query("SELECT COUNT(*) AS total FROM heroes WHERE user_id='$user_id'")->fetch_assoc()['total'] ?? 0;
$skinCount = $conn->query("SELECT COUNT(*) AS total FROM skins WHERE user_id='$user_id'")->fetch_assoc()['total'] ?? 0;
$tierCount = $conn->query("SELECT COUNT(*) AS total FROM tier WHERE user_id='$user_id'")->fetch_assoc()['total'] ?? 0;
$userCount = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'] ?? 0;

// Ambil data emblem user terakhir
$types = ['physical','jungle','tank','fighter','assassin','mage','marksman','support'];
$emblemRow = $conn->query("SELECT * FROM emblem WHERE user_id='$user_id'")->fetch_assoc();
$levelMap = ['0'=>0,'4'=>1,'10'=>2,'20'=>3,'30'=>4,'40'=>5,'60'=>6,'I'=>7,'II'=>8,'III'=>9,'V'=>10,'VII'=>11,'VIII'=>12,'IX'=>13];
$emblemMax = 0;
$emblemMaxStr = '0';
foreach($types as $t){
    $val = $emblemRow[$t] ?? '0';
    $num = $levelMap[$val] ?? 0;
    if($num > $emblemMax){ $emblemMax = $num; $emblemMaxStr = $val; }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Mobile Legends Gallery</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
<style>
body {
  background-image: url('assets/img/miya.jpg');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  background-attachment: fixed;
  color: #f1f1f1;
  font-family: 'Poppins', sans-serif;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* Navbar */
.navbar {
  background-image: url('assets/img/johnson.png');
  background-size: cover;
  border-bottom: 2px solid #0ff;
  box-shadow: 0 0 15px rgba(0,255,255,0.3);
}
.navbar-brand { color: rgba(255, 230, 0, 1) !important; text-shadow: rgba(255, 153, 0, 0.72) 0 0 50px; font-weight: 600; letter-spacing: 1px; }
.nav-link { color: #ddd !important; transition: 0.3s; }
.nav-link:hover { color: rgba(255, 238, 0, 1) !important; text-shadow: 0 0 15px rgba(253, 255, 110, 1); }

/* Konten di tengah layar */
.main-content {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  animation: fadeIn 1s ease-in-out;
}

.card {
  background: rgba(37, 35, 35, 1);
  border: 1px solid rgba(255,255,255,0.2);
  box-shadow: 0 0 20px rgba(0,255,255,0.1);
  color: #f8f9fa;
  border-radius: 20px;
  padding: 30px;
  max-width: 600px;
  width: 100%;
  text-align: center;
}

hr { border-color: #0ff; opacity: 0.4; }
.btn-info {
  background-color: #0ff; color: #000; font-weight: 600; border: none;
}
.btn-info:hover {
  box-shadow: 0 0 10px #0ff;
  background-color: #00dada;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Modal */
.modal-content {
  background: rgba(0,0,0,0.85);
  border: 2px solid #0ff;
  color: #fff;
  box-shadow: 0 0 20px rgba(0,255,255,0.3);
}

.stat-box {
  background: transparent;
  border: none;
  padding: 10px;
  text-align: center;
}
.stat-box p {
  color: #aaa;
  font-size: 0.9rem;
  margin-top: 5px;
}
.stat-number {
  font-size: 2.5rem;
  color: #0ff;
  font-weight: bold;
  text-shadow: 0 0 10px #0ff, 0 0 20px #0ff;
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand" href="#">⚔️ MOBILE LEGENDS BANG BANG</a>
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

<!-- Konten di Tengah -->
<div class="main-content">
  <div class="card">
    <h2 class="mb-3">
      Selamat datang, <span class="text-info"><?php echo htmlspecialchars($username); ?></span>!
    </h2>
    <p class="lead mb-4">Pilih menu di atas untuk mulai mengelola data galeri Mobile Legends kamu.</p>

    <button class="btn btn-info w-100 mt-2" data-bs-toggle="modal" data-bs-target="#statistikModal">
      📊 Lihat Statistik Data
    </button>

    <hr class="mt-4">
    <p class="small text-secondary mb-0">
      © 2025 <span class="text-info">ML Gallery System</span> | Developed by <b>Bagus Dwi Surya Baskoro</b>
    </p>
  </div>
</div>

<!-- Modal Statistik -->
<div class="modal fade" id="statistikModal" tabindex="-1" aria-labelledby="statistikLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4 p-4">
      <div class="modal-header border-info">
        <h4 class="modal-title text-info" id="statistikLabel">📈 Statistik Data Galeri Keseluruhan</h4>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3 text-center">
          <div class="col-md-3">
            <div class="stat-box">
              <div class="stat-number"><?php echo $heroCount; ?></div>
              <p>Jumlah Hero</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <div class="stat-number"><?php echo $skinCount; ?></div>
              <p>Jumlah Skin</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <div class="stat-number"><?php echo $emblemMaxStr; ?></div>
              <p>Level Emblem Tertinggi</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <div class="stat-number"><?php echo $tierCount; ?></div>
              <p>Jumlah Tier</p>
            </div>
          </div>
          <div class="col-md-6 mt-3 mx-auto">
            <div class="stat-box">
              <div class="stat-number"><?php echo $userCount; ?></div>
              <p>Total Akun Terdaftar</p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
