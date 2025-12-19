<?php
include 'config.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$user_id = $_SESSION['user_id'];

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['save'])) {
    $nama = $_POST['nama'];
    $subname = $_POST['subname'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];

    // Upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $folder = "uploads/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $path_gambar = $folder . uniqid() . "_" . basename($gambar);

    if (move_uploaded_file($tmp, $path_gambar)) {
        // Simpan ke database
        $mime = $_FILES['gambar']['type'];
        $query = "INSERT INTO skins (user_id, nama, subname, tanggal, file_path, mime, keterangan)
                  VALUES ('$user_id', '$nama', '$subname', '$tanggal', '$path_gambar', '$mime', '$keterangan')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "<script>alert('✅ Data berhasil ditambahkan!'); window.location='skins.php';</script>";
        } else {
            echo "<script>alert('❌ Gagal menyimpan ke database: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('❌ Upload gambar gagal!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Skin - ML Gallery</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background: linear-gradient(135deg, #0a0a0a, #1a1a2e);
  color: #f1f1f1;
  min-height: 100vh;
  font-family: 'Poppins', sans-serif;
}
.card {
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255,255,255,0.2);
  box-shadow: 0 0 20px rgba(0,255,255,0.1);
  color: #f8f9fa;
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
</style>
</head>
<body>
<div class="container mt-5 pt-5">
  <div class="col-md-6 mx-auto">
    <div class="card p-4 rounded-4">
      <h3 class="mb-4 text-center text-info">+ Tambah Data Skin</h3>
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Nama Skin</label>
          <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Subname</label>
          <input type="text" name="subname" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Tanggal</label>
          <input type="date" name="tanggal" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Gambar</label>
          <input type="file" name="gambar" class="form-control" accept="image/*" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Tipe Skin</label>
          <select name="keterangan" class="form-select">
            <option value="basic">Basic</option>
            <option value="elite">Elite</option>
            <option value="season">Season</option>
            <option value="limited">Limited</option>
            <option value="special">Special</option>
            <option value="epic">Epic</option>
            <option value="limit_edition">Limit Edition</option>
            <option value="legend">Legend</option>
            <option value="mythic">Mythic</option>
          </select>
        </div>
        <div class="d-flex justify-content-between">
          <a href="skins.php" class="btn btn-secondary">Kembali</a>
          <button type="submit" name="save" class="btn btn-custom">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
