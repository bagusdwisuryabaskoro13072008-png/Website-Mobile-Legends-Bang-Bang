<?php
include 'config.php';
if (session_status()===PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) header("Location:index.php");

$id = $_GET['id'] ?? 0;
$result = mysqli_query($conn, "SELECT * FROM skins WHERE id='$id' AND user_id='{$_SESSION['user_id']}'");
$skin = mysqli_fetch_assoc($result);
if (!$skin) die("Skin tidak ditemukan");

// Proses update
if(isset($_POST['save'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $subname = mysqli_real_escape_string($conn, $_POST['subname']);
    $tanggal = $_POST['tanggal'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    // Upload gambar baru jika ada
    $filePath = $skin['file_path'];
    if($_FILES['gambar']['name']){
        $uploadDir = "uploads/";
        $fileName = basename($_FILES['gambar']['name']);
        $targetFile = $uploadDir . uniqid() . "_" . $fileName;
        if(move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)){
            $filePath = $targetFile;
        }
    }

    $sql = "UPDATE skins SET nama='$nama', subname='$subname', tanggal='$tanggal', keterangan='$keterangan', file_path='$filePath' WHERE id='$id'";
    if(mysqli_query($conn,$sql)){
        echo "<script>alert('✅ Skin berhasil diupdate!'); window.location='skins.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal update!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Skin</title>
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
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 25px rgba(0, 255, 255, 0.3);
}
.img-preview {
    width: 100px;
    height: 100px;
    border-radius: 8px;
    border: 2px solid #0ff;
    margin-bottom: 10px;
    object-fit: cover;
}
.btn-custom {
    background-color: cyan;
    color: #000;
    font-weight: bold;
}
.btn-custom:hover {
    background-color: #00ffffa1;
    color: #000;
}
</style>
</head>
<body>
<div class="container">
<div class="form-container">
<h3 class="text-info text-center mb-4">✏️ Edit Skin</h3>
<form method="POST" enctype="multipart/form-data">

    <label>Nama Skin</label>
    <input type="text" name="nama" class="form-control mb-2" value="<?php echo htmlspecialchars($skin['nama']); ?>" required>
    
    <label>Subname</label>
    <input type="text" name="subname" class="form-control mb-2" value="<?php echo htmlspecialchars($skin['subname']); ?>">
    
    <label>Tanggal</label>
    <input type="date" name="tanggal" class="form-control mb-2" value="<?php echo htmlspecialchars($skin['tanggal']); ?>">
    
    <label>Keterangan (Tier Skin)</label>
    <select name="keterangan" class="form-select mb-2">
        <option value="basic" <?php if($skin['keterangan']=='basic') echo 'selected'; ?>>Basic</option>
        <option value="elite" <?php if($skin['keterangan']=='elite') echo 'selected'; ?>>Elite</option>
        <option value="season" <?php if($skin['keterangan']=='season') echo 'selected'; ?>>Season</option>
        <option value="limited" <?php if($skin['keterangan']=='limited') echo 'selected'; ?>>Limited</option>
        <option value="special" <?php if($skin['keterangan']=='special') echo 'selected'; ?>>Special</option>
        <option value="epic" <?php if($skin['keterangan']=='epic') echo 'selected'; ?>>Epic</option>
        <option value="limit_edition" <?php if($skin['keterangan']=='limit_edition') echo 'selected'; ?>>Limit Edition</option>
        <option value="legend" <?php if($skin['keterangan']=='legend') echo 'selected'; ?>>Legend</option>
        <option value="mythic" <?php if($skin['keterangan']=='mythic') echo 'selected'; ?>>Mythic</option>
    </select>

    <label>Gambar Skin</label><br>
    <img src="<?php echo htmlspecialchars($skin['file_path']); ?>" class="img-preview"><br>
    <input type="file" name="gambar" class="form-control">

    <div class="text-center mt-3">
        <button type="submit" name="save" class="btn btn-custom">💾 Update</button>
        <a href="skins.php" class="btn btn-outline-info ms-2">❌ Batal</a>
    </div>
</form>
</div>
</div>
</body>
</html>
