<?php
include 'config.php';
if (session_status()===PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) header("Location:index.php");
$user_id = $_SESSION['user_id'];

// Data dropdown rank
$ranks = [
    ['name'=>'Warrior','img'=>'assets/img/tier/warrior.png'],
    ['name'=>'Elite','img'=>'assets/img/tier/elite.png'],
    ['name'=>'Master','img'=>'assets/img/tier/master.png'],
    ['name'=>'Grandmaster','img'=>'assets/img/tier/grandmaster.png'],
    ['name'=>'Epic','img'=>'assets/img/tier/epic.png'],
    ['name'=>'Legend','img'=>'assets/img/tier/legend.png'],
    ['name'=>'Mythic','img'=>'assets/img/tier/mythic.png'],
    ['name'=>'Immortal','img'=>'assets/img/tier/immortal.png']
];

// Ambil data tier yang akan diubah
if(!isset($_GET['id'])){
    header("Location: riwayat_tier.php");
    exit;
}
$tier_id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM tier_history WHERE id='$tier_id' AND user_id='$user_id'");
$tier = mysqli_fetch_assoc($result);
if(!$tier){
    $_SESSION['message'] = "Data tier tidak ditemukan!";
    header("Location: riwayat_tier.php");
    exit;
}

// Default jika NULL
$rank_tertinggi_point_type = $tier['rank_tertinggi_point_type'] ?? 'level';
$rank_tertinggi_point = $tier['rank_tertinggi_point'] ?? 'I';
$rank_saat_ini_point_type = $tier['rank_saat_ini_point_type'] ?? 'level';
$rank_saat_ini_point = $tier['rank_saat_ini_point'] ?? 'I';

