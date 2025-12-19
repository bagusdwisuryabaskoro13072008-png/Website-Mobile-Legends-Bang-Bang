<?php
include 'config.php';

// Mulai session jika belum aktif
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) header("Location:index.php");

$user_id = $_SESSION['user_id'];
$id = intval($_GET['id'] ?? 0);

// Ambil data hero
$stmt = $conn->prepare("SELECT * FROM heroes WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$hero = $result->fetch_assoc();
$stmt->close();

if(!$hero) die("Hero tidak ditemukan");

// Proses update
if(isset($_POST['save'])){
    $nama = $_POST['nama'];
    $subname = $_POST['subname'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];

    $filePath = $hero['file_path'];
    $mime = $hero['mime'];

    // Jika user upload gambar baru
    if(!empty($_FILES['gambar']['name'])){
        $uploadDir = "uploads/";
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = basename($_FILES['gambar']['name']);
        $filePath = $uploadDir . uniqid() . "_" . $fileName;
        $mime = mime_content_type($_FILES['gambar']['tmp_name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $filePath);
    }

    // Update menggunakan prepared statement
    $stmt = $conn->prepare("UPDATE heroes SET nama=?, subname=?, tanggal=?, keterangan=?, file_path=?, mime=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssssssii", $nama, $subname, $tanggal, $keterangan, $filePath, $mime, $id, $user_id);

    if($stmt->execute()){
        echo "<script>alert('Hero berhasil diupdate'); window.location='heroes.php';</script>";
    } else {
        echo "<script>alert('Gagal update: ".htmlspecialchars($stmt->error)."');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Hero</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:linear-gradient(135deg,#0a0a0a,#1a1a2e);color:#fff;font-family:Poppins;}
.form-container{max-width:600px;margin:50px auto;padding:25px;border-radius:15px;background:rgba(255,255,255,0.08);box-shadow:0 0 25px rgba(0,255,255,0.3);}
.img-preview{width:70px;border-radius:8px;border:2px solid #0ff;margin-bottom:10px;}
.btn-custom{background:#0ff;color:#000;font-weight:bold;}
.btn-custom:hover{background:#09c;color:#fff;}
</style>
</head>
<body>
<div class="container">
<div class="form-container">
<h3 class="text-info text-center mb-4">Edit Hero</h3>
<form method="POST" enctype="multipart/form-data">
    <label>Nama Hero</label>
    <input type="text" name="nama" class="form-control mb-2" value="<?= htmlspecialchars($hero['nama']) ?>" required>

    <label>Subname</label>
    <input type="text" name="subname" class="form-control mb-2" value="<?= htmlspecialchars($hero['subname']) ?>">

    <label>Tanggal</label>
    <input type="date" name="tanggal" class="form-control mb-2" value="<?= $hero['tanggal'] ?>">

    <label>Keterangan</label>
    <select name="keterangan" class="form-select mb-2">
        <option value="diperoleh" <?= $hero['keterangan']=='diperoleh'?'selected':'' ?>>Diperoleh</option>
        <option value="belum diperoleh" <?= $hero['keterangan']=='belum diperoleh'?'selected':'' ?>>Belum Diperoleh</option>
    </select>

    <label>Gambar</label><br>
    <img src="<?= htmlspecialchars($hero['file_path']) ?>" class="img-preview"><br>
    <input type="file" name="gambar" class="form-control">

    <div class="text-center mt-3">
        <button type="submit" name="save" class="btn btn-custom">Update</button>
        <a href="heroes.php" class="btn btn-outline-info ms-2">Batal</a>
    </div>
</form>
</div>
</div>
</body>
</html>
