<?php
include 'config.php';

// Session hanya jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['save'])) {
    $user_id = $_SESSION['user_id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $subname = mysqli_real_escape_string($conn, $_POST['subname']);
    $tanggal = $_POST['tanggal'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileName = basename($_FILES['gambar']['name']);
    $targetFile = $uploadDir . uniqid() . "_" . $fileName;
    $mime = mime_content_type($_FILES['gambar']['tmp_name']);

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)) {
        $sql = "INSERT INTO heroes (user_id, nama, subname, tanggal, file_path, mime, keterangan, created_at)
                VALUES ('$user_id', '$nama', '$subname', '$tanggal', '$targetFile', '$mime', '$keterangan', NOW())";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Hero berhasil ditambahkan!'); window.location='heroes.php';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan ke database! ".mysqli_error($conn)."');</script>";
        }
    } else {
        echo "<script>alert('Gagal mengupload gambar!');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Hero - ML Gallery</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background: linear-gradient(135deg, #0a0a0a, #1a1a2e);
  color: #fff;
  font-family: 'Poppins', sans-serif;
}
.form-container {
  max-width: 600px;
  margin: 50px auto;
  padding: 25px;
  border-radius: 15px;
  background: rgba(255,255,255,0.08);
  box-shadow: 0 0 25px rgba(0,255,255,0.3);
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
<div class="container">
  <div class="form-container">
    <h2 class="text-center text-info mb-4">Tambah Data Hero</h2>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Nama Hero</label>
        <input type="text" name="nama" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Subname</label>
        <input type="text" name="subname" class="form-control">
      </div>
      <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="tanggal" class="form-control">
      </div>
      <div class="mb-3">
        <label>Keterangan</label>
        <select name="keterangan" class="form-select">
          <option value="diperoleh">Diperoleh</option>
          <option value="belum diperoleh">Belum Diperoleh</option>
        </select>
      </div>
      <div class="mb-3">
        <label>Gambar</label>
        <input type="file" name="gambar" class="form-control" accept="image/*" required>
      </div>
      <div class="text-center mt-4">
        <button type="submit" name="save" class="btn btn-custom">Simpan</button>
        <a href="heroes.php" class="btn btn-outline-info ms-2">Batal</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
