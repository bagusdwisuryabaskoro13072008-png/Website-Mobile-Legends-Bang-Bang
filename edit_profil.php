<?php
include 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Ambil data profil
$query = mysqli_query($conn, "SELECT * FROM profil WHERE user_id='$user_id' LIMIT 1");
$profil = mysqli_fetch_assoc($query);

// Jika belum ada data profil, buat default
if (!$profil) {
    mysqli_query($conn, "INSERT INTO profil (user_id, foto, nama_lengkap) VALUES ('$user_id', 'default.png', '')");
    $profil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM profil WHERE user_id='$user_id' LIMIT 1"));
}

// Proses update
if (isset($_POST['update'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $pekerjaan = $_POST['pekerjaan'];
    $status = $_POST['status'];
    $bio = $_POST['bio'];

    // Upload foto jika ada
    $foto = $profil['foto'];
    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "assets/img/profil/";
        $file_name = basename($_FILES["foto"]["name"]);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','svg','webp'];
        if (in_array($file_ext, $allowed)) {
            $new_name = "profil_" . $user_id . "." . $file_ext;
            $target_file = $target_dir . $new_name;
            move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
            $foto = $new_name;
        }
    }

    $update = mysqli_query($conn, "UPDATE profil SET 
        foto='$foto',
        nama_lengkap='$nama_lengkap',
        username='$username',
        email='$email',
        telepon='$telepon',
        tanggal_lahir='$tanggal_lahir',
        jenis_kelamin='$jenis_kelamin',
        alamat='$alamat',
        pekerjaan='$pekerjaan',
        status='$status',
        bio='$bio'
        WHERE user_id='$user_id'
    ");

    if ($update) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='profil.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui profil!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Profil</title>
<style>
body {
  background: radial-gradient(circle at top, #001, #000);
  font-family: 'Poppins', sans-serif;
  color: #fff;
  margin: 0;
  padding: 0;
}
.container {
  max-width: 550px;
  margin: 50px auto;
  padding: 30px;
  background: rgba(10, 15, 30, 0.9);
  border: 2px solid cyan;
  border-radius: 15px;
  box-shadow: 0 0 20px cyan;
  text-align: left;
}

/* Label cyan dengan efek shadow */
label {
  color: #fff !important;
  font-weight: 600;
  text-shadow: 0 0 5px cyan, 0 0 10px cyan;
  font-size: 15px;
  margin-top: 10px;
  display: block;
}

/* Input */
input, textarea, select {
  background-color: rgba(255,255,255,0.9);
  color: #000;
  border: 1px solid cyan;
  border-radius: 8px;
  padding: 10px;
  width: 100%;
  transition: 0.3s;
}
input:focus, textarea:focus, select:focus {
  border-color: cyan;
  box-shadow: 0 0 10px cyan;
  outline: none;
}

/* Tombol */
.btn {
  margin-top: 20px;
  border: none;
  padding: 12px 25px;
  border-radius: 10px;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
}

/* Tombol Simpan */
.btn-save {
  background: cyan;
  color: #000;
}
.btn-save:hover {
  background: #00e6e6;
  box-shadow: 0 0 15px cyan;
  transform: scale(1.05);
}

/* Tombol Cancel */
.btn-cancel {
  background: transparent;
  color: cyan;
  border: 2px solid cyan;
  margin-left: 10px;
}
.btn-cancel:hover {
  background: cyan;
  color: #000;
  box-shadow: 0 0 15px cyan;
  transform: scale(1.05);
}

/* Judul */
h2 {
  color: cyan;
  text-shadow: 0 0 10px cyan;
  text-align: center;
  margin-bottom: 20px;
}

/* Gambar Profil */
.profile-pic {
  display: block;
  margin: 0 auto 15px;
  border-radius: 50%;
  border: 2px solid cyan;
  box-shadow: 0 0 15px cyan;
  width: 100px;
  height: 100px;
  object-fit: cover;
}
</style>
</head>
<body>
<div class="container">
    <h2>Edit Profil</h2>
    <form method="POST" enctype="multipart/form-data">
        <img src="assets/img/profil/<?php echo $profil['foto']; ?>" class="profile-pic" alt="Foto Profil">

        <label>Foto Profil</label>
        <input type="file" name="foto" accept=".jpg,.jpeg,.png,.svg,.webp">

        <label>Nama Lengkap</label>
        <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($profil['nama_lengkap']) ?>" required>

        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($profil['username']) ?>">

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($profil['email']) ?>">

        <label>Telepon</label>
        <input type="text" name="telepon" value="<?= htmlspecialchars($profil['telepon']) ?>">

        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($profil['tanggal_lahir']) ?>">

        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin">
            <option value="">-- Pilih --</option>
            <option value="Laki-laki" <?= $profil['jenis_kelamin']=='Laki-laki'?'selected':'' ?>>Laki-laki</option>
            <option value="Perempuan" <?= $profil['jenis_kelamin']=='Perempuan'?'selected':'' ?>>Perempuan</option>
        </select>

        <label>Alamat</label>
        <textarea name="alamat" rows="2"><?= htmlspecialchars($profil['alamat']) ?></textarea>

        <label>Pekerjaan</label>
        <input type="text" name="pekerjaan" value="<?= htmlspecialchars($profil['pekerjaan']) ?>">

        <label>Status</label>
        <input type="text" name="status" value="<?= htmlspecialchars($profil['status']) ?>">

        <label>Bio / Tentang Saya</label>
        <textarea name="bio" rows="3"><?= htmlspecialchars($profil['bio']) ?></textarea>

        <div style="text-align:center;">
            <button type="submit" name="update" class="btn btn-save">💾 Simpan</button>
            <a href="profil.php" class="btn btn-cancel">✖ Batal</a>
        </div>
    </form>
</div>
</body>
</html>
