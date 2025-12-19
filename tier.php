<?php
include 'config.php';
if (session_status()===PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) header("Location:index.php");

$user_id = $_SESSION['user_id'];
$data = mysqli_query($conn, "SELECT * FROM tier WHERE user_id='$user_id' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Tier - ML Gallery</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {background:linear-gradient(135deg,#0a0a0a,#1a1a2e); color:#fff; font-family:Poppins;}
.table-dark {--bs-table-bg: rgba(255,255,255,0.1);}
img.thumb {width:70px;height:70px;border-radius:8px;border:2px solid #0ff;object-fit:contain;background:rgba(255,255,255,0.05);}
.btn-custom {background:#0ff;color:#000;font-weight:bold;}
.btn-custom:hover {background:#09c;color:white;}
.btn-outline-info {border-color:#0ff;color:#0ff;}
.btn-outline-info:hover {background:#0ff;color:#000;}
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
<h2 class="text-info">🏆 Data Tier Anda</h2>
<div>
<a href="tambah_tier.php" class="btn btn-custom me-2">+ Tambah Tier</a>
<a href="riwayat_tier.php" class="btn btn-outline-info">Riwayat Tier</a>
</div>
</div>

<div class="table-responsive">
<table class="table table-dark table-hover table-bordered align-middle text-center">
<thead>
<tr>
<th>#</th>
<th>Rank Tertinggi</th>
<th>Rank Saat Ini</th>
<th>Tipe Point</th>
<th>Jumlah Point</th>
<th>Dibuat</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
<?php
if(mysqli_num_rows($data)>0){
    $no=1;
    while($row=mysqli_fetch_assoc($data)){
        echo "<tr>
        <td>{$no}</td>
        <td><img src='{$row['rank_tertinggi_img']}' class='thumb'><br><small>{$row['rank_tertinggi']}</small></td>
        <td><img src='{$row['rank_saat_ini_img']}' class='thumb'><br><small>{$row['rank_saat_ini']}</small></td>
        <td>".ucfirst($row['rank_saat_ini_point_type'])."</td>
        <td>{$row['rank_saat_ini_point']}</td>
        <td>".date('d M Y',strtotime($row['created_at']))."</td>
        <td>
            <a href='edit_tier.php?id={$row['id']}' class='btn btn-sm btn-warning me-1'>Edit</a>
            <a href='hapus_tier.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Hapus tier ini?\")'>Hapus</a>
        </td>
        </tr>";
        $no++;
    }
}else{
    echo "<tr><td colspan='7' class='text-center text-secondary'>Belum ada data tier.</td></tr>";
}
?>
</tbody>
</table>
</div>

<a href="dashboard.php" class="btn btn-outline-info mt-3">← Kembali ke Dashboard</a>
</div>
</body>
</html>
