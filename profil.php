<?php
include 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) header("Location: index.php");
$user_id = $_SESSION['user_id'];

// Ambil profil user dari database
$result = mysqli_query($conn, "SELECT * FROM profil WHERE user_id='$user_id' LIMIT 1");
if (mysqli_num_rows($result) == 0) {
    mysqli_query($conn, "INSERT INTO profil (user_id, foto) VALUES ('$user_id', 'default.png')");
    $result = mysqli_query($conn, "SELECT * FROM profil WHERE user_id='$user_id' LIMIT 1");
}
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil Saya</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg, #0a0a0a, #1a1a2e);
    color: #fff;
    font-family: 'Poppins', sans-serif;
}
.card {
    background: rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    box-shadow: 0 0 25px cyan;
    padding: 30px;
}
img.profile-pic {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 3px solid cyan;
    object-fit: cover;
    box-shadow: 0 0 25px cyan;
    transition: .3s;
}
img.profile-pic:hover {transform: scale(1.05);}
.btn-cyan {
    background: cyan;
    color: #000;
    font-weight: bold;
}
.btn-cyan:hover {
    background: #00ffffa8;
}
.btn-back {
    background: #444;
    color: #fff;
    font-weight: bold;
}
.btn-back:hover {
    background: #666;
}
</style>
</head>
<body class="p-4">
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
<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 700px;">
        <div class="text-center mb-4">
            <img src="assets/img/profil/<?php echo htmlspecialchars($row['foto']); ?>" 
                 onerror="this.src='assets/img/profil/default.png';"
                 class="profile-pic">
            <h3 class="mt-3 text-info">
                <?php echo htmlspecialchars($row['nama_lengkap'] ?: 'Belum Diisi'); ?>
            </h3>
            <p class="text-muted">@<?php echo htmlspecialchars($row['username'] ?: 'unknown'); ?></p>
        </div>

        <table class="table table-dark table-striped align-middle">
            <tr><th>Email</th><td><?php echo htmlspecialchars($row['email'] ?: '-'); ?></td></tr>
            <tr><th>Telepon</th><td><?php echo htmlspecialchars($row['telepon'] ?: '-'); ?></td></tr>
            <tr><th>Tanggal Lahir</th><td><?php echo htmlspecialchars($row['tanggal_lahir'] ?: '-'); ?></td></tr>
            <tr><th>Jenis Kelamin</th><td><?php echo htmlspecialchars($row['jenis_kelamin'] ?: '-'); ?></td></tr>
            <tr><th>Alamat</th><td><?php echo htmlspecialchars($row['alamat'] ?: '-'); ?></td></tr>
            <tr><th>Pekerjaan</th><td><?php echo htmlspecialchars($row['pekerjaan'] ?: '-'); ?></td></tr>
            <tr><th>Status</th><td><?php echo htmlspecialchars($row['status'] ?: '-'); ?></td></tr>
            <tr><th>Bio</th><td><?php echo nl2br(htmlspecialchars($row['bio'] ?: '-')); ?></td></tr>
        </table>

        <div class="text-center mt-4">
            <a href="edit_profil.php" class="btn btn-cyan btn-lg me-2">
                ✏️ Edit Profil
            </a>
            <a href="hapus_profil.php" onclick="return confirm('Yakin ingin menghapus profil Anda?')" 
               class="btn btn-danger btn-lg me-2">
               🗑 Hapus
            </a>
            <a href="dashboard.php" class="btn btn-back btn-lg">
                ⬅️ Kembali
            </a>
        </div>
    </div>
</div>

</body>
</html>
