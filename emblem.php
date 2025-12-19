<?php
include 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) header("Location:index.php");
$user_id = $_SESSION['user_id'];

// Ambil data emblem user
$data = mysqli_query($conn,"SELECT * FROM emblem WHERE user_id='$user_id' LIMIT 1");
if(mysqli_num_rows($data)==0){
    mysqli_query($conn,"INSERT INTO emblem(user_id) VALUES('$user_id')");
    $data = mysqli_query($conn,"SELECT * FROM emblem WHERE user_id='$user_id' LIMIT 1");
}
$row = mysqli_fetch_assoc($data);

// Data dropdown
$levels = ['0','4','10','20','30','40','60','I','II','III','V','VII','VIII','IX'];
$types = ['physical','jungle','tank','fighter','assassin','mage','marksman','support'];

// Update emblem
if(isset($_POST['save'])){
    $updates=[];
    foreach($types as $t){
        $val=$_POST[$t]??'0';
        $updates[]="$t='$val'";
    }
    $sql="UPDATE emblem SET ".implode(',',$updates).", updated_at=NOW() WHERE user_id='$user_id'";
    if(mysqli_query($conn,$sql)){
        echo "<script>alert('Emblem berhasil diperbarui!');window.location='emblem.php';</script>";
    } else echo "<script>alert('Gagal menyimpan data');</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Emblem</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{
  background:linear-gradient(135deg,#0a0a0a,#1a1a2e);
  color:#fff;font-family:Poppins;
}
.card{background:rgba(0,255,255,0.1);border:1px solid cyan;box-shadow:0 0 20px rgba(0,255,255,0.4);transition:0.3s;}
.card:hover{transform:translateY(-3px);box-shadow:0 0 35px cyan;}
select.form-select{color:#000;font-weight:bold;}
#statCard{
  background:rgba(255,255,255,0.05);
  border:1px solid rgba(0,255,255,0.3);
  box-shadow:0 0 15px rgba(0,255,255,0.2);
  transition:all .4s ease;
}
#statCard:hover{box-shadow:0 0 40px rgba(0,255,255,0.7);}
#statsTable{
  width:100%;
  border-collapse:collapse;
  margin-top:10px;
  font-size:16px;
}
#statsTable td,#statsTable th{
  border-bottom:1px solid rgba(0,255,255,0.2);
  padding:8px 10px;
  text-align:center;
}
.shine{
  position:relative;
  overflow:hidden;
}
.shine::before{
  content:'';
  position:absolute;
  top:0;
  left:-75%;
  width:50%;
  height:100%;
  background:linear-gradient(120deg,rgba(255,255,255,0) 0%,rgba(255,255,255,0.5) 50%,rgba(255,255,255,0) 100%);
  transform:skewX(-25deg);
  transition:0.8s;
}
.shine:hover::before{left:125%;}
.emblem-card{
  background:rgba(0,255,255,0.1);
  border:1px solid cyan;
  box-shadow:0 0 10px rgba(0,255,255,0.3);
  border-radius:10px;
  padding:10px;
  transition:all .3s ease;
}
.emblem-card:hover{
  transform:translateY(-4px) scale(1.05);
  box-shadow:0 0 25px cyan;
}
.emblem-name{
  font-size:14px;
  color:cyan;
  text-transform:uppercase;
  font-weight:bold;
}
.emblem-value{
  font-size:22px;
  color:#fff;
  font-weight:bold;
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
<div class="container mt-4">
  <h2 class="text-info mb-4">⚔️ Emblem Stats</h2>

  <form method="POST">
    <div class="row g-3">
      <?php foreach($types as $t): ?>
      <div class="col-md-3">
        <div class="card p-3 text-center">
          <label class="form-label text-uppercase"><?php echo $t;?></label>
          <select name="<?php echo $t;?>" class="form-select emblem-select" data-type="<?php echo $t;?>">
            <?php foreach($levels as $l): ?>
              <option value="<?php echo $l;?>" <?php echo ($row[$t]==$l)?'selected':'';?>><?php echo $l;?></option>
            <?php endforeach;?>
          </select>
        </div>
      </div>
      <?php endforeach;?>
    </div>

    <div class="text-center mt-4">
      <button class="btn btn-info btn-lg" name="save">💾 Simpan Perubahan</button>
      <a href="dashboard.php" class="btn btn-outline-info btn-lg ms-2">Kembali</a>
    </div>
  </form>

  <div class="mt-5">
    <div id="statCard" class="rounded-4 p-4 shine">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-info mb-0">📊 Statistik Emblem Saat Ini</h4>
        <button class="btn btn-outline-info btn-sm" id="toggleStats">⬇️ Lihat Detail</button>
      </div>
      <div id="statsContent" style="display:none;">
        <div class="row" id="emblemCards">
          <?php foreach($types as $t): ?>
          <div class="col-md-3 mb-3">
            <div class="emblem-card text-center">
              <div class="emblem-name"><?php echo strtoupper($t);?></div>
              <div class="emblem-value" id="val_<?php echo $t;?>"><?php echo $row[$t];?></div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
        <div class="text-center mt-3">
          <button onclick="window.print()" class="btn btn-success">🖨️ Cetak Statistik</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('toggleStats').addEventListener('click',()=>{
  const box=document.getElementById('statsContent');
  box.style.display = box.style.display==='none'?'block':'none';
  document.getElementById('toggleStats').textContent = box.style.display==='none'?'⬇️ Lihat Detail':'⬆️ Sembunyikan';
});

// Update nilai di card secara real-time
document.querySelectorAll('.emblem-select').forEach(sel=>{
  sel.addEventListener('change',()=>{
    const type=sel.dataset.type;
    const value=sel.value;
    const valBox=document.getElementById('val_'+type);
    valBox.innerText=value;
    valBox.style.transition='transform 0.3s ease, color 0.3s ease';
    valBox.style.transform='scale(1.3)';
    valBox.style.color='cyan';
    setTimeout(()=>{
      valBox.style.transform='scale(1)';
      valBox.style.color='#fff';
    },400);
  });
});
</script>
</body>
</html>
