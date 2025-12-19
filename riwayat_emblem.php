<?php
include 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) header("Location:index.php");
$user_id = $_SESSION['user_id'];

// Jenis emblem
$types = ['physical','jungle','tank','fighter','assassin','mage','marksman','support'];
$levels = ['0','4','10','20','30','40','60','I','II','III','V','VII','VIII','IX'];

// Proses update level terbaru
if(isset($_POST['save'])){
    $update = [];
    foreach($types as $t){
        $val = $_POST[$t] ?? '0';
        $update[] = "$t='$val'";
    }
    $sql_update = "UPDATE emblem SET ".implode(',',$update)." WHERE user_id='$user_id'";
    mysqli_query($conn,$sql_update);

    // Simpan ke riwayat
    $fields = implode(',',$types);
    $values = implode(',',array_map(fn($t)=>"'".$_POST[$t]."'", $types));
    mysqli_query($conn,"INSERT INTO emblem_history (user_id,$fields,created_at) VALUES ('$user_id',$values,NOW())");

    echo "<script>alert('Level emblem berhasil diperbarui!');window.location='riwayat_emblem.php';</script>";
}

// Ambil data emblem terakhir
$data = mysqli_query($conn,"SELECT * FROM emblem WHERE user_id='$user_id'");
$row = mysqli_fetch_assoc($data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat & Edit Emblem</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:linear-gradient(135deg,#0a0a0a,#1a1a2e);color:#fff;font-family:Poppins;}
.table-dark{--bs-table-bg:rgba(255,255,255,0.1);}
select.form-select{background:#111;color:#0ff;font-weight:bold;}
.btn-custom{background:#0ff;color:#000;font-weight:bold;}
.btn-custom:hover{background:#09c;color:#fff;}
.badge-high{background:#0ff;color:#000;font-weight:bold;}
</style>
</head>
<body class="p-4">
<div class="container mt-4">
<h2 class="text-info mb-4">Riwayat & Edit Emblem</h2>
<form method="POST">
<div class="table-responsive">
<table class="table table-dark table-bordered table-hover align-middle text-center">
<thead>
<tr>
    <th>#</th>
    <?php foreach($types as $t) echo "<th>".ucfirst($t)."</th>"; ?>
    <th>Nilai Tertinggi</th>
</tr>
</thead>
<tbody>
<tr>
    <td>1</td>
    <?php
    $maxLevel = 0; $maxLevelStr = '';
    foreach($types as $t){
        $cur = $row[$t] ?? '0';
        $valMap = ['0'=>0,'4'=>1,'10'=>2,'20'=>3,'30'=>4,'40'=>5,'60'=>6,'I'=>7,'II'=>8,'III'=>9,'V'=>10,'VII'=>11,'VIII'=>12,'IX'=>13];
        $val = $valMap[$cur] ?? 0;
        if($val>$maxLevel){ $maxLevel=$val; $maxLevelStr=$cur; }

        echo "<td>
                <select name='$t' class='form-select'>";
        foreach($levels as $l){
            $sel = ($l==$cur)?'selected':'';
            echo "<option value='$l' $sel>$l</option>";
        }
        echo "</select></td>";
    }
    echo "<td><span class='badge badge-high'>{$maxLevelStr}</span></td>";
    ?>
</tr>
</tbody>
</table>
</div>
<div class="text-center mt-3">
    <button type="submit" name="save" class="btn btn-custom">Simpan Perubahan</button>
    <a href="dashboard.php" class="btn btn-outline-info ms-2">← Kembali</a>
</div>
</form>
</div>
</body>
</html>
