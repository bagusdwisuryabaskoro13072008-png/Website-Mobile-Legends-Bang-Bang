<?php
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$data = mysqli_query($conn, "SELECT * FROM skins WHERE user_id='$user_id' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Skins - ML Gallery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #0a0a0a, #1a1a2e);
      color: #fff;
      font-family: 'Poppins', sans-serif;
    }
    .table-dark {
      --bs-table-bg: rgba(255,255,255,0.1);
    }
    .btn-custom {
      background: #0ff;
      color: #000;
      font-weight: bold;
    }
    .btn-custom:hover {
      background: #09c;
      color: white;
    }
    img.thumb {
      width: 70px;
      border-radius: 8px;
      border: 2px solid #0ff;
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
    <div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="text-info">Daftar Skin Anda</h2>
  <div>
    <a href="gallery_skin.php" class="btn btn-outline-info me-2">🎨 Lihat Galeri</a>
    <a href="tambah_skin.php" class="btn btn-custom">+ Tambah Skin</a>
  </div>
</div>


    <div class="table-responsive">
      <table class="table table-dark table-hover table-bordered align-middle">
        <thead>
          <tr class="text-center">
            <th>#</th>
            <th>Nama</th>
            <th>Subname</th>
            <th>Tanggal</th>
            <th>Gambar</th>
            <th>Keterangan</th>
            <th>Dibuat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (mysqli_num_rows($data) > 0) {
              $no = 1;
              while ($row = mysqli_fetch_assoc($data)) {
                  echo "<tr>
                          <td>{$no}</td>
                          <td>{$row['nama']}</td>
                          <td>{$row['subname']}</td>
                          <td>{$row['tanggal']}</td>
                          <td><img src='{$row['file_path']}' class='thumb'></td>
                          <td>{$row['keterangan']}</td>
                          <td>{$row['created_at']}</td>
                          <td class='text-center'>
                              <a href='{$row['file_path']}' target='_blank' class='btn btn-sm btn-info'>Lihat</a>
                              <a href='edit_skins.php?id={$row['id']}' class='btn btn-sm btn-warning'>Edit</a>
                              <a href='hapus_skin.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Hapus data ini?\")'>Hapus</a>
                          </td>
                        </tr>";
                  $no++;
              }
          } else {
              echo "<tr><td colspan='8' class='text-center text-secondary'>Belum ada data skin ditambahkan.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

    <a href="dashboard.php" class="btn btn-outline-info mt-3">← Kembali ke Dashboard</a>
  </div>
</body>
</html>
