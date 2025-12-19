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

    // Ambil file gambar dulu supaya bisa dihapus dari folder
    $result = mysqli_query($conn, "SELECT file_path FROM heroes WHERE id='$id' AND user_id='$user_id'");
    if ($row = mysqli_fetch_assoc($result)) {
        if (file_exists($row['file_path'])) {
            unlink($row['file_path']); // hapus file fisik
        }
    }

    // Hapus data dari database
    $sql = "DELETE FROM heroes WHERE id='$id' AND user_id='$user_id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Hero berhasil dihapus');window.location='heroes.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus hero');window.location='heroes.php';</script>";
    }
} else {
    header("Location: heroes.php");
    exit;
}
?>
