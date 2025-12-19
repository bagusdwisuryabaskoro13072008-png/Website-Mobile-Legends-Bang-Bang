<?php
include 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Ambil ID dari parameter GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // Hapus data tier hanya jika milik user yang login
    $sql = "DELETE FROM tier WHERE id='$id' AND user_id='$user_id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Tier berhasil dihapus');window.location='tier.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data tier');window.location='tier.php';</script>";
    }
} else {
    header("Location: tier.php");
    exit;
}
?>
