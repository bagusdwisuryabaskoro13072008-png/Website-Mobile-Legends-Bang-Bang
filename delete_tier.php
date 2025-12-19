<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    $delete = mysqli_query($conn, "DELETE FROM tier_history WHERE id='$id' AND user_id='$user_id'");
    
    if($delete){
        $_SESSION['message'] = "Riwayat berhasil dihapus!";
    } else {
        $_SESSION['message'] = "Gagal menghapus riwayat!";
    }
}

header("Location: riwayat_tier.php");
exit;