// Proses submit
if(isset($_POST['save'])){
    $rank_high = $_POST['rank_high'];
    $rank_high_img = $_POST['rank_high_img'];
    $rank_high_point_type = $_POST['rank_high_point_type'];
    $rank_high_point = $_POST['rank_high_point'] ?? 'I';

    $rank_current = $_POST['rank_current'];
    $rank_current_img = $_POST['rank_current_img'];
    $rank_current_point_type = $_POST['rank_current_point_type'];
    $rank_current_point = $_POST['rank_current_point'] ?? 'I';

    $sql = "UPDATE tier_history SET 
        rank_tertinggi='$rank_high',
        rank_tertinggi_img='$rank_high_img',
        rank_tertinggi_point='$rank_high_point',
        rank_tertinggi_point_type='$rank_high_point_type',
        rank_saat_ini='$rank_current',
        rank_saat_ini_img='$rank_current_img',
        rank_saat_ini_point='$rank_current_point',
        rank_saat_ini_point_type='$rank_current_point_type',
        created_at=NOW()
        WHERE id='$tier_id' AND user_id='$user_id'";

    if(mysqli_query($conn,$sql)){
        echo "<script>alert('Tier berhasil diubah');window.location='riwayat_tier.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan ke database!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Ubah Tier</title>
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
<h3 class="text-info text-center mb-4">✏️ Ubah Tier</h3>
<form method="POST">
    <!-- Rank Tertinggi -->
<div class="mb-3">
    <label class="form-label">Rank Tertinggi</label>
    <div class="d-flex align-items-center mb-2">
        <img id="previewHigh" src="<?php echo $tier['rank_tertinggi_img'] ?: 'assets/img/tier/warrior.png'; ?>" class="rank-preview">
        <select id="rankHigh" name="rank_high" class="form-select">
            <?php foreach($ranks as $r): ?>
                <option value="<?php echo $r['name'];?>" data-img="<?php echo $r['img'];?>" <?php echo ($tier['rank_tertinggi']==$r['name'])?'selected':'';?>><?php echo $r['name'];?></option>
            <?php endforeach;?>
        </select>
    </div>
    <input type="hidden" name="rank_high_img" id="rankHighImg" value="<?php echo $tier['rank_tertinggi_img'];?>">

    <label>Point Type</label>
    <select id="highPointType" name="rank_high_point_type" class="form-select">
        <option value="level" <?php echo ($rank_tertinggi_point_type=='level')?'selected':'';?>>Level</option>
        <option value="star" <?php echo ($rank_tertinggi_point_type=='star')?'selected':'';?>>Star</option>
    </select>
    <div id="highPointContainer" class="mt-2">
        <?php if($rank_tertinggi_point_type=='level'): ?>
        <select name="rank_high_point" id="highPoint" class="form-select">
            <option value="I" <?php echo ($rank_tertinggi_point=='I')?'selected':'';?>>I</option>
            <option value="II" <?php echo ($rank_tertinggi_point=='II')?'selected':'';?>>II</option>
            <option value="III" <?php echo ($rank_tertinggi_point=='III')?'selected':'';?>>III</option>
            <option value="IV" <?php echo ($rank_tertinggi_point=='IV')?'selected':'';?>>IV</option>
            <option value="V" <?php echo ($rank_tertinggi_point=='V')?'selected':'';?>>V</option>
        </select>
        <?php else: ?>
        <input type="number" name="rank_high_point" id="highPoint" class="form-control" value="<?php echo $rank_tertinggi_point;?>" min="0">
        <?php endif; ?>
    </div>
</div>

<!-- Rank Saat Ini -->
<div class="mb-3">
    <label class="form-label">Rank Saat Ini</label>
    <div class="d-flex align-items-center mb-2">
        <img id="previewCurrent" src="<?php echo $tier['rank_saat_ini_img'] ?: 'assets/img/tier/warrior.png'; ?>" class="rank-preview">
        <select id="rankCurrent" name="rank_current" class="form-select">
            <?php foreach($ranks as $r): ?>
                <option value="<?php echo $r['name'];?>" data-img="<?php echo $r['img'];?>" <?php echo ($tier['rank_saat_ini']==$r['name'])?'selected':'';?>><?php echo $r['name'];?></option>
            <?php endforeach;?>
        </select>
    </div>
    <input type="hidden" name="rank_current_img" id="rankCurrentImg" value="<?php echo $tier['rank_saat_ini_img'];?>">

    <label>Point Type</label>
    <select id="currentPointType" name="rank_current_point_type" class="form-select">
        <option value="level" <?php echo ($rank_saat_ini_point_type=='level')?'selected':'';?>>Level</option>
        <option value="star" <?php echo ($rank_saat_ini_point_type=='star')?'selected':'';?>>Star</option>
    </select>
    <div id="currentPointContainer" class="mt-2">
        <?php if($rank_saat_ini_point_type=='level'): ?>
        <select name="rank_current_point" id="currentPoint" class="form-select">
            <option value="I" <?php echo ($rank_saat_ini_point=='I')?'selected':'';?>>I</option>
            <option value="II" <?php echo ($rank_saat_ini_point=='II')?'selected':'';?>>II</option>
            <option value="III" <?php echo ($rank_saat_ini_point=='III')?'selected':'';?>>III</option>
            <option value="IV" <?php echo ($rank_saat_ini_point=='IV')?'selected':'';?>>IV</option>
            <option value="V" <?php echo ($rank_saat_ini_point=='V')?'selected':'';?>>V</option>
        </select>
        <?php else: ?>
        <input type="number" name="rank_current_point" id="currentPoint" class="form-control" value="<?php echo $rank_saat_ini_point;?>" min="0">
        <?php endif; ?>
    </div>
</div>

<div class="text-center mt-4">
    <button type="submit" name="save" class="btn btn-info w-100">💾 Simpan Perubahan</button>
    <a href="riwayat_tier.php" class="btn btn-outline-light w-100 mt-2">← Kembali</a>
</div>
</form>
</div>
</div>

<script>
// Preview rank
function setupRankPreview(selectId, imgId, hiddenId){
    const sel = document.getElementById(selectId);
    const img = document.getElementById(imgId);
    const hidden = document.getElementById(hiddenId);
    sel.addEventListener('change', ()=>{
        img.src = sel.selectedOptions[0].dataset.img;
        hidden.value = sel.selectedOptions[0].dataset.img;
    });
}
setupRankPreview('rankHigh','previewHigh','rankHighImg');
setupRankPreview('rankCurrent','previewCurrent','rankCurrentImg');

// Update point container
function updatePointContainer(containerId, type, currentValue){
    const container = document.getElementById(containerId);
    const nameAttr = containerId.includes('High') ? 'rank_high_point' : 'rank_current_point';
    if(type==='level'){
        container.innerHTML=`<select name="${nameAttr}" class="form-select">
            <option value="I" ${currentValue==='I'?'selected':''}>I</option>
            <option value="II" ${currentValue==='II'?'selected':''}>II</option>
            <option value="III" ${currentValue==='III'?'selected':''}>III</option>
            <option value="IV" ${currentValue==='IV'?'selected':''}>IV</option>
            <option value="V" ${currentValue==='V'?'selected':''}>V</option>
        </select>`;
    } else {
        container.innerHTML=`<input type="number" name="${nameAttr}" class="form-control" value="${currentValue}" min="0">`;
    }
}

document.getElementById('highPointType').addEventListener('change', ()=>{
    const val = document.getElementById('highPointType').value;
    const curVal = document.getElementById('highPoint') ? document.getElementById('highPoint').value : '0';
    updatePointContainer('highPointContainer', val, curVal);
});
document.getElementById('currentPointType').addEventListener('change', ()=>{
    const val = document.getElementById('currentPointType').value;
    const curVal = document.getElementById('currentPoint') ? document.getElementById('currentPoint').value : '0';
    updatePointContainer('currentPointContainer', val, curVal);
});
</script>
</body>
</html>
