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

// Ambil data riwayat user
$query = mysqli_query($conn, "SELECT * FROM tier_history WHERE user_id='$user_id' ORDER BY created_at DESC");
$riwayat_count = mysqli_num_rows($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Tier - ML Gallery</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background: linear-gradient(135deg, #0a0a0a, #1a1a2e);
  color: #fff;
  font-family: 'Poppins', sans-serif;
}
.card-tier {
  background: rgba(255,255,255,0.08);
  border-radius: 15px;
  box-shadow: 0 0 25px rgba(0,255,255,0.2);
  padding: 20px;
  text-align: center;
  transition: transform 0.3s;
  position: relative;
}
.card-tier:hover {
  transform: translateY(-5px);
  box-shadow: 0 0 35px rgba(0,255,255,0.4);
}
img.tier-img {
  width: 120px;       /* lebar tetap */
  height: 120px;      /* tinggi sama dengan lebar untuk 1:1 */
  object-fit: cover;  /* memastikan gambar memenuhi kotak tanpa terdistorsi */
  border-radius: 15%; /* bisa diganti 50% jika mau lingkaran, untuk 1:1 pakai lebih kecil */
  border: 3px solid #0ff;
  background: rgba(255,255,255,0.05);
  margin-bottom: 10px;
}

img.empty {
  filter: grayscale(100%) brightness(0.5);
}
.rank-text {
  font-size: 18px;
  font-weight: bold;
  color: #0ff;
}
.date-text {
  font-size: 14px;
  color: #bbb;
}
.delete-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 16px;
}
.update-btn {
  margin-top: 10px;
}
.notice {
  margin-top: 10px;
  font-size: 14px;
  color: #0ff;
}
</style>
</head>
<body class="p-4">
<div class="container mt-4">

  <?php if(isset($_SESSION['message'])): ?>
  <div class="alert alert-info text-center">
      <?php 
          echo $_SESSION['message']; 
          unset($_SESSION['message']); 
      ?>
  </div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-info">📜 Riwayat Tier</h2>
    <a href="tier.php" class="btn btn-outline-info">← Kembali</a>
  </div>

  <div class="alert alert-info text-center">
    Riwayat tier tersimpan otomatis setiap <strong>3 bulan sekali</strong> berdasarkan tier terakhir kamu.
  </div>

  <div class="row g-4">
  <?php if ($riwayat_count > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($query)): ?>
      <?php 
          $created_at = strtotime($row['created_at']);
          $now = time();
          $diff_months = ($now - $created_at) / (30*24*60*60); // kira-kira bulan
      ?>
      <div class="col-md-4">
        <div class="card-tier">
          <!-- Tombol hapus -->
          <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $row['id']; ?>">❌</button>

          <img src="<?php echo $row['rank_saat_ini_img'] ? $row['rank_saat_ini_img'] : 'assets/img/unknown.png'; ?>" 
               alt="Tier" 
               class="tier-img <?php echo !$row['rank_saat_ini_img'] ? 'empty' : ''; ?>">

          <div class="rank-text">
            Rank Tertinggi: 
            <?php echo $row['rank_tertinggi'] ? $row['rank_tertinggi'] : '<span class="text-danger">Belum ada</span>'; ?>
          </div>
          <div class="rank-text mt-1">
            Rank Terakhir: 
            <?php echo $row['rank_saat_ini'] ? $row['rank_saat_ini'] : '<span class="text-warning">Belum ada informasi!</span>'; ?>
          </div>
          <div class="date-text mt-2">
            Disimpan pada: <?php echo date('d M Y', strtotime($row['created_at'])); ?>
          </div>

          <?php if($diff_months >= 3): ?>
              <div class="notice">✅ Riwayat otomatis tersimpan</div>
          <?php else: ?>
              <a href="edit_tier.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning update-btn">Ubah Tier</a>
          <?php endif; ?>

        </div>
      </div>
      <?php endwhile; ?>
  <?php else: ?>
      <div class="col-12 text-center">
        <img src="assets/img/unknown.png" class="tier-img empty" alt="?">
        <div class="rank-text">Belum ada data riwayat tier</div>
      </div>
  <?php endif; ?>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('.delete-btn').click(function(){
        var id = $(this).data('id');
        if(confirm("Apakah kamu yakin ingin menghapus riwayat ini?")) {
            window.location.href = 'delete_tier.php?id=' + id;
        }
    });
});
</script>
</body>
</html>
