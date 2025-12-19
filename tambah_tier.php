<?php
include 'config.php';
if (session_status()===PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) header("Location:index.php");
$user_id = $_SESSION['user_id'];

// Data dropdown rank (.webp)
$ranks = [
    ['name'=>'Warrior','img'=>'assets/img/tier/warrior.webp'],
    ['name'=>'Elite','img'=>'assets/img/tier/elite.webp'],
    ['name'=>'Master','img'=>'assets/img/tier/Master.webp'],
    ['name'=>'Grandmaster','img'=>'assets/img/tier/Grandmaster.webp'],
    ['name'=>'Epic','img'=>'assets/img/tier/epic.webp'],
    ['name'=>'Legend','img'=>'assets/img/tier/Legend.webp'],
    ['name'=>'Mythic','img'=>'assets/img/tier/mythic romawi.webp'],
    ['name'=>'Mythical Honor','img'=>'assets/img/tier/mythic honor.webp'],
    ['name'=>'Mythical Glory','img'=>'assets/img/tier/mythic glory.webp'],
    ['name'=>'Mythical Immortal','img'=>'assets/img/tier/mythic immortal.webp']
];

// Proses submit
if(isset($_POST['save'])){
    $rank_high = $_POST['rank_high'];
    $rank_high_img = $_POST['rank_high_img'];
    $rank_high_point_type = $_POST['rank_high_point_type'];
    $rank_high_point = $_POST['rank_high_point'] ?? 0;

    $rank_current = $_POST['rank_current'];
    $rank_current_img = $_POST['rank_current_img'];
    $rank_current_point_type = $_POST['rank_current_point_type'];
    $rank_current_point = $_POST['rank_current_point'] ?? 0;

    $sql = "INSERT INTO tier (user_id, rank_tertinggi, rank_tertinggi_img, rank_tertinggi_point, rank_tertinggi_point_type, rank_saat_ini, rank_saat_ini_img, rank_saat_ini_point, rank_saat_ini_point_type, created_at)
    VALUES ('$user_id','$rank_high','$rank_high_img','$rank_high_point','$rank_high_point_type','$rank_current','$rank_current_img','$rank_current_point','$rank_current_point_type',NOW())";

    if(mysqli_query($conn,$sql)){
        echo "<script>alert('Tier berhasil ditambahkan');window.location='tier.php';</script>";
    }else{
        echo "<script>alert('Gagal menyimpan ke database!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Tier</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:linear-gradient(135deg,#0a0a0a,#1a1a2e);color:#fff;font-family:Poppins;}
.form-container{max-width:600px;margin:50px auto;padding:25px;border-radius:15px;background:rgba(255,255,255,0.08);box-shadow:0 0 25px rgba(0,255,255,0.3);}
.rank-preview{width:60px;height:60px;object-fit:cover;border-radius:50%;border:2px solid #0ff;box-shadow:0 0 15px #0ff;margin-right:10px;transition:all .3s;animation:shine 2s linear infinite;}
@keyframes shine{0%{box-shadow:0 0 5px #0ff;}50%{box-shadow:0 0 25px #0ff;}100%{box-shadow:0 0 5px #0ff;}}
</style>
</head>
<body>
<div class="container">
<div class="form-container">
<h3 class="text-info text-center mb-4">Tambah Tier</h3>
<form method="POST">

<!-- Rank Tertinggi -->
<div class="mb-3">
    <label class="form-label">Rank Tertinggi</label>
    <div class="d-flex align-items-center mb-2">
        <img id="previewHigh" src="assets/img/tier/warrior.webp" class="rank-preview">
        <select id="rankHigh" name="rank_high" class="form-select">
            <?php foreach($ranks as $r): ?>
                <option value="<?php echo $r['name'];?>" data-img="<?php echo $r['img'];?>"><?php echo $r['name'];?></option>
            <?php endforeach;?>
        </select>
        <input type="hidden" name="rank_high_img" id="rankHighImg" value="assets/img/tier/warrior.webp">
    </div>
    <label>Point Type</label>
    <select id="highPointType" name="rank_high_point_type" class="form-select">
        <option value="level">Level</option>
        <option value="star">Star</option>
    </select>
    <div id="highPointContainer" class="mt-2">
        <select name="rank_high_point" id="highPoint" class="form-select">
            <option value="I">I</option>
            <option value="II">II</option>
            <option value="III">III</option>
            <option value="IV">IV</option>
            <option value="V">V</option>
        </select>
    </div>
</div>

<!-- Rank Saat Ini -->
<div class="mb-3">
    <label class="form-label">Rank Saat Ini</label>
    <div class="d-flex align-items-center mb-2">
        <img id="previewCurrent" src="assets/img/tier/warrior.webp" class="rank-preview">
        <select id="rankCurrent" name="rank_current" class="form-select">
            <?php foreach($ranks as $r): ?>
                <option value="<?php echo $r['name'];?>" data-img="<?php echo $r['img'];?>"><?php echo $r['name'];?></option>
            <?php endforeach;?>
        </select>
        <input type="hidden" name="rank_current_img" id="rankCurrentImg" value="assets/img/tier/warrior.webp">
    </div>
    <label>Point Type</label>
    <select id="currentPointType" name="rank_current_point_type" class="form-select">
        <option value="level">Level</option>
        <option value="star">Star</option>
    </select>
    <div id="currentPointContainer" class="mt-2">
        <select name="rank_current_point" id="currentPoint" class="form-select">
            <option value="I">I</option>
            <option value="II">II</option>
            <option value="III">III</option>
            <option value="IV">IV</option>
            <option value="V">V</option>
        </select>
    </div>
</div>

<div class="text-center mt-4">
    <button type="submit" name="save" class="btn btn-custom">Simpan</button>
    <a href="tier.php" class="btn btn-outline-info ms-2">Batal</a>
</div>
</form>
</div>
</div>

<script>
// Rank Tertinggi
const rankHigh = document.getElementById('rankHigh');
const previewHigh = document.getElementById('previewHigh');
const rankHighImg = document.getElementById('rankHighImg');
rankHigh.addEventListener('change', ()=>{
    const img = rankHigh.selectedOptions[0].dataset.img;
    previewHigh.src = img;
    rankHighImg.value = img;
});

// Rank Saat Ini
const rankCurrent = document.getElementById('rankCurrent');
const previewCurrent = document.getElementById('previewCurrent');
const rankCurrentImg = document.getElementById('rankCurrentImg');
rankCurrent.addEventListener('change', ()=>{
    const img = rankCurrent.selectedOptions[0].dataset.img;
    previewCurrent.src = img;
    rankCurrentImg.value = img;
});

// High Point Type
const highPointType = document.getElementById('highPointType');
const highPointContainer = document.getElementById('highPointContainer');
highPointType.addEventListener('change', ()=>{
    if(highPointType.value==='level'){
        highPointContainer.innerHTML = `<select name="rank_high_point" id="highPoint" class="form-select">
            <option value="I">I</option><option value="II">II</option><option value="III">III</option>
            <option value="IV">IV</option><option value="V">V</option></select>`;
    } else {
        highPointContainer.innerHTML = `<input type="number" name="rank_high_point" id="highPoint" class="form-control" value="0" min="0">`;
    }
});

// Current Point Type
const currentPointType = document.getElementById('currentPointType');
const currentPointContainer = document.getElementById('currentPointContainer');
currentPointType.addEventListener('change', ()=>{
    if(currentPointType.value==='level'){
        currentPointContainer.innerHTML = `<select name="rank_current_point" id="currentPoint" class="form-select">
            <option value="I">I</option><option value="II">II</option><option value="III">III</option>
            <option value="IV">IV</option><option value="V">V</option></select>`;
    } else {
        currentPointContainer.innerHTML = `<input type="number" name="rank_current_point" id="currentPoint" class="form-control" value="0" min="0">`;
    }
});
</script>
</body>
</html>
