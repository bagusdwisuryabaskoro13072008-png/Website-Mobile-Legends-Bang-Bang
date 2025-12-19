<?php
include 'config.php';
if (session_status()===PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) header("Location: index.php");
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Rekap Data - ML Gallery</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg,#0a0a0a,#1a1a2e);
    color:#fff;
    font-family:Poppins, sans-serif;
}
h1,h2 {color:#0ff;}
table {background: rgba(255,255,255,0.05);}
table th, table td {text-align:center; vertical-align:middle;}
img.thumb {width:50px; border-radius:6px; border:2px solid #0ff;}
.btn-print {background:#0ff;color:#000;font-weight:bold;margin-top:15px;}
.btn-print:hover{background:#09c;color:#fff;}
/* Print styles */
@media print {
    body {background:#fff; color:#000;}
    table {background:#fff;}
    .btn-print, a {display:none;}
    .pagebreak {page-break-before:always;}
}
.signature {margin-top:150px; text-align:right; margin-right:50px;}
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
<div class="container">

<h1 class="mb-4">📊 Laporan Rekap Data Anda</h1>
<a href="dashboard.php" class="btn btn-outline-info mb-4">← Kembali ke Dashboard</a>

<?php
$tables = [
    'Heroes' => "SELECT nama, subname, tanggal, file_path, keterangan, created_at FROM heroes WHERE user_id='$user_id' ORDER BY id DESC",
    'Skins' => "SELECT nama, subname, tanggal, file_path, keterangan, created_at FROM skins WHERE user_id='$user_id' ORDER BY id DESC",
    'Emblem' => "SELECT * FROM emblem WHERE user_id='$user_id' ORDER BY updated_at DESC",
    'Tier' => "SELECT * FROM tier WHERE user_id='$user_id' ORDER BY created_at DESC"
];

foreach($tables as $title => $query){
    echo "<h2 class='mt-4'>$title</h2>";
    $result = mysqli_query($conn,$query);
    if(mysqli_num_rows($result)>0){
        echo "<div class='table-responsive'><table class='table table-dark table-bordered table-hover align-middle'>
              <thead><tr>";
        if($title=='Emblem'){
            $row = mysqli_fetch_assoc($result);
            foreach($row as $col => $val){
                echo "<th>".ucfirst($col)."</th>";
            }
            echo "</tr></thead><tbody>";
            mysqli_data_seek($result,0);
            while($row=mysqli_fetch_assoc($result)){
                echo "<tr>";
                foreach($row as $val){
                    echo "<td>$val</td>";
                }
                echo "</tr>";
            }
        }elseif($title=='Tier'){
            echo "<th>#</th><th>Rank Tertinggi</th><th>Rank Saat Ini</th><th>Tipe Point</th><th>Jumlah Point</th><th>Dibuat</th></tr></thead><tbody>";
            $no=1;
            while($row=mysqli_fetch_assoc($result)){
                echo "<tr>
                <td>$no</td>
                <td><img src='{$row['rank_tertinggi_img']}' class='thumb'><br>{$row['rank_tertinggi']}</td>
                <td><img src='{$row['rank_saat_ini_img']}' class='thumb'><br>{$row['rank_saat_ini']}</td>
                <td>".ucfirst($row['rank_saat_ini_point_type'])."</td>
                <td>{$row['rank_saat_ini_point']}</td>
                <td>".date('d M Y',strtotime($row['created_at']))."</td>
                </tr>";
                $no++;
            }
        }else{
            echo "<th>#</th><th>Nama</th><th>Subname</th><th>Tanggal</th><th>Gambar</th><th>Keterangan</th><th>Dibuat</th></tr></thead><tbody>";
            $no=1;
            while($row=mysqli_fetch_assoc($result)){
                echo "<tr>
                <td>$no</td>
                <td>{$row['nama']}</td>
                <td>{$row['subname']}</td>
                <td>{$row['tanggal']}</td>
                <td><img src='{$row['file_path']}' class='thumb'></td>
                <td>{$row['keterangan']}</td>
                <td>{$row['created_at']}</td>
                </tr>";
                $no++;
            }
        }
        echo "</tbody></table></div>";
    }else{
        echo "<p class='text-secondary'>Belum ada data $title.</p>";
    }
}
?>

<!-- Halaman terakhir untuk tanda tangan -->
<div class="pagebreak">
<h2 class="mt-4">Tanda Tangan</h2>
<p class="signature">........................................<br>User</p>
</div>

<button class="btn btn-print" onclick="window.print()">🖨 Cetak Laporan</button>
</div>
</body>
</html>
